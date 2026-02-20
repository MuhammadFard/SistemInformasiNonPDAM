<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Models\Invoice;
// use App\Models\Customer;
use App\Models\Customer;
use App\Models\User;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            $users = User::where('user_id', $user->user_id)->get();
        } else {
            $users = User::all();
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:superadmin,viewer,customer',
        ]);

        $user = User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'tanggal_terdaftar' => now(),
        ]);

        if (in_array($request->role, ['viewer','customer'])) {
            Customer::create([
                'user_id' => $user->user_id,
                'kwh_category_id' => 1,
                'alamat' => '-',
                'rt' => '-',
                'rw' => '-',
                'nomor_telepon' => '-',
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'role'  => 'required|in:superadmin,viewer,customer',
            'is_verified' => 'required|boolean',
        ]);

        $user->update([
            'nama'  => $request->nama,
            'email' => $request->email,
            'role'  => $request->role,
            'is_verified' => $request->is_verified,
        ]);

        if($request->password){
            $request->validate(['password' => 'string|min:6|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function verify($user_id)
    {
        $user = User::findOrFail($user_id);

        if ($user->role === 'customer') {
            $user->is_verified = true;
            $user->tanggal_terdaftar = now();
            $user->save();
        }

        return redirect()->back()
            ->with('success','User berhasil diverifikasi.');
    }
}
