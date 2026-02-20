@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>Users</h1>
@stop

@section('content')
<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Tambah User</a>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Verifikasi Akun</th>
            <th>Tanggal Terdaftar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $key => $user)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $user->nama }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td>
                @if($user->is_verified)
                    <span class="badge badge-success">Verified</span>
                @else
                    <span class="badge badge-warning">Pending</span>
                @endif
            </td>
            <td>
                {{ $user->tanggal_terdaftar ? $user->tanggal_terdaftar->format('d/m/Y') : '-' }}
            </td>
            <td>
                <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-sm btn-warning">Edit</a>

                <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus user ini?')" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop
