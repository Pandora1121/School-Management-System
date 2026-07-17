@extends('layouts.app')

@section('title', 'Kelas Saya')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Kelas Saya</li>
        </ol>
    </nav>

    <h4 class="mb-4">Kelas yang Diampu — {{ $teacher->name }}</h4>

    @if ($classes->isEmpty())
        <div class="alert alert-warning">Anda belum ditetapkan sebagai wali kelas manapun.</div>
    @else
        <div class="row g-3">
            @foreach ($classes as $class)
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $class->name }}</h5>
                        <p class="text-muted mb-3">{{ $class->code }}</p>
                        <a href="{{ route('teacher.classes.students', $class->id) }}" class="btn btn-primary btn-sm">Lihat Siswa & Absensi</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection