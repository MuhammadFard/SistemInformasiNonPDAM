@extends('adminlte::auth.auth-page')

@section('auth_header', 'Atur Password Baru')

@section('auth_body')
<form action="{{ route('password.update') }}" method="post">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password Baru" required>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
    </div>

    <button type="submit" class="btn btn-primary btn-block">Atur Password</button>
</form>

<div class="mt-2 text-center">
    <a href="{{ route('login') }}">Kembali ke Login</a>
</div>
@endsection
