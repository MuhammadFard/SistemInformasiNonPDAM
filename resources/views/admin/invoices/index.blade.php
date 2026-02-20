@extends('adminlte::page')

@section('title', 'Invoice')

@section('css')
<style>
    /* Membuat ukuran tombol seragam */
    .btn-fixed {
        width: 100px; /* Atur lebar sesuai kebutuhan */
        margin-bottom: 2px;
    }
    /* Khusus tombol icon saja (seperti hapus bukti) agar tidak terlalu lebar */
    .btn-icon-fixed {
        width: 40px;
    }
    /* Menjaga agar form dalam tabel tidak merusak layout */
    .d-inline-block {
        display: inline-block;
    }
</style>
@stop

@section('content_header')
    <h1 class="mb-3">Invoice</h1>
    @if(auth()->user()->role === 'superadmin')
        <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary mb-2">Buat Invoice</a>
    @endif
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nomor Invoice</th>
            <th>Pelanggan</th>
            <th>Total Tagihan</th>
            <th>Jatuh Tempo</th>
            <th>Tanggal Bayar</th>
            <th>Bukti Pembayaran</th>
            <th>Status</th>
            <th>Catatan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $key => $invoice)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $invoice->nomor_invoice }}</td>
            <td>{{ $invoice->customer->user->nama }} ({{ $invoice->customer->user->email }})</td>
            <td>{{ number_format($invoice->total_tagihan,0,',','.') }}</td>
            <td>{{ $invoice->tanggal_jatuh_tempo }}</td>
            <td>{{ $invoice->tanggal_bayar }}</td>
            <td>
                @if(!$invoice->paymentProof)
                    <form action="{{ route('admin.payment-proofs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="invoice_id" value="{{ $invoice->invoice_id }}">
                        <input type="file" name="file_bukti" class="form-control form-control-sm mb-2" required>
                        <button class="btn btn-sm btn-primary btn-fixed">Upload</button>
                    </form>
                @else
                <div class="d-flex flex-wrap align-items-center">
                    <a href="{{ asset('storage/payment_proofs/'.$invoice->paymentProof->file_bukti) }}" target="_blank" class="btn btn-sm btn-info btn-fixed mr-1">
                        <i class="fas fa-eye"></i> Bukti
                    </a>

                    <button type="button"
                            onclick="printInvoice('{{ route('admin.invoices.print', $invoice->invoice_id) }}')"
                            class="btn btn-sm btn-info btn-fixed mr-1"
                            title="Cetak Invoice">
                        <i class="fas fa-print"></i> Print
                    </button>

                    {{-- Tombol Hapus Bukti (Hanya untuk Superadmin) --}}
                    @if(auth()->user()?->role === 'superadmin')
                        <form action="{{ route('admin.payment-proofs.destroy', $invoice->paymentProof->payment_proof_id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Hapus bukti pembayaran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-icon-fixed" title="Hapus Bukti">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
                @endif
            </td>
            <td>{{ ucfirst($invoice->status) }}</td>
            <td>{{ $invoice->catatan }}</td>
            <td>
                @if(auth()->user()?->role === 'superadmin')
                    <div class="d-flex flex-wrap">
                        <a href="{{ route('admin.invoices.edit', $invoice->invoice_id) }}"
                        class="btn btn-sm btn-warning btn-fixed mr-1"
                        title="Edit Invoice">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        <form action="{{ route('admin.invoices.destroy', $invoice->invoice_id) }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus invoice ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-fixed" title="Hapus Invoice">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                @else
                    <span class="badge badge-secondary p-2">
                        <i class="fas fa-lock mr-1"></i> Read Only
                    </span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<iframe id="printFrame" style="display:none;"></iframe>
@stop

@push('js')
<script>
    function printInvoice(url) {
        var iframe = document.getElementById('printFrame');
        const btn = event.currentTarget;
        const originalContent = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Wait';
        btn.disabled = true;

        iframe.src = url;

        iframe.onload = function() {
            setTimeout(function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }, 500);
        };
    }
</script>
@endpush
