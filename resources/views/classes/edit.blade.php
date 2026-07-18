@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Kelas</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">Edit Kelas</h5>

            <div id="formAlert"></div>

            <form id="classEditForm" method="POST" action="{{ route('classes.update', $class->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Kode Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control" value="{{ $class->code }}" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $class->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                    <select name="id_major" class="form-select" required>
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}" {{ $class->id_major == $major->id ? 'selected' : '' }}>
                                {{ $major->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Wali Kelas</label>
                    <select name="id_wali_kelas" class="form-select">
                        <option value="">-- Pilih Wali Kelas (opsional) --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $class->id_wali_kelas == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ $class->description }}</textarea>
                </div>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <span id="submitText">Update</span>
                    <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
                <a href="{{ route('classes.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#classEditForm').on('submit', function (e) {
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
                    window.location.href = "{{ route('classes.index') }}";
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