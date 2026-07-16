@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Dashboard</h4>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-danger btn-sm">Logout</button>
        </form>
    </div>

    <p class="text-muted">Selamat datang, <strong>{{ auth()->user()->name }}</strong>
        ({{ auth()->user()->role == 1 ? 'Super Admin' : 'Admin' }})</p>

    <div class="row g-3 mt-2">
        <div class="col-md-{{ auth()->user()->role == 1 ? '3' : '4' }}">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Siswa</p>
                    <h3 class="mb-0">{{ number_format($totalStudents, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-{{ auth()->user()->role == 1 ? '3' : '4' }}">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Kelas</p>
                    <h3 class="mb-0">{{ number_format($totalClasses, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-{{ auth()->user()->role == 1 ? '3' : '4' }}">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Jurusan</p>
                    <h3 class="mb-0">{{ number_format($totalMajors, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        @if (auth()->user()->role == 1)
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total User</p>
                    <h3 class="mb-0">{{ number_format($totalUsers, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection