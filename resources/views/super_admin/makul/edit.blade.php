@extends('layouts.master')
@section('title', 'Edit Mata Kuliah')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Edit Mata Kuliah</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('makul.index') }}" class="text-muted">Mata
                                        Kuliah</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="float-right">
                        <a href="{{ route('makul.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title m-0">Form Edit Mata Kuliah</h4>
                    </div>

                    <form action="{{ route('makul.update', $makul->id_makul) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Kode Mata Kuliah <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                        name="kode" value="{{ old('kode', $makul->kode) }}" required>
                                    @error('kode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Nama Mata Kuliah <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        name="nama" value="{{ old('nama', $makul->nama) }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Program Studi <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('id_prodi') is-invalid @enderror"
                                        name="id_prodi" required>
                                        @foreach ($prodi as $p)
                                            <option value="{{ $p->id_prodi }}"
                                                {{ old('id_prodi', $makul->id_prodi) == $p->id_prodi ? 'selected' : '' }}>
                                                {{ $p->kode }} - {{ $p->nama_prodi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_prodi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Bobot SKS <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('sks') is-invalid @enderror"
                                        name="sks" value="{{ old('sks', $makul->sks) }}" min="1" max="6"
                                        placeholder="Contoh: 3" required>
                                    @error('sks')
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
                width: '100%'
            });
        });
    </script>
@endpush
