@extends('adminlte::auth.auth-page')

@section('auth_header', 'Daftar Akun Pelanggan')

@section('auth_body')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('register') }}" method="post">
    @csrf

    <div class="input-group mb-3">
        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" value="{{ old('nama') }}" required>
    </div>

    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
    </div>

    <div class="input-group mb-3">
        <input type="text" name="alamat" class="form-control" placeholder="Alamat" value="{{ old('alamat') }}" required>
    </div>

    <div class="input-group mb-3">
        <input type="text" name="nomor_telepon" class="form-control" placeholder="Nomor Telepon" value="{{ old('nomor_telepon') }}" required>
    </div>

    <div class="input-group mb-3">
        <select name="kwh_category_id" class="form-control" required>
            <option value="">-- Pilih KWH --</option>
            @foreach(\App\Models\KwhCategory::all() as $kwh)
                <option value="{{ $kwh->kwh_category_id }}">{{ $kwh->daya }}</option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
        </div>
    </div>
</form>

<div class="mt-2 text-center">
    <a href="{{ route('login') }}">Sudah punya akun? Login</a>
</div>
@endsection
