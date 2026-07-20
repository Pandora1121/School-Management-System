@extends('layouts.app')

@section('title', 'Nilai Saya')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Nilai Saya</li>
        </ol>
    </nav>

    <h4 class="mb-4">Nilai — {{ $student->name }}</h4>

    @if ($grouped->isEmpty())
        <div class="alert alert-warning">Belum ada nilai yang tercatat.</div>
    @else
        @foreach ($grouped as $subjectName => $exams)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="mb-3">{{ $subjectName }}</h5>
                <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Jenis Ujian</th>
                            <th class="text-end">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exams as $index => $exam)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $exam->exam_type }}</td>
                            <td class="text-end">{{ number_format($exam->score, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection