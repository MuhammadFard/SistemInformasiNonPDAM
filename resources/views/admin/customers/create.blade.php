@extends('adminlte::page')

@section('title', 'Tambah Pelanggan')

@section('content_header')
<h1>Tambah Pelanggan</h1>
@stop

@section('content')
<form action="{{ route('admin.customers.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>
    <div class="form-group">
        <label>Telepon</label>
        <input type="text" name="phone" class="form-control">
    </div>
    <div class="form-group">
        <label>Alamat</label>
        <textarea name="address" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <label>Kategori Daya</label>
        <select name="kwh_category_id" class="form-control" required>
            @foreach(App\Models\KwhCategory::all() as $category)
                <option value="{{ $category->kwh_category_id }}"
                    {{ (isset($customer) && $customer->kwh_category_id == $category->kwh_category_id) ? 'selected' : '' }}>
                    {{ $category->daya }} ({{ number_format($category->tarif_bulanan,0,',','.') }} / bulan)
                </option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>
@stop
