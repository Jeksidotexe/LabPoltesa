@extends('layouts.master')
@section('title', 'Edit Data Alat')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Edit Alat</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('alat.index') }}" class="text-muted">Alat
                                        Lab</a></li>
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
                        <h4 class="card-title m-0">Form Edit Alat & Inventaris</h4>
                        <a href="{{ route('alat.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <form action="{{ route('alat.update', $alat->id_alat) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Lokasi Laboratorium <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('id_lab') is-invalid @enderror"
                                        name="id_lab" required>
                                        @foreach ($lab as $l)
                                            <option value="{{ $l->id_lab }}"
                                                {{ old('id_lab', $alat->id_lab) == $l->id_lab ? 'selected' : '' }}>
                                                {{ $l->nama }}</option>
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
                                        name="nama_alat" value="{{ old('nama_alat', $alat->nama_alat) }}" required>
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
                                        required>{{ old('spesifikasi_alat', $alat->spesifikasi_alat) }}</textarea>
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
                                        required>{{ old('instruksi_kerja', $alat->instruksi_kerja) }}</textarea>
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
                                        name="tahun_pengadaan" value="{{ old('tahun_pengadaan', $alat->tahun_pengadaan) }}"
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
                                        name="jumlah" value="{{ old('jumlah', $alat->jumlah) }}" min="1" required>
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
                                    <small class="text-muted d-block mt-1">Kosongkan jika tidak mengubah foto.</small>

                                    @if ($alat->foto && Storage::disk('public')->exists($alat->foto))
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $alat->foto) }}" alt="Foto Alat"
                                                class="img-thumbnail" width="100">
                                        </div>
                                    @endif
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
