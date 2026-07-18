@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">Nilai Siswa</a></li>
            <li class="breadcrumb-item active">Input</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">Pilih Kelas, Mata Pelajaran & Jenis Ujian</h5>

            @if ($assignments->isEmpty())
                <div class="alert alert-warning">Anda belum memiliki jadwal mengajar. Hubungi Admin.</div>
            @else
                <form method="POST" action="{{ route('exams.students-form') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Kelas & Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="assignment" id="assignmentSelect" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($assignments as $item)
                                    <option value="{{ $item->id_class }}|{{ $item->id_subject }}">
                                        {{ $item->schoolClass->name ?? '-' }} — {{ $item->subject->name ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="id_class" id="idClassInput">
                            <input type="hidden" name="id_subject" id="idSubjectInput">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Jenis Ujian <span class="text-danger">*</span></label>
                            <select name="exam_type" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Tugas">Tugas</option>
                                <option value="Kuis">Kuis</option>
                                <option value="UTS">UTS</option>
                                <option value="UAS">UAS</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Lanjut</button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#assignmentSelect').on('change', function () {
        let val = $(this).val();
        if (val) {
            let parts = val.split('|');
            $('#idClassInput').val(parts[0]);
            $('#idSubjectInput').val(parts[1]);
        }
    });
});
</script>
@endpush