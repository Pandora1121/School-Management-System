@extends('layouts.app')

@section('title', 'Input Absensi — ' . $class->name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('teacher.classes') }}">Kelas Saya</a></li>
            <li class="breadcrumb-item"><a href="{{ route('teacher.classes.students', $class->id) }}">{{ $class->name }}</a></li>
            <li class="breadcrumb-item active">Input Absensi</li>
        </ol>
    </nav>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('teacher.attendance.create', $class->id) }}" class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ $selectedDate }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary w-100">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    @if ($students->isEmpty())
        <div class="alert alert-warning">Belum ada siswa terdaftar di kelas ini.</div>
    @else
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">Absensi {{ $class->name }} — {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d M Y') }}</h5>

            <div id="formAlert"></div>

            <form id="teacherAttendanceForm" method="POST" action="{{ route('teacher.attendance.store', $class->id) }}">
                @csrf
                <input type="hidden" name="date" value="{{ $selectedDate }}">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th style="width: 250px;">Status <span class="text-danger">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->nis }}</td>
                            <td>{{ $student->name }}</td>
                            <td>
                                <select name="status[{{ $student->id }}]" class="form-select" required>
                                    <option value="Hadir">Hadir</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Alpa">Alpa</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <span id="submitText">Simpan Absensi</span>
                    <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
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
    $('#teacherAttendanceForm').on('submit', function (e) {
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
                    window.location.href = res.redirect;
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