@extends('adminlte::page')

@section('title', 'Kategori Daya')

@section('content_header')
<h1 class="mb-3">Kategori Daya</h1>
@if(auth()->user()->role === 'superadmin')
    <a href="{{ route('admin.kwh-categories.create') }}" class="btn btn-primary mb-2">Tambah Kategori</a>
@endif
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Daya (VA)</th>
            <th class="text-center">Tarif Bulanan</th>
            @if(auth()->user()?->role === 'superadmin')
                <th style="width: 200px" class="text-center">Aksi</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $key => $category)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-center">{{ $category->daya }} VA</td>
                <td class="text-center"><strong>Rp {{ number_format($category->tarif_bulanan, 0, ',', '.') }}</strong></td>
                    @if(auth()->user()?->role === 'superadmin')
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('admin.kwh-categories.edit', $category->kwh_category_id) }}"
                               class="btn btn-sm btn-warning mr-2">
                                <i class="fas fa-edit gap-3"></i> Edit
                            </a>

                            <form action="{{ route('admin.kwh-categories.destroy', $category->kwh_category_id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->role === 'superadmin' ? 4 : 3 }}" class="text-center">
                        Data kategori belum tersedia.
                    </td>
                </tr>
                @endforelse
    </tbody>
</table>
@stop
