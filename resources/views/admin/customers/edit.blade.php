@extends('adminlte::page')

@section('title', 'Edit Pelanggan')

@section('content_header')
<h1>Edit Pelanggan</h1>
@stop

@section('content')

<form action="{{ route('admin.customers.update', $customer->customer_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" value="{{ $customer->user->nama }}" readonly>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" value="{{ $customer->user->email }}" readonly>
    </div>

    <div class="form-group">
        <label>Telepon</label>
        <input type="text" name="nomor_telepon" class="form-control" value="{{ $customer->nomor_telepon }}">
    </div>

    <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control">{{ $customer->alamat }}</textarea>
    </div>
    <div class="form-group">
        <label>RT</label>
        <textarea name="rt" class="form-control">{{ $customer->rt }}</textarea>
    </div>
    <div class="form-group">
        <label>RW</label>
        <textarea name="rw" class="form-control">{{ $customer->rw }}</textarea>
    </div>

    <div class="form-group">
        <label>Kategori Daya</label>
        {{-- Jika Customer, select ini kita disable agar tidak bisa diubah --}}
        <select name="kwh_category_id" class="form-control" {{ auth()->user()->role === 'customer' ? 'disabled' : 'required' }}>
            @foreach(App\Models\KwhCategory::all() as $category)
                <option value="{{ $category->kwh_category_id }}"
                    {{ $customer->kwh_category_id == $category->kwh_category_id ? 'selected' : '' }}>
                    {{ $category->daya }} ({{ number_format($category->tarif_bulanan,0,',','.') }} / bulan)
                </option>
            @endforeach
        </select>
        @if(auth()->user()->role === 'customer')
            <small class="text-muted text-danger">*Hanya Admin yang dapat mengubah kategori daya.</small>
            {{-- Tambahkan hidden input agar value kategori tidak hilang saat disubmit oleh customer --}}
            <input type="hidden" name="kwh_category_id" value="{{ $customer->kwh_category_id }}">
        @endif
    </div>

    <button type="submit" class="btn btn-success">
        <i class="fas fa-save"></i> Simpan Perubahan
    </button>
</form>
@stop
