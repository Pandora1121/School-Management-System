@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('class-routines.index') }}">Jadwal Pelajaran</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">Edit Jadwal</h5>

            <div id="formAlert"></div>

            <form id="routineEditForm" method="POST" action="{{ route('class-routines.update', $routine->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select name="id_class" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ $routine->id_class == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="id_subject" class="form-select" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $routine->id_subject == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guru</label>
                        <select name="id_teacher" class="form-select">
                            <option value="">-- Pilih Guru (opsional) --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $routine->id_teacher == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hari <span class="text-danger">*</span></label>
                        <select name="day" class="form-select" required>
                            @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                                <option value="{{ $day }}" {{ $routine->day == $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" class="form-control" value="{{ substr($routine->start_time, 0, 5) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" class="form-control" value="{{ substr($routine->end_time, 0, 5) }}" required>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <span id="submitText">Update</span>
                    <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
                <a href="{{ route('class-routines.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#routineEditForm').on('submit', function (e) {
        e.preventDefault();

        let formData = $(this).serialize();
        $('#submitBtn').prop('disabled', true);
        $('#submitText').addClass('d-none');
        $('#submitSpinner').removeClass('d-none');
        $('#formAlert').html('');
        $('.is-invalid').removeClass('is-invalid');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function (res) {
                $('#formAlert').html(`<div class="alert alert-success">${res.message}</div>`);
                setTimeout(function () {
                    window.location.href = "{{ route('class-routines.index') }}";
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
                        $(`[name="${field}"]`).addClass('is-invalid');
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