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

    <p class="text-muted">Selamat datang, <strong>{{ auth()->user()->name }}</strong>
        ({{ auth()->user()->role == 1 ? 'Super Admin' : 'Admin' }})</p>

    <div class="row g-3 mt-2 mb-4">
        <div class="col-md-{{ auth()->user()->role == 1 ? '3' : '4' }}">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Siswa</p>
                    <h3 class="mb-0">{{ number_format($totalStudents, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-{{ auth()->user()->role == 1 ? '3' : '4' }}">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Kelas</p>
                    <h3 class="mb-0">{{ number_format($totalClasses, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-{{ auth()->user()->role == 1 ? '3' : '4' }}">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Jurusan</p>
                    <h3 class="mb-0">{{ number_format($totalMajors, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        @if (auth()->user()->role == 1)
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total User</p>
                    <h3 class="mb-0">{{ number_format($totalUsers, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="row g-3">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="mb-3">Tren Kehadiran 7 Hari Terakhir</h6>
                    <canvas id="attendanceChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="mb-3">Distribusi Gender Siswa</h6>
                    <canvas id="genderChart" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="mb-3">Jumlah Siswa per Jurusan</h6>
                    <canvas id="majorChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Tren Kehadiran (Line Chart)
    const attendanceData = @json($attendanceTrend);
    new Chart(document.getElementById('attendanceChart'), {
        type: 'line',
        data: {
            labels: attendanceData.map(d => d.date),
            datasets: [{
                label: 'Jumlah Hadir',
                data: attendanceData.map(d => d.count),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.1)',
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Distribusi Gender (Doughnut Chart)
    const genderData = @json($genderCounts);
    new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [genderData.L, genderData.P],
                backgroundColor: ['#0d6efd', '#fd7e14'],
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Siswa per Jurusan (Bar Chart)
    const majorData = @json($studentsPerMajor->map(fn($m) => ['name' => $m->name, 'count' => $m->students_count]));
    new Chart(document.getElementById('majorChart'), {
        type: 'bar',
        data: {
            labels: majorData.map(d => d.name),
            datasets: [{
                label: 'Jumlah Siswa',
                data: majorData.map(d => d.count),
                backgroundColor: '#0d6efd',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>
@endpush