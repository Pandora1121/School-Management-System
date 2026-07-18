@extends('layouts.app')

@section('title', 'Siswa Kelas ' . $class->name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('teacher.classes') }}">Kelas Saya</a></li>
            <li class="breadcrumb-item active">{{ $class->name }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Siswa Kelas {{ $class->name }} {{ $isWaliKelas ? '(Wali Kelas)' : '(Guru Pengajar)' }}</h4>
        <a href="{{ route('teacher.attendance.create', $class->id) }}" class="btn btn-primary btn-sm">Input Absensi Hari Ini</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if ($students->isEmpty())
                <p class="text-muted mb-0">Belum ada siswa terdaftar di kelas ini.</p>
            @else
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Gender</th>
                            <th>Telepon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->nis }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $student->phone ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection