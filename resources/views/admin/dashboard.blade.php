@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    {{-- Sambutan untuk User --}}
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Selamat Datang, {{ auth()->user()->nama }}!</h5>
                Anda login sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong>.
            </div>
        </div>
    </div>

    <div class="row">
        {{-- BLOK 1: TOTAL USERS (Hanya untuk Superadmin) --}}
        @if(auth()->user()->role === 'superadmin')
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Total User Sistem</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                    Kelola User <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        @endif

        {{-- BLOK 2: CUSTOMERS (Tampilan Berbeda tiap Role) --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalCustomers }}</h3>
                    <p>{{ auth()->user()->role === 'customer' ? 'Status Profil' : 'Total Pelanggan' }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <a href="{{ route('admin.customers.index') }}" class="small-box-footer">
                    {{ auth()->user()->role === 'customer' ? 'Lihat Profil Saya' : 'Daftar Pelanggan' }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- BLOK 3: INVOICES (Tampilan Berbeda tiap Role) --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalInvoices }}</h3>
                    <p>{{ auth()->user()->role === 'customer' ? 'Tagihan Saya' : 'Total Invoice' }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <a href="{{ route('admin.invoices.index') }}" class="small-box-footer">
                    {{ auth()->user()->role === 'customer' ? 'Bayar Tagihan' : 'Semua Invoice' }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Info Tambahan Khusus Customer --}}
    @if(auth()->user()->role === 'customer')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Informasi Akun</h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
                    <p class="text-muted">{{ auth()->user()->customer->alamat ?? 'Belum diisi' }}</p>
                    <hr>
                    <strong><i class="fas fa-bolt mr-1"></i> Kategori Daya</strong>
                    <p class="text-muted">{{ auth()->user()->customer->kwhCategory->daya ?? '-' }} VA</p>
                </div>
            </div>
        </div>
    </div>
    @endif
@stop

{{-- @extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
    <!-- Users -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalUsers }}</h3>
                <p>Users</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Customers -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalCustomers }}</h3>
                <p>Pelanggan</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-friends"></i>
            </div>
            <a href="{{ route('admin.customers.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Invoices -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalInvoices }}</h3>
                <p>Invoices</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <a href="{{ route('admin.invoices.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
@stop

 --}}

