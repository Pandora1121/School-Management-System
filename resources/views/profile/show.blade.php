@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Profil Saya</li>
        </ol>
    </nav>

    <h4 class="mb-4">Profil Saya</h4>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4">Edit Data Diri</h5>

                    <div id="profileAlert"></div>

                    <form id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <img id="profilePreview" src="{{ $user->img_url ? asset('uploads/profiles/'.$user->img_url) : '' }}" width="100" height="100" style="object-fit: cover; {{ !$user->img_url ? 'display:none;' : '' }}" class="rounded-circle border">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" name="img_url" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" autofocus required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                            <small class="text-muted">Username tidak dapat diubah.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" class="form-control" placeholder="Contoh: 081234567890" value="{{ $user->phone }}">
                        </div>

                        <button type="submit" id="profileSubmitBtn" class="btn btn-primary">
                            <span id="profileSubmitText">Simpan Perubahan</span>
                            <span id="profileSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4">Ganti Password</h5>

                    <div id="passwordAlert"></div>

                    <form id="passwordForm" method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control" placeholder="Masukkan password saat ini" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                        </div>

                        <button type="submit" id="passwordSubmitBtn" class="btn btn-primary">
                            <span id="passwordSubmitText">Ganti Password</span>
                            <span id="passwordSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function handleAjaxForm(formId, alertId, btnId, textId, spinnerId, onSuccess) {
    $(formId).on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        $(btnId).prop('disabled', true);
        $(textId).addClass('d-none');
        $(spinnerId).removeClass('d-none');
        $(alertId).html('');
        $('.is-invalid').removeClass('is-invalid');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                $(alertId).html(`<div class="alert alert-success">${res.message}</div>`);
                $(btnId).prop('disabled', false);
                $(textId).removeClass('d-none');
                $(spinnerId).addClass('d-none');
                if (onSuccess) onSuccess(res);
            },
            error: function (xhr) {
                $(btnId).prop('disabled', false);
                $(textId).removeClass('d-none');
                $(spinnerId).addClass('d-none');

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
                    $.each(errors, function (field, messages) {
                        errorHtml += `<li>${messages[0]}</li>`;
                    });
                    errorHtml += '</ul></div>';
                    $(alertId).html(errorHtml);
                } else {
                    $(alertId).html('<div class="alert alert-danger">Terjadi kesalahan. Coba lagi.</div>');
                }
            }
        });
    });
}

$(document).ready(function () {
    handleAjaxForm('#profileForm', '#profileAlert', '#profileSubmitBtn', '#profileSubmitText', '#profileSubmitSpinner', function (res) {
        if (res.img_url) {
            $('#profilePreview').attr('src', '/uploads/profiles/' + res.img_url).show();
        }
    });

    handleAjaxForm('#passwordForm', '#passwordAlert', '#passwordSubmitBtn', '#passwordSubmitText', '#passwordSubmitSpinner', function () {
        $('#passwordForm')[0].reset();
    });
});
</script>
@endpush