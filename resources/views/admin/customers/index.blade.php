@extends('adminlte::page')

@section('title', 'Pelanggan')

@section('content_header')
<h1>Pelanggan</h1>
@stop

@section('content')

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            @if(in_array(auth()->user()->role, ['superadmin', 'viewer']))
                <th>Id Pelanggan</th>
            @endif
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>RT</th>
            <th>RW</th>
            <th>Kategori Daya</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $key => $customer)
        <tr>
            <td>{{ $key+1 }}</td>
            @if(in_array(auth()->user()->role, ['superadmin', 'viewer']))
                <td>{{ $customer->customer_id }}</td>
            @endif
            <td>{{ $customer->user->nama }}</td>
            <td>{{ $customer->user->email }}</td>
            <td>{{ $customer->nomor_telepon }}</td>
            <td>{{ $customer->alamat }}</td>
            <td>{{ $customer->rt }}</td>
            <td>{{ $customer->rw }}</td>
            <td>
                {{ $customer->kwhCategory->daya ?? '-' }}
                ({{ number_format($customer->kwhCategory->tarif_bulanan, 0, ',', '.') }} / bulan)
            </td>
            <td>
                <div class="btn-group">
                    {{-- Kondisi 1: Superadmin (Akses Penuh) --}}
                    @if(auth()->user()?->role === 'superadmin')
                        <a href="{{ route('admin.customers.edit', $customer->customer_id) }}" class="btn btn-sm btn-warning mr-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.customers.destroy', $customer->customer_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pelanggan?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                        </form>

                    {{-- Kondisi 2: Customer (Hanya edit miliknya sendiri) --}}
                    @elseif(auth()->user()?->role === 'customer' && $customer->user_id === auth()->user()->user_id)
                        <a href="{{ route('admin.customers.edit', $customer->customer_id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-user-edit"></i> Edit Profil
                        </a>

                    {{-- Kondisi 3: Viewer atau Data Orang Lain --}}
                    @else
                        <span class="badge badge-secondary p-2"><i class="fas fa-lock mr-1"></i> Read Only</span>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop
