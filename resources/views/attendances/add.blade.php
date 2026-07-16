@extends('layouts.app')

@section('title', 'Input Absensi')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Absensi Siswa</a></li>
            <li class="breadcrumb-item active">Input</li>
        </ol>
    </nav>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h5 class="mb-4">Pilih Kelas & Tanggal</h5>
            <form method="GET" action="{{ route('attendances.create') }}">
                <div class="row g-2">
                    <div class="col-md-5">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select name="id_class" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ $selectedDate }}" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary w-100">Tampilkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($selectedClass && $students->count() > 0)
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">Daftar Siswa — {{ $students->first()->schoolClass->name ?? '' }} ({{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d M Y') }})</h5>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('attendances.store') }}">
                @csrf
                <input type="hidden" name="id_class" value="{{ $selectedClass }}">
                <input type="hidden" name="date" value="{{ $selectedDate }}">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th style="width: 350px;">Status Kehadiran <span class="text-danger">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->nis }}</td>
                            <td>{{ $student->name }}</td>
                            <td>
                                <select name="status[{{ $student->id }}]" class="form-select" required>
                                    <option value="Hadir">Hadir</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Alpa">Alpa</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
    @elseif ($selectedClass)
        <div class="alert alert-warning">Tidak ada siswa terdaftar di kelas ini.</div>
    @endif
</div>
@endsection