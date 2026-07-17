@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Guru</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Guru</h4>
        <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-sm">+ Tambah Guru</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="loading" class="text-center py-3">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 mb-0">Memuat data...</p>
            </div>
            <table id="teachersTable" class="table table-bordered table-hover w-100 d-none">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Gender</th>
                        <th>Tgl Lahir</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th style="width: 150px;">Aksi</th>
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    $.ajax({
        url: "{{ route('teachers.data') }}",
        method: "GET",
        success: function (res) {
            $('#loading').addClass('d-none');
            $('#teachersTable').removeClass('d-none');

                    let rows = res.data.map((item, index) => [
            index + 1,
            item.nip,
            item.name,
            item.major_name,
            item.gender,
            item.birth_date,
            item.phone,
            item.status == 'Aktif'
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-secondary">Non-Aktif</span>',
            `<a href="/teachers/${item.id}/edit" class="btn btn-sm btn-warning">Edit</a>
            @if(auth()->user()->role == 1)
            <button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}">Hapus</button>
            @endif`
        ]);

                    $('#teachersTable').DataTable({
            data: rows,
            columns: [
                { title: "No" }, { title: "NIP" }, { title: "Nama" }, { title: "Jurusan" },
                { title: "Gender" }, { title: "Tgl Lahir" }, { title: "Telepon" }, { title: "Status" }, { title: "Aksi" }
            ]
        });
        },
        error: function () {
            $('#loading').html('<p class="text-danger">Gagal memuat data.</p>');
        }
    });

    $(document).on('click', '.btn-delete', function () {
        if (!confirm('Yakin ingin menghapus data ini?')) return;

        let id = $(this).data('id');
        $.ajax({
            url: `/teachers/${id}`,
            method: "DELETE",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function () {
                location.reload();
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