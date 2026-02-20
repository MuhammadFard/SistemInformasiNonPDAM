@extends('adminlte::page')

@section('title', 'Tambah Kategori Daya')

@section('content_header')
<h1>Tambah Kategori Daya</h1>
@stop

{{-- @section('content')
<form action="{{ route('admin.kwh-categories.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Daya (VA)</label>
        <input type="number" name="daya" class="form-control" value="{{ old('daya') }}" required>
    </div>
    <div class="form-group">
        <label>Tarif Bulanan</label>
        <input type="number" name="tarif_bulanan" class="form-control" value="{{ old('tarif_bulanan') }}" required>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>
@stop --}}

@section('content')
<form action="{{ route('admin.kwh-categories.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Daya (VA)</label>
        <input type="text" name="daya" class="form-control"
               placeholder="Contoh: 450 atau 900-2200"
               value="{{ old('daya') }}" required>
        <small class="text-muted">Gunakan tanda (-) jika ingin memasukkan rentang daya.</small>
    </div>
    <div class="form-group">
        <label>Tarif Bulanan</label>
        <input type="number" name="tarif_bulanan" class="form-control" value="{{ old('tarif_bulanan') }}" required>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>
@stop
