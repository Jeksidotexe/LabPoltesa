@extends('layouts.master')
@section('title', 'Tambah Data Alat')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Tambah Alat</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('alat.index') }}" class="text-muted">Alat
                                        Lab</a></li>
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
                        <h4 class="card-title m-0">Form Tambah Alat & Inventaris</h4>
                        <a href="{{ route('alat.index') }}" class="btn btn-sm btn-secondary btn-rounded custom-shadow">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <form action="{{ route('alat.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Lokasi Laboratorium <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('id_lab') is-invalid @enderror"
                                        name="id_lab" required>
                                        <option value="" disabled selected>Pilih Laboratorium</option>
                                        @foreach ($lab as $l)
                                            <option value="{{ $l->id_lab }}"
                                                {{ old('id_lab') == $l->id_lab ? 'selected' : '' }}>{{ $l->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_lab')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Nama Alat <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_alat') is-invalid @enderror"
                                        name="nama_alat" value="{{ old('nama_alat') }}"
                                        placeholder="Contoh: Mikroskop Digital" required>
                                    @error('nama_alat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Spesifikasi Alat <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('spesifikasi_alat') is-invalid @enderror" name="spesifikasi_alat" rows="2"
                                        placeholder="Detail merk, tipe, daya..." required>{{ old('spesifikasi_alat') }}</textarea>
                                    @error('spesifikasi_alat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Instruksi Kerja (SOP) <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('instruksi_kerja') is-invalid @enderror" name="instruksi_kerja" rows="2"
                                        placeholder="Panduan ringkas penggunaan alat..." required>{{ old('instruksi_kerja') }}</textarea>
                                    @error('instruksi_kerja')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Tahun Pengadaan <span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control @error('tahun_pengadaan') is-invalid @enderror"
                                        name="tahun_pengadaan" value="{{ old('tahun_pengadaan', date('Y')) }}"
                                        min="1900" max="{{ date('Y') + 1 }}" required>
                                    @error('tahun_pengadaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Jumlah Unit <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                        name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Foto Alat</label>
                                    <input type="file" class="form-control-file @error('foto') is-invalid @enderror"
                                        name="foto" accept="image/jpeg,image/png,image/jpg">
                                    <small class="text-muted d-block mt-1">Opsional. Maksimal 2MB.</small>
                                    @error('foto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-3 border-top pt-4">
                            <button type="submit" class="btn btn-success btn-rounded custom-shadow"><i
                                    class="fa fa-save"></i> Simpan Data Alat</button>
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
