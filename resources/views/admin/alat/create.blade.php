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
                <div class="col-5 align-self-center">
                    <div class="float-right">
                        <a href="{{ route('alat.index') }}" class="btn btn-sm btn-secondary font-weight-medium">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid pb-5">
            <div class="card border-0 rounded-lg">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h4 class="card-title m-0 font-weight-bold text-dark">
                            <i class="fas fa-box-open text-primary mr-2"></i> Form Tambah Alat & Inventaris
                        </h4>
                    </div>

                    <form action="{{ route('alat.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Baris 1: Lab & Nama --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Lokasi Laboratorium <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('id_lab') is-invalid @enderror"
                                        name="id_lab" required>
                                        <option value="" disabled selected>Pilih Laboratorium</option>
                                        @foreach ($lab as $l)
                                            <option value="{{ $l->id_lab }}"
                                                {{ old('id_lab') == $l->id_lab ? 'selected' : '' }}>
                                                {{ $l->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_lab')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Nama Alat <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_alat') is-invalid @enderror"
                                        name="nama_alat" value="{{ old('nama_alat') }}" required>
                                    @error('nama_alat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Baris 2: Tahun, Jumlah, Kondisi --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Tahun Pengadaan <span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control @error('tahun_pengadaan') is-invalid @enderror"
                                        name="tahun_pengadaan" min="1900" max="{{ date('Y') + 1 }}" required>
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
                                        name="jumlah" min="1" required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Kondisi Alat <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2" name="kondisi" required>
                                        <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik
                                        </option>
                                        <option value="Rusak Ringan"
                                            {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="Rusak Berat"
                                            {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    </select>
                                    @error('kondisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Baris 3: Spesifikasi & SOP --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Spesifikasi Alat <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('spesifikasi_alat') is-invalid @enderror" name="spesifikasi_alat" rows="3"
                                        required>{{ old('spesifikasi_alat') }}</textarea>
                                    @error('spesifikasi_alat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Instruksi Kerja (SOP) <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('instruksi_kerja') is-invalid @enderror" name="instruksi_kerja" rows="3"
                                        required>{{ old('instruksi_kerja') }}</textarea>
                                    @error('instruksi_kerja')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Baris 4: Drag & Drop Foto --}}
                        <div class="row">
                            <div class="col-md-12 form-group mb-4">
                                <label class="form-label text-dark font-weight-medium">Foto Alat <small
                                        class="text-muted">(Opsional)</small></label>

                                <div class="drag-drop-zone custom-radius" id="dragDropZone">
                                    <div class="dz-message">
                                        <i class="fas fa-image text-primary mb-2 fa-3x"></i>
                                        <h6 class="font-weight-bold text-dark mb-1">Tarik & Lepas Foto di Sini</h6>
                                        <p class="text-muted font-13 mb-3">atau klik untuk menelusuri file</p>
                                        <span class="badge bg-light-secondary text-secondary px-3 py-1 border">Maks: 2MB |
                                            JPG, PNG</span>
                                    </div>
                                    <input type="file" id="foto" name="foto" class="d-none"
                                        accept="image/jpeg,image/png,image/jpg">
                                </div>
                                @error('foto')
                                    <div class="text-danger font-13 mt-2">{{ $message }}</div>
                                @enderror

                                <div id="previewContainer"
                                    class="d-none mt-3 text-center p-4 border custom-radius bg-light">
                                    <img id="imagePreview" src="" alt="Preview Foto"
                                        class="img-fluid rounded mb-3 border border-white"
                                        style="width: 250px; height: 180px; object-fit: cover;">
                                    <div class="d-flex justify-content-center">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3 mr-2"
                                            onclick="document.getElementById('foto').click()">
                                            <i class="fas fa-sync-alt mr-1"></i> Ganti Foto
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill px-3"
                                            id="removeImage">
                                            <i class="fas fa-times mr-1"></i> Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-4 border-top pt-4 text-right">
                            <button type="reset" class="btn btn-sm btn-light mr-2 font-weight-medium">
                                <i class="fa fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary font-weight-medium px-4">
                                <i class="fa fa-save mr-2"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS Khusus Drag & Drop --}}
    <style>
        .custom-radius {
            border-radius: 12px;
        }

        .drag-drop-zone {
            border: 2px dashed #b8c2cc;
            background-color: #f8f9fc;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .drag-drop-zone:hover,
        .drag-drop-zone.dragover {
            background-color: #e3f2fd;
            border-color: #1171ef;
        }

        .drag-drop-zone .dz-message {
            pointer-events: none;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Select2 Init
            $('.select2').select2({
                theme: "bootstrap-5",
                width: '100%'
            });

            // Logika Drag & Drop Foto
            const dropZone = document.getElementById('dragDropZone');
            const fileInput = document.getElementById('foto');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const removeBtn = document.getElementById('removeImage');

            if (dropZone) {
                dropZone.addEventListener('click', () => fileInput.click());
                dropZone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropZone.classList.add('dragover');
                });
                dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
                dropZone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropZone.classList.remove('dragover');
                    if (e.dataTransfer.files.length) {
                        fileInput.files = e.dataTransfer.files;
                        handlePreview();
                    }
                });
            }

            if (fileInput) fileInput.addEventListener('change', handlePreview);

            function handlePreview() {
                if (fileInput.files && fileInput.files[0]) {
                    const file = fileInput.files[0];
                    if (file.size > 2097152) { // 2MB Limit
                        alert('Ukuran file terlalu besar! Maksimal 2MB.');
                        fileInput.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        dropZone.classList.add('d-none');
                        previewContainer.classList.remove('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            }

            if (removeBtn) {
                removeBtn.addEventListener('click', () => {
                    fileInput.value = '';
                    imagePreview.src = '';
                    previewContainer.classList.add('d-none');
                    dropZone.classList.remove('d-none');
                });
            }
        });
    </script>
@endpush
