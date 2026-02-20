<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invoice;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentProofController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['customer.user', 'paymentProof'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.payment-proofs.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,invoice_id',
            'file_bukti' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Cegah upload dobel
        if (PaymentProof::where('invoice_id', $request->invoice_id)->exists()) {
            return back()->with('error', 'Bukti pembayaran sudah diupload.');
        }

        $file = $request->file('file_bukti');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->storeAs('payment_proofs', $filename, 'public');

        PaymentProof::create([
            'invoice_id' => $request->invoice_id,
            'file_bukti' => $filename,
            'status' => 'pending',
        ]);

        // 🔥 UPDATE INVOICE
        Invoice::where('invoice_id', $request->invoice_id)->update([
            'tanggal_bayar' => now(),
            'status' => 'unpaid', // atau 'pending_verification' kalau mau diverifikasi dulu
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload.');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentProof $payment_proof)
    {
        $request->validate([
        'status' => 'required|in:pending,verified,rejected'
        ]);

        $payment_proof->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.payment-proofs.index')
            ->with('success', 'Status bukti pembayaran diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentProof $paymentProof)
    {
        // Hapus file fisik dari storage
        if (Storage::disk('public')->exists('payment_proofs/' . $paymentProof->file_bukti)) {
            Storage::disk('public')->delete('payment_proofs/' . $paymentProof->file_bukti);
        }

        $paymentProof->delete();

        return back()->with('success', 'Bukti pembayaran berhasil dihapus.');
        }
}
