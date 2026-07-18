@extends('layouts.app')

@section('title', 'Edit Jurusan')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('majors.index') }}">Jurusan</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">Edit Jurusan</h5>

            <div id="formAlert"></div>

            <form id="majorEditForm" method="POST" action="{{ route('majors.update', $major->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Jurusan <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $major->name }}" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ $major->description }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gambar</label>
                    @if ($major->img_url)
                        <div class="mb-2">
                            <img id="imgPreview" src="{{ asset('uploads/majors/'.$major->img_url) }}" width="120" height="120" style="object-fit: cover;" class="rounded border">
                        </div>
                    @else
                        <div class="mb-2">
                            <img id="imgPreview" src="" width="120" height="120" style="object-fit: cover; display:none;" class="rounded border">
                        </div>
                    @endif
                    <input type="file" name="img_url" class="form-control" accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                </div>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <span id="submitText">Update</span>
                    <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
                <a href="{{ route('majors.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#majorEditForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        $('#submitBtn').prop('disabled', true);
        $('#submitText').addClass('d-none');
        $('#submitSpinner').removeClass('d-none');
        $('#formAlert').html('');
        $('.is-invalid').removeClass('is-invalid');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                $('#formAlert').html(`<div class="alert alert-success">${res.message}</div>`);
                setTimeout(function () {
                    window.location.href = "{{ route('majors.index') }}";
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