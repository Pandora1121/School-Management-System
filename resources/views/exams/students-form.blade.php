@extends('layouts.app')

@section('title', 'Input Nilai — ' . $class->name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">Nilai Siswa</a></li>
            <li class="breadcrumb-item active">Input</li>
        </ol>
    </nav>

    @if ($students->isEmpty())
        <div class="alert alert-warning">Belum ada siswa terdaftar di kelas ini.</div>
    @else
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">{{ $class->name }} — {{ $subject->name }} ({{ $examType }})</h5>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('exams.store') }}">
                @csrf
                <input type="hidden" name="id_class" value="{{ $class->id }}">
                <input type="hidden" name="id_subject" value="{{ $subject->id }}">
                <input type="hidden" name="exam_type" value="{{ $examType }}">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th style="width: 150px;">Nilai (0-100) <span class="text-danger">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->nis }}</td>
                            <td>{{ $student->name }}</td>
                            <td>
                                <input type="number" name="score[{{ $student->id }}]" class="form-control text-end" step="0.01" min="0" max="100" placeholder="0-100" required>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                <a href="{{ route('exams.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection