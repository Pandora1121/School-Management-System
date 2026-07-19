@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            <h4 class="mb-4 text-center">Lupa Password</h4>
            <p class="text-muted text-center small mb-4">Masukkan username atau email Anda. Admin akan memproses permintaan reset password Anda.</p>

            <div id="formAlert"></div>

            <form id="forgotForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Username atau Email <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username atau email" autofocus required>
                </div>
                <button type="submit" id="submitBtn" class="btn btn-primary w-100">
                    <span id="submitText">Kirim Permintaan</span>
                    <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </form>

            <p class="text-center mt-3 mb-0">
                <a href="{{ route('login') }}">Kembali ke Login</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#forgotForm').on('submit', function (e) {
        e.preventDefault();

        let formData = $(this).serialize();
        $('#submitBtn').prop('disabled', true);
        $('#submitText').addClass('d-none');
        $('#submitSpinner').removeClass('d-none');
        $('#formAlert').html('');

        $.ajax({
            url: "{{ route('password.email') }}",
            method: 'POST',
            data: formData,
            success: function (res) {
                $('#formAlert').html(`<div class="alert alert-success">${res.message}</div>`);
                $('#forgotForm')[0].reset();
                $('#submitBtn').prop('disabled', false);
                $('#submitText').removeClass('d-none');
                $('#submitSpinner').addClass('d-none');
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