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

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="{{ old('username') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" id="roleSelect" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Super Admin</option>
                        <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>Admin</option>
                        <option value="3" {{ old('role') == 3 ? 'selected' : '' }}>Guru</option>
                        <option value="4" {{ old('role') == 4 ? 'selected' : '' }}>Siswa</option>
                        <option value="5" {{ old('role') == 5 ? 'selected' : '' }}>Wali Kelas</option>
                    </select>
                </div>

                <div class="mb-3 d-none" id="studentLinkField">
                    <label class="form-label">Hubungkan ke Data Siswa</label>
                    <select name="id_student" class="form-select">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ old('id_student') == $student->id ? 'selected' : '' }}>
                                {{ $student->nis }} - {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hanya menampilkan siswa yang belum punya akun.</small>
                </div>

                <div class="mb-3 d-none" id="teacherLinkField">
                    <label class="form-label">Hubungkan ke Data Guru</label>
                    <select name="id_teacher" class="form-select">
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('id_teacher') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->nip }} - {{ $teacher->name }}
                            </option>
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
                <button type="submit" class="btn btn-primary">Simpan</button>
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
});
</script>
@endpush