@extends('layouts.app')

@section('title', 'Data Jurusan')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Jurusan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Jurusan</h4>
        <a href="{{ route('majors.create') }}" class="btn btn-primary btn-sm">+ Tambah Jurusan</a>
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
            <table id="majorsTable" class="table table-bordered table-hover w-100 d-none">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Jurusan</th>
                        <th>Deskripsi</th>
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
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    $.ajax({
        url: "{{ route('majors.data') }}",
        method: "GET",
        success: function (res) {
            $('#loading').addClass('d-none');
            $('#majorsTable').removeClass('d-none');

            let rows = res.data.map((item, index) => [
                index + 1,
                item.name,
                item.description ?? '-',
                `<a href="/majors/${item.id}/edit" class="btn btn-sm btn-warning">Edit</a>
                    @if(auth()->user()->role == 1)
                    <button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}">Hapus</button>
                    @endif`
                 
            ]);

            $('#majorsTable').DataTable({
                                responsive: true,
data: rows,
                columns: [
                    { title: "No" }, { title: "Nama Jurusan" },
                    { title: "Deskripsi" }, { title: "Aksi" }
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
            url: `/majors/${id}`,
            method: "DELETE",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function () {
                location.reload();
            },
            error: function () {
                alert('Gagal menghapus data.');
            }
        });
    });
});
</script>
@endpush