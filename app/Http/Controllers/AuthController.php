<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            if ($user->role === 'customer' && $user->is_verified == false) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('verify','Akun anda belum diverifikasi oleh admin.');
            }

            // Redirect semua role ke admin dashboard
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

     // ==== CUSTOMER REGISTRATION ====

    // Tampilkan form registrasi
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string',
            'kwh_category_id' => 'required|exists:kwh_categories,kwh_category_id',
        ]);

        DB::transaction(function () use ($request) {

            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
                'account_type' => 'simple',
                'is_verified' => false,
            ]);

            Customer::create([
                'user_id' => $user->user_id,
                'alamat' => $request->alamat,
                'nomor_telepon' => $request->nomor_telepon,
                'kwh_category_id' => $request->kwh_category_id,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Menunggu verifikasi admin.');
    }


    // Tampilkan form email untuk reset password
    public function showResetForm()
    {
        return view('auth.password.email');
    }

    public function sendResetLink(Request $request)
    {
        // Validasi email
        $request->validate([
            'email' => 'required|email'
        ]);

        // Kirim link reset password
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Cek status kirim
        if ($status === Password::RESET_LINK_SENT) {
            // Flash message sukses dengan teks custom
            return back()->with('status', 'Link reset password telah dikirim ke email Anda!');
        } else {
            // Flash message error
            return back()->withErrors(['email' => 'Email tidak ditemukan di sistem.']);
        }
    }

    // Form untuk set password baru (dari token)
    public function showNewPasswordForm($token)
    {
        return view('auth.password.reset', ['token' => $token]);
    }

    // Update password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                // $user->setRememberToken(Str::random(10));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
