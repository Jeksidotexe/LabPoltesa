@extends('layouts.master')
@section('title', 'Edit Kategori Praktikum')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Edit Kategori Praktikum</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}"
                                        class="text-muted">Kategori</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title m-0">Form Edit Kategori Praktikum</h4>
                        <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <form action="{{ route('kategori.update', $kategori->id_kategori) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Nama Kategori <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                                        name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                                        required>
                                    @error('nama_kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-actions mt-3 border-top pt-4">
                            <button type="submit" class="btn btn-sm btn-dark">
                                <i class="fa fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
