@extends('adminlte::page')

@section('title', 'Buat Invoice')

@section('content_header')
<h1>Buat Invoice</h1>
@stop

@section('content')
<form action="{{ route('admin.invoices.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Pelanggan</label>
        <select name="customer_id" id="customer_id" class="form-control" required>
            <option value="">-- Pilih Pelanggan --</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->customer_id }}" data-tarif="{{ $customer->kwhCategory->tarif_bulanan }}">
                    {{ $customer->user->nama }} ({{ $customer->user->email }}) - {{ $customer->kwhCategory->daya }} VA
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Total Tagihan</label>
        <input type="text" id="total_tagihan" class="form-control" readonly>
    </div>

    <div class="form-group">
        <label>Tanggal Jatuh Tempo</label>
        <input type="date" name="tanggal_jatuh_tempo" 
            class="form-control" 
            value="{{ old('tanggal_jatuh_tempo', now()->addMonthNoOverflow()->startOfMonth()->format('Y-m-d')) }}" 
            required>
    </div>

    <button type="submit" class="btn btn-success">Simpan</button>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customerSelect = document.getElementById('customer_id');
        const totalTagihanInput = document.getElementById('total_tagihan');

        customerSelect.addEventListener('change', function() {
            const selected = customerSelect.options[customerSelect.selectedIndex];
            const tarif = selected.getAttribute('data-tarif');
            totalTagihanInput.value = tarif ? parseFloat(tarif).toLocaleString('id-ID') : '';
        });
    });
</script>
@stop
