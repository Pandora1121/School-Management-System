@extends('layouts.app')

@section('title', 'Riwayat Presensi Saya')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Riwayat Presensi</li>
        </ol>
    </nav>

    <h4 class="mb-4">Riwayat Presensi — {{ $student->name }}</h4>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Hadir</p>
                    <h4 class="mb-0 text-success">{{ $summary['Hadir'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Sakit</p>
                    <h4 class="mb-0 text-warning">{{ $summary['Sakit'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Izin</p>
                    <h4 class="mb-0 text-info">{{ $summary['Izin'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Alpa</p>
                    <h4 class="mb-0 text-danger">{{ $summary['Alpa'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if ($attendances->isEmpty())
                <p class="text-muted mb-0">Belum ada riwayat presensi.</p>
            @else
            <table id="attendanceHistoryTable" class="table table-bordered table-hover w-100">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}</td>
                            <td>
                                @php
                                    $badgeColor = match($item->status) {
                                        'Hadir' => 'success',
                                        'Sakit' => 'warning',
                                        'Izin' => 'info',
                                        'Alpa' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeColor }}">{{ $item->status }}</span>
                            </td>
                            <td>{{ $item->note ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    if ($('#attendanceHistoryTable').length) {
        $('#attendanceHistoryTable').DataTable({ responsive: true });
    }
});
</script>
@endpush
@endsection