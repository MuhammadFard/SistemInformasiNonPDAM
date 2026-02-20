<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            $customers = Customer::where('user_id', $user->user_id)->get();
        } else {
            $customers = Customer::with('user')->get();
        }

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string',
            'rw' => 'nullable|string',
            'kwh_category_id' => 'required|exists:kwh_categories,kwh_category_id', // asumsi ada table kategori daya
        ]);

        Customer::create($request->all());

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil dibuat.');
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
    public function edit(Customer $customer)
    {
        $user = Auth::user();
        // Proteksi: Customer hanya boleh edit datanya sendiri
        if ($user->role === 'customer' && $customer->user_id !== $user->user_id) {
            abort(403, 'Akses ditolak.');
        }

        return view('admin.customers.edit', compact('customer'));
        // return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $user = Auth::user();

        // Proteksi keamanan
        if ($user->role === 'customer' && $customer->user_id !== $user->user_id) {
            abort(403);
        }
        $rules = [
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string',
            'rw' => 'nullable|string',
        ];
        // Hanya Superadmin yang wajib memvalidasi kwh_category_id
        if ($user->role === 'superadmin') {
            $rules['kwh_category_id'] = 'required|exists:kwh_categories,kwh_category_id';
        }
        $request->validate($rules);
        // Filter data yang boleh diupdate
        $data = $request->only('nomor_telepon', 'alamat','rt','rw');

        // Jika superadmin, tambahkan kwh_category_id ke array data
        if ($user->role === 'superadmin') {
            $data['kwh_category_id'] = $request->kwh_category_id;
        }
        $customer->update($data);
        return redirect()->route('admin.customers.index')
                        ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
