@extends('adminlte::page')

@section('title', 'Edit Kategori Daya')

@section('content_header')
<h1>Edit Kategori Daya</h1>
@stop

@section('content')
<form action="{{ route('admin.kwh-categories.update', $kwhCategory->kwh_category_id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Daya (VA)</label>
        <input type="text" name="daya" class="form-control" value="{{ $kwhCategory->daya }}" required>
    </div>
    <div class="form-group">
        <label>Tarif Bulanan</label>
        <input type="number" name="tarif_bulanan" class="form-control" value="{{ $kwhCategory->tarif_bulanan }}" required>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
</form>
@stop
