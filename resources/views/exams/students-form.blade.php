@extends('layouts.app')

@section('title', 'Input Nilai — ' . $class->name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">Nilai Siswa</a></li>
            <li class="breadcrumb-item active">Input</li>
        </ol>
    </nav>

    @if ($students->isEmpty())
        <div class="alert alert-warning">Belum ada siswa terdaftar di kelas ini.</div>
    @else
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">{{ $class->name }} — {{ $subject->name }} ({{ $examType }})</h5>

            <div id="formAlert"></div>

            <form id="examForm" method="POST" action="{{ route('exams.store') }}">
                @csrf
                <input type="hidden" name="id_class" value="{{ $class->id }}">
                <input type="hidden" name="id_subject" value="{{ $subject->id }}">
                <input type="hidden" name="exam_type" value="{{ $examType }}">

                <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th style="width: 150px;">Nilai (0-100) <span class="text-danger">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->nis }}</td>
                            <td>{{ $student->name }}</td>
                            <td>
                                <input type="number" name="score[{{ $student->id }}]" class="form-control text-end" step="0.01" min="0" max="100" placeholder="0-100" required>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <span id="submitText">Simpan Nilai</span>
                    <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
                <a href="{{ route('exams.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#examForm').on('submit', function (e) {
        e.preventDefault();

        let formData = $(this).serialize();
        $('#submitBtn').prop('disabled', true);
        $('#submitText').addClass('d-none');
        $('#submitSpinner').removeClass('d-none');
        $('#formAlert').html('');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function (res) {
                $('#formAlert').html(`<div class="alert alert-success">${res.message}</div>`);
                setTimeout(function () {
                    window.location.href = "{{ route('exams.index') }}";
                }, 800);
            },
            error: function (xhr) {
                $('#submitBtn').prop('disabled', false);
                $('#submitText').removeClass('d-none');
                $('#submitSpinner').addClass('d-none');

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
                    $.each(errors, function (field, messages) {
                        errorHtml += `<li>${messages[0]}</li>`;
                    });
                    errorHtml += '</ul></div>';
                    $('#formAlert').html(errorHtml);
                } else {
                    $('#formAlert').html('<div class="alert alert-danger">Terjadi kesalahan. Coba lagi.</div>');
                }
            }
        });
    });
});
</script>
@endpush