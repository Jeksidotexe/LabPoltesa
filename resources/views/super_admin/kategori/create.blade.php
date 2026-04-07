@extends('layouts.master')
@section('title', 'Tambah Kategori Praktikum')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Tambah Kategori Praktikum</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}"
                                        class="text-muted">Kategori</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Tambah</li>
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
                        <h4 class="card-title m-0">Form Tambah Kategori Praktikum</h4>
                        <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <form action="{{ route('kategori.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Nama Kategori <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                                        name="nama_kategori" value="{{ old('nama_kategori') }}" required>
                                    @error('nama_kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-actions mt-3 border-top pt-4">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fa fa-save"></i> Simpan Kategori
                            </button>
                            <button type="reset" class="btn btn-sm btn-light">
                                <i class="fa fa-undo"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
