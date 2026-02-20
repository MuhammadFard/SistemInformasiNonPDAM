@extends('adminlte::page')

@section('title', 'Tambah User')

@section('content_header')
<h1>Tambah User</h1>
@stop

@section('content')
<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Role</label>
        <select name="role" class="form-control" required>
            <option value="superadmin">Superadmin</option>
            <option value="viewer">Admin</option>
            <option value="customer">Customer</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Simpan</button>
</form>
@stop
