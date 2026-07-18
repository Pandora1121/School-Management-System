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

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if ($exams->isEmpty())
                <p class="text-muted mb-0">Belum ada nilai yang diinput.</p>
            @else
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Jenis</th>
                            <th class="text-end">Nilai</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exams as $index => $exam)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $exam->student->name ?? '-' }}</td>
                            <td>{{ $exam->schoolClass->name ?? '-' }}</td>
                            <td>{{ $exam->subject->name ?? '-' }}</td>
                            <td>{{ $exam->exam_type }}</td>
                            <td class="text-end">{{ number_format($exam->score, 2, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $exam->id }}">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
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