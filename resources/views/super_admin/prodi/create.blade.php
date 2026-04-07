@extends('layouts.master')
@section('title', 'Tambah Program Studi')
@section('content')
    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Tambah Prodi</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('prodi.index') }}" class="text-muted">Program
                                        Studi</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Tambah</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="float-right">
                        <a href="{{ route('prodi.index') }}" class="btn btn-sm btn-secondary">
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
                        <h4 class="card-title m-0">Form Tambah Program Studi</h4>
                    </div>
                    <form action="{{ route('prodi.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Kode Prodi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                        name="kode" value="{{ old('kode') }}" required>
                                    @error('kode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Nama Program Studi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_prodi') is-invalid @enderror"
                                        name="nama_prodi" value="{{ old('nama_prodi') }}" required>
                                    @error('nama_prodi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Status Akreditasi <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('akreditasi') is-invalid @enderror"
                                        name="akreditasi" required>
                                        <option value="" selected disabled>Pilih Akreditasi</option>
                                        <optgroup label="Standar Baru">
                                            <option value="Unggul" {{ old('akreditasi') == 'Unggul' ? 'selected' : '' }}>
                                                Unggul</option>
                                            <option value="Baik Sekali"
                                                {{ old('akreditasi') == 'Baik Sekali' ? 'selected' : '' }}>Baik Sekali
                                            </option>
                                            <option value="Baik" {{ old('akreditasi') == 'Baik' ? 'selected' : '' }}>Baik
                                            </option>
                                        </optgroup>
                                        <optgroup label="Standar Lama">
                                            <option value="A" {{ old('akreditasi') == 'A' ? 'selected' : '' }}>A
                                            </option>
                                            <option value="B" {{ old('akreditasi') == 'B' ? 'selected' : '' }}>B
                                            </option>
                                            <option value="C" {{ old('akreditasi') == 'C' ? 'selected' : '' }}>C
                                            </option>
                                        </optgroup>
                                        <option value="Belum Ada" {{ old('akreditasi') == 'Belum Ada' ? 'selected' : '' }}>
                                            Belum Ada / Proses</option>
                                    </select>
                                    @error('akreditasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Tanggal Berdiri <span
                                            class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text"
                                            class="form-control datepicker bg-white @error('tanggal_berdiri') is-invalid @enderror"
                                            name="tanggal_berdiri" value="{{ old('tanggal_berdiri') }}"
                                            placeholder="Pilih Tanggal" required>
                                        <i class="far fa-calendar-alt position-absolute text-muted"
                                            style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                                    </div>
                                    @error('tanggal_berdiri')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-actions mt-3 border-top pt-4">
                            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Simpan</button>
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
    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
                width: '100%'
            });

            // Datepicker Flatpickr Init
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true
            });
        });
    </script>
@endpush
