@extends('layouts.app')

@section('title', 'Nilai Siswa')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Nilai Siswa</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Nilai Siswa</h4>
        <a href="{{ route('exams.create') }}" class="btn btn-primary btn-sm">+ Input Nilai</a>
    </div>

    <div id="resultAlert"></div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id="loading" class="text-center py-3">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 mb-0">Memuat data...</p>
            </div>
            <table id="examsTable" class="table table-bordered table-hover w-100 d-none">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Jenis</th>
                        <th>Nilai</th>
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    let exams = @json($exams->map(function ($e) {
        return [
            'id' => $e->id,
            'student_name' => $e->student->name ?? '-',
            'class_name' => $e->schoolClass->name ?? '-',
            'subject_name' => $e->subject->name ?? '-',
            'exam_type' => $e->exam_type,
            'score' => number_format($e->score, 2, ',', '.'),
        ];
    }));

    $('#loading').addClass('d-none');
    $('#examsTable').removeClass('d-none');

    let rows = exams.map((item, index) => [
        index + 1,
        item.student_name,
        item.class_name,
        item.subject_name,
        item.exam_type,
        `<div class="text-end">${item.score}</div>`,
        `<button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}">Hapus</button>`
    ]);

    $('#examsTable').DataTable({
        data: rows,
        columns: [
            { title: "No" }, { title: "Nama Siswa" }, { title: "Kelas" },
            { title: "Mata Pelajaran" }, { title: "Jenis" }, { title: "Nilai" }, { title: "Aksi" }
        ]
    });

    $(document).on('click', '.btn-delete', function () {
        if (!confirm('Yakin ingin menghapus nilai ini?')) return;

        let id = $(this).data('id');
        $.ajax({
            url: `/exams/${id}`,
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