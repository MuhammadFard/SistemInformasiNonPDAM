@extends('adminlte::auth.auth-page')

@section('auth_header', 'Reset Password')

@section('auth_body')
@if (session('status'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('status') }}
    </div>
@endif

@error('email')
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ $message }}
    </div>
@enderror

<form action="{{ route('password.email') }}" method="post">
    @csrf
    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Kirim Link Reset Password</button>
</form>

<div class="mt-2 text-center">
    <a href="{{ route('login') }}">Kembali ke Login</a>
</div>
@endsection
