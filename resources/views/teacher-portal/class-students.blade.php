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
            <table id="classStudentsTable" class="table table-bordered table-hover w-100">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Gender</th>
                        <th>Telepon</th>
                        <th class="text-end">Hadir</th>
                        <th class="text-end">Sakit</th>
                        <th class="text-end">Izin</th>
                        <th class="text-end">Alpa</th>
                        <th class="text-end">Rata-rata Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $index => $student)
                    @php $r = $recap[$student->id]; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->nis }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $student->phone ?? '-' }}</td>
                        <td class="text-end">{{ $r['hadir'] }}</td>
                        <td class="text-end">{{ $r['sakit'] }}</td>
                        <td class="text-end">{{ $r['izin'] }}</td>
                        <td class="text-end">{{ $r['alpa'] }}</td>
                        <td class="text-end">{{ $r['avg_score'] !== null ? number_format($r['avg_score'], 2, ',', '.') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    $('#classStudentsTable').DataTable();
});
</script>
@endpush