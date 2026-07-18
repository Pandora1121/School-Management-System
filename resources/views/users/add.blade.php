@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User List</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">Tambah User</h5>

            <div id="formAlert"></div>

            <form id="userForm" method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" id="roleSelect" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="1">Super Admin</option>
                        <option value="2">Admin</option>
                        <option value="3">Guru</option>
                        <option value="4">Siswa</option>
                        <option value="5">Wali Kelas</option>
                    </select>
                </div>

                <div class="mb-3 d-none" id="studentLinkField">
                    <label class="form-label">Hubungkan ke Data Siswa</label>
                    <select name="id_student" class="form-select">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">{{ $student->nis }} - {{ $student->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hanya menampilkan siswa yang belum punya akun.</small>
                </div>

                <div class="mb-3 d-none" id="teacherLinkField">
                    <label class="form-label">Hubungkan ke Data Guru</label>
                    <select name="id_teacher" class="form-select">
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->nip }} - {{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hanya menampilkan guru yang belum punya akun.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                </div>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <span id="submitText">Simpan</span>
                    <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    function toggleFields() {
        let role = $('#roleSelect').val();
        $('#studentLinkField').addClass('d-none');
        $('#teacherLinkField').addClass('d-none');

        if (role == '4') {
            $('#studentLinkField').removeClass('d-none');
        } else if (role == '3' || role == '5') {
            $('#teacherLinkField').removeClass('d-none');
        }
    }

    toggleFields();
    $('#roleSelect').on('change', toggleFields);

    $('#userForm').on('submit', function (e) {
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
                    window.location.href = "{{ route('users.index') }}";
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