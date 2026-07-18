@extends('layouts.app')

@section('title', 'Jadwal Pelajaran Saya')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Jadwal Pelajaran</li>
        </ol>
    </nav>

    <h4 class="mb-4">Jadwal Pelajaran — {{ $student->name }}</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            @if ($routines->isEmpty())
                <p class="text-muted mb-0">Belum ada jadwal pelajaran untuk kelas Anda.</p>
            @else
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($routines as $index => $routine)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $routine->day }}</td>
                            <td>{{ substr($routine->start_time, 0, 5) }} - {{ substr($routine->end_time, 0, 5) }}</td>
                            <td>{{ $routine->subject->name ?? '-' }}</td>
                            <td>{{ $routine->teacher->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection