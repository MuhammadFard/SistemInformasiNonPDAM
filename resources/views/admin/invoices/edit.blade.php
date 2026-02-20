@extends('adminlte::page')

@section('title', 'Edit Invoice')

@section('content_header')
<h1>Edit Invoice</h1>
@stop

@section('content')
<form action="{{ route('admin.invoices.update', $invoice->invoice_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Pelanggan</label>
        <select name="customer_id" class="form-control" required>
            @foreach($customers as $customer)
                <option value="{{ $customer->customer_id }}" {{ $invoice->customer_id == $customer->customer_id ? 'selected' : '' }}>
                    {{ $customer->user->nama }} ({{ $customer->user->email }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Total Tagihan</label>
        <input type="number" name="total_tagihan" class="form-control" value="{{ $invoice->total_tagihan }}" required>
    </div>

    <div class="form-group">
        <label>Jatuh Tempo</label>
        <input type="date" name="tanggal_jatuh_tempo" class="form-control" value="{{ $invoice->tanggal_jatuh_tempo }}" required>
    </div>
    <div class="form-group">
    <label for="catatan">Catatan</label>
        <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3">{{ old('catatan', $invoice->catatan) }}</textarea>
        @error('catatan')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="unpaid" {{ $invoice->status=='unpaid' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ $invoice->status=='paid' ? 'selected' : '' }}>Verified</option>
            <option value="overdue" {{ $invoice->status=='overdue' ? 'selected' : '' }}>Rejected</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Update</button>
</form>
@stop
