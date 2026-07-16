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

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('majors.update', $major->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Jurusan <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $major->name) }}" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $major->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gambar</label>
                    @if ($major->img_url)
                        <div class="mb-2">
                            <img src="{{ asset('uploads/majors/'.$major->img_url) }}" width="120" height="120" style="object-fit: cover;" class="rounded border">
                        </div>
                    @endif
                    <input type="file" name="img_url" class="form-control" accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('majors.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection