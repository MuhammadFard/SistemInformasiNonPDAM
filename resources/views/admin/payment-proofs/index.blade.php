@extends('adminlte::page')

@section('title', 'Bukti Pembayaran')

@section('content_header')
<h1>Bukti Pembayaran</h1>
@stop

@section('content')
{{-- NOTIFIKASI --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        {{ $errors->first() }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Invoice</th>
            <th>Pelanggan</th>
            <th>Total</th>
            <th>Status</th>
            <th>Bukti Pembayaran</th>
        </tr>
    </thead>
    <tbody>
    @foreach($invoices as $invoice)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $invoice->nomor_invoice }}</td>
            <td>{{ $invoice->customer->user->nama }}</td>
            <td>Rp {{ number_format($invoice->total_tagihan,0,',','.') }}</td>

            {{-- STATUS --}}
            <td>
                @if($invoice->paymentProof)
                    <span class="badge badge-{{
                        $invoice->paymentProof->status == 'verified' ? 'success' :
                        ($invoice->paymentProof->status == 'rejected' ? 'danger' : 'warning')
                    }}">
                        {{ ucfirst($invoice->paymentProof->status) }}
                    </span>
                @else
                    <span class="badge badge-secondary">Belum Upload</span>
                @endif
            </td>

            {{-- UPLOAD --}}
            <td>
                @if(!$invoice->paymentProof)
                    <form action="{{ route('admin.payment-proofs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="invoice_id" value="{{ $invoice->invoice_id }}">
                        <input type="file" name="file_bukti" class="form-control mb-2" required>
                        <button class="btn btn-sm btn-primary">Upload</button>
                    </form>
                @else
                    <a href="{{ asset('storage/payment_proofs/'.$invoice->paymentProof->file_bukti) }}"
                       target="_blank"
                       class="btn btn-sm btn-info">
                       Lihat Bukti
                    </a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@stop
