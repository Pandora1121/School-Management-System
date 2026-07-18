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
        ({{ auth()->user()->role == 5 ? 'Wali Kelas' : 'Guru' }})</p>

    @if (!$teacher)
        <div class="alert alert-warning">Akun Anda belum terhubung ke data guru. Hubungi Admin.</div>
    @else
        <div class="row g-3 mt-2">
            @if (auth()->user()->role == 5)
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted mb-1">Kelas Diampu</p>
                        <h3 class="mb-0">{{ $classCount }}</h3>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-{{ auth()->user()->role == 5 ? '8' : '12' }}">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-3">Jadwal Mengajar Hari Ini</h5>
                        @if ($todayRoutines->isEmpty())
                            <p class="text-muted mb-0">Tidak ada jadwal mengajar hari ini.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach ($todayRoutines as $routine)
                                <li class="list-group-item px-0">
                                    <strong>{{ substr($routine->start_time, 0, 5) }} - {{ substr($routine->end_time, 0, 5) }}</strong>
                                    — {{ $routine->subject->name ?? '-' }} ({{ $routine->schoolClass->name ?? '-' }})
                                </li>
                                @endforeach
                            </ul>
                        @endif
                        <a href="{{ route('teacher.schedule') }}" class="btn btn-sm btn-primary mt-3">Lihat Jadwal Lengkap</a>
                        @if (auth()->user()->role == 5)
                            <a href="{{ route('teacher.classes') }}" class="btn btn-sm btn-outline-primary mt-3">Kelola Kelas Saya</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection