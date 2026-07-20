@extends('layouts.app')

@section('title', 'Tambah Jurusan')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('majors.index') }}">Jurusan</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">Tambah Jurusan</h5>

            <div id="formAlert"></div>

            <form id="majorForm" method="POST" action="{{ route('majors.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Jurusan <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Rekayasa Perangkat Lunak" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" placeholder="Deskripsi singkat jurusan" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gambar</label>
                    <input type="file" name="img_url" class="form-control" accept="image/*">
                    <small class="text-muted">Format JPG/PNG, maksimal 2MB.</small>
                </div>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <span id="submitText">Simpan</span>
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
    $('#majorForm').on('submit', function (e) {
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