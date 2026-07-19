@extends('layouts.app')

@section('title', 'Anak Saya')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Anak Saya</li>
        </ol>
    </nav>

    <h4 class="mb-4">Data Anak</h4>

    @if ($children->isEmpty())
        <div class="alert alert-warning">Akun Anda belum terhubung ke data anak manapun. Hubungi Admin.</div>
    @else
        <div class="row g-3">
            @foreach ($children as $child)
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $child->name }}</h5>
                        <p class="text-muted mb-3">
                            {{ $child->nis }} — {{ $child->schoolClass->name ?? '-' }} ({{ $child->major->name ?? '-' }})
                        </p>
                        <a href="{{ route('parent.schedule', $child->id) }}" class="btn btn-sm btn-outline-primary mb-1 w-100">Jadwal Pelajaran</a>
                        <a href="{{ route('parent.attendance', $child->id) }}" class="btn btn-sm btn-outline-primary mb-1 w-100">Riwayat Presensi</a>
                        <a href="{{ route('parent.scores', $child->id) }}" class="btn btn-sm btn-outline-primary w-100">Nilai</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection