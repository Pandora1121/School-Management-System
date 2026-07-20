@extends('layouts.app')

@section('title', 'Absensi Siswa')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Absensi Siswa</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Absensi Siswa</h4>
        <a href="{{ route('attendances.create') }}" class="btn btn-primary btn-sm">+ Input Absensi</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">Filter Kelas</label>
                    <select id="filterClass" class="form-select">
                        <option value="">-- Semua Kelas --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter Tanggal</label>
                    <input type="date" id="filterDate" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button id="btnFilter" class="btn btn-secondary">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="loading" class="text-center py-3">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 mb-0">Memuat data...</p>
            </div>
            <table id="attendancesTable" class="table table-bordered table-hover w-100 d-none">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
let table;

function statusBadge(status) {
    const map = {
        'Hadir': 'success',
        'Sakit': 'warning',
        'Izin': 'info',
        'Alpa': 'danger',
    };
    return `<span class="badge bg-${map[status] ?? 'secondary'}">${status}</span>`;
}

function loadData(idClass = '', date = '') {
    $('#loading').removeClass('d-none');
    $('#attendancesTable').addClass('d-none');

    if (table) {
        table.destroy();
        $('#attendancesTable tbody').empty();
    }

    $.ajax({
        url: "{{ route('attendances.data') }}",
        method: "GET",
        data: { id_class: idClass, date: date },
        success: function (res) {
            $('#loading').addClass('d-none');
            $('#attendancesTable').removeClass('d-none');

            let rows = res.data.map((item, index) => [
                index + 1,
                item.date,
                item.student_name,
                item.class_name,
                statusBadge(item.status),
                item.note,
                `@if(auth()->user()->role == 1)
                 <button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}">Hapus</button>
                 @endif`
            ]);

            table = $('#attendancesTable').DataTable({
                                responsive: true,
data: rows,
                columns: [
                    { title: "No" }, { title: "Tanggal" }, { title: "Nama Siswa" }, { title: "Kelas" },
                    { title: "Status" }, { title: "Catatan" }, { title: "Aksi" }
                ]
            });
        },
        error: function () {
            $('#loading').html('<p class="text-danger">Gagal memuat data.</p>');
        }
    });
}

$(document).ready(function () {
    loadData();

    $('#btnFilter').on('click', function () {
        loadData($('#filterClass').val(), $('#filterDate').val());
    });

    $(document).on('click', '.btn-delete', function () {
        if (!confirm('Yakin ingin menghapus data ini?')) return;

        let id = $(this).data('id');
        $.ajax({
            url: `/attendances/${id}`,
            method: "DELETE",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function () {
                loadData($('#filterClass').val(), $('#filterDate').val());
            },
            error: function (xhr) {
                let msg = xhr.responseJSON?.message ?? 'Gagal menghapus data.';
                alert(msg);
            }
        });
    });
});
</script>
@endpush