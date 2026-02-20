@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
<h1>Edit User</h1>
@stop

@section('content')
<form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
    </div>

    <div class="form-group">
        <label>Password (Kosongkan jika tidak diganti)</label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="form-group">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>

    <div class="form-group">
        <label>Role</label>
        <select name="role" class="form-control" required>
            <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
            <option value="viewer" {{ $user->role == 'viewer' ? 'selected' : '' }}>Admin</option>
            <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
        </select>
    </div>
    <div class="form-group">
        <label>Status Verifikasi</label>
        <select name="is_verified" class="form-control">
            <option value="1" {{ $user->is_verified ? 'selected' : '' }}>
                Terverifikasi
            </option>
            <option value="0" {{ !$user->is_verified ? 'selected' : '' }}>
                Belum Diverifikasi
            </option>
        </select>
    </div>


    <button type="submit" class="btn btn-success">Update</button>
</form>
@stop
