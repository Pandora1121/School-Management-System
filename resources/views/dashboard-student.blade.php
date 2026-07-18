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

    <p class="text-muted">Selamat datang, <strong>{{ auth()->user()->name }}</strong> (Siswa)</p>

    @if (!$student)
        <div class="alert alert-warning">Akun Anda belum terhubung ke data siswa. Hubungi Admin.</div>
    @else
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Jadwal Hari Ini</h5>
                        @if ($todayRoutines->isEmpty())
                            <p class="text-muted mb-0">Tidak ada jadwal pelajaran hari ini.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach ($todayRoutines as $routine)
                                <li class="list-group-item px-0">
                                    <strong>{{ substr($routine->start_time, 0, 5) }} - {{ substr($routine->end_time, 0, 5) }}</strong>
                                    — {{ $routine->subject->name ?? '-' }}
                                </li>
                                @endforeach
                            </ul>
                        @endif
                        <a href="{{ route('student.schedule') }}" class="btn btn-sm btn-primary mt-3">Lihat Jadwal Lengkap</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Presensi Terakhir</h5>
                        @if ($recentAttendance->isEmpty())
                            <p class="text-muted mb-0">Belum ada riwayat presensi.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach ($recentAttendance as $item)
                                @php
                                    $badgeColor = match($item->status) {
                                        'Hadir' => 'success', 'Sakit' => 'warning',
                                        'Izin' => 'info', 'Alpa' => 'danger', default => 'secondary',
                                    };
                                @endphp
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}
                                    <span class="badge bg-{{ $badgeColor }}">{{ $item->status }}</span>
                                </li>
                                @endforeach
                            </ul>
                        @endif
                        <a href="{{ route('student.attendance') }}" class="btn btn-sm btn-primary mt-3">Lihat Riwayat Lengkap</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection