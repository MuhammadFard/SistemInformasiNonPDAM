<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Jika yang login adalah customer
        if ($user->role === 'customer') {
            // Ambil invoice yang hanya milik customer ini melalui relasi
            $invoices = Invoice::whereHas('customer', function ($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })->get();
        } else {
            // Jika superadmin atau viewer, tampilkan semua
            $invoices = Invoice::with(['customer.user', 'paymentProof'])->get();
        }

        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::with('kwhCategory')->get();
        $jatuh_tempo = now()->addMonthNoOverflow()->startOfMonth()->toDateString();
        return view('admin.invoices.create', compact('customers', 'jatuh_tempo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:today',
            'catatan' => 'nullable|string',
        ]);

        $customer = Customer::with('kwhCategory')->findOrFail($request->customer_id);
        $total_tagihan = $customer->kwhCategory->tarif_bulanan;

        $jatuh_tempo = $request->tanggal_jatuh_tempo;

        $lastInvoice = Invoice::where('nomor_invoice', 'like', '07C%')
            ->orderBy('nomor_invoice', 'desc')
            ->first();

        if ($lastInvoice) {
            // Ambil bagian angka saja (semua karakter setelah indeks ke-3)
            $lastNumberString = substr($lastInvoice->nomor_invoice, 3);

            // Cek jika lastNumberString adalah angka valid, jika tidak mulai dari 10001
            $lastNumber = is_numeric($lastNumberString) ? (int) $lastNumberString : 10000;

            $nextNumber = ($lastNumber < 10001) ? 10001 : $lastNumber + 1;
        } else {
            $nextNumber = 10001;
        }

        $no_skr = '07C' . $nextNumber;

        Invoice::create([
            'customer_id' => $customer->customer_id,
            'nomor_invoice' => $no_skr, // Sekarang menggunakan format No. SKR
            'total_tagihan' => $total_tagihan,
            'tanggal_jatuh_tempo' => $jatuh_tempo,
            'catatan' => $request->catatan ?? '-',
            'status' => 'unpaid',
        ]);

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice/SKR berhasil dibuat.');
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
    public function edit(Invoice $invoice)
    {
        $customers = Customer::with('user')->get();
        return view('admin.invoices.edit', compact('invoice', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'total_tagihan' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date',
            'catatan' => 'nullable|string',
            'status' => 'required|in:unpaid,paid,overdue',
        ]);

        $invoice->update($request->only('customer_id','total_tagihan','tanggal_jatuh_tempo','catatan','status'));

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice berhasil dihapus.');
    }
    public function print(Invoice $invoice)
    {
        // Load relasi agar data pelanggan dan tarif muncul
        $invoice->load(['customer.user', 'customer.kwhCategory']);

        return view('admin.invoices.print', compact('invoice'));
    }
}
