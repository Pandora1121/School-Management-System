@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            <h4 class="mb-4 text-center">Login Sistem Manajemen Sekolah</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="{{ old('username') }}" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                        <div class="mb-3 text-end">
            <a href="{{ route('password.request') }}" class="small">Lupa Password?</a>
        </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-center mt-3 mb-0">
                Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
            </p>
        </div>
    </div>
</div>
@endsection