@extends('adminlte::auth.auth-page')

@section('auth_header', 'Login ke Sistem PDAM')

@section('auth_body')
@if(session('verify'))
    <div class="alert alert-warning">
        <i class="fas fa-clock"></i>
        {{ session('verify') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-times-circle"></i>
        {{ session('error') }}
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

<form action="{{ route('login') }}" method="post">
    @csrf

    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
        <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </div>
    </div>
    <div class="mt-2">
        <div style="float: left;">
            <a href="{{ route('register') }}">Belum punya akun</a>
        </div>
        <div style="float: right;">
            <a href="{{ route('password.request') }}">Reset Password</a>
        </div>
        <div style="clear: both;"></div>
    </div>
</form>
@endsection
