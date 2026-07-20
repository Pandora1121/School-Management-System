@extends('layouts.app')

@section('title', 'Permintaan Reset Password')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Permintaan Reset Password</li>
        </ol>
    </nav>

    <h4 class="mb-4">Permintaan Reset Password</h4>

    <div id="resultAlert"></div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="loading" class="text-center py-3">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 mb-0">Memuat data...</p>
            </div>
            <table id="resetsTable" class="table table-bordered table-hover w-100 d-none">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Waktu Permintaan</th>
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
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
function loadData() {
    $('#loading').removeClass('d-none');
    $('#resetsTable').addClass('d-none');

    $.ajax({
        url: "{{ route('password-resets.data') }}",
        method: "GET",
        success: function (res) {
            $('#loading').addClass('d-none');
            $('#resetsTable').removeClass('d-none');

            let rows = res.data.map((item, index) => [
                index + 1,
                item.name,
                item.username,
                item.email,
                item.creation_time,
                item.status == 'Selesai'
                    ? '<span class="badge bg-success">Selesai</span>'
                    : '<span class="badge bg-warning">Pending</span>',
                item.status == 'Pending'
                    ? `<button class="btn btn-sm btn-primary btn-process" data-id="${item.id}">Reset Password</button>`
                    : '-'
            ]);

            if ($.fn.DataTable.isDataTable('#resetsTable')) {
                $('#resetsTable').DataTable().destroy();
                $('#resetsTable tbody').empty();
            }

            $('#resetsTable').DataTable({
                                responsive: true,
data: rows,
                columns: [
                    { title: "No" }, { title: "Nama" }, { title: "Username" }, { title: "Email" },
                    { title: "Waktu Permintaan" }, { title: "Status" }, { title: "Aksi" }
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

    $(document).on('click', '.btn-process', function () {
        if (!confirm('Reset password user ini? Password baru akan digenerate otomatis.')) return;

        let id = $(this).data('id');
        let btn = $(this);
        btn.prop('disabled', true).text('Memproses...');

        $.ajax({
            url: `/password-resets/${id}/process`,
            method: "POST",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function (res) {
                $('#resultAlert').html(`
                    <div class="alert alert-success">
                        Password untuk <strong>${res.username}</strong> berhasil direset.<br>
                        Password baru: <code>${res.new_password}</code><br>
                        <small>Catat password ini dan sampaikan ke user secara manual. Password ini tidak akan ditampilkan lagi.</small>
                    </div>
                `);
                loadData();
            },
            error: function (xhr) {
                let msg = xhr.responseJSON?.message ?? 'Gagal memproses permintaan.';
                alert(msg);
                btn.prop('disabled', false).text('Reset Password');
            }
        });
    });
});
</script>
@endpush