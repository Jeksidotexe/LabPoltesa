@extends('layouts.master')
@section('title', 'Tambah Laboratorium')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-7">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Tambah Laboratorium</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0 bg-transparent font-14">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('lab.index') }}">Laboratorium</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Tambah</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 text-right">
                    <a href="{{ route('lab.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fa fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <h4 class="card-title m-0 text-dark font-weight-bold">Form Tambah Laboratorium</h4>
                        <p class="text-muted font-14">Harap isi formulir di bawah ini dengan lengkap dan benar.</p>
                    </div>

                    <form action="{{ route('lab.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            {{-- INFORMASI UMUM --}}
                            <div class="col-lg-8 pr-lg-5 border-right-lg mb-4 mb-lg-0">
                                <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-4">
                                    <i class="fas fa-info-circle mr-2"></i> Informasi Umum
                                </h5>

                                <div class="row">
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Nama Laboratorium <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control custom-input @error('nama') is-invalid @enderror"
                                            name="nama" value="{{ old('nama') }}" required>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Kode Laboratorium <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control custom-input @error('kode') is-invalid @enderror"
                                            name="kode" value="{{ old('kode') }}" required>
                                        @error('kode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Lokasi Ruangan <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control custom-input @error('lokasi') is-invalid @enderror"
                                        name="lokasi" value="{{ old('lokasi') }}" required>
                                    @error('lokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Kapasitas (Orang) <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control custom-input @error('kapasitas') is-invalid @enderror"
                                            name="kapasitas" value="{{ old('kapasitas') }}" min="1" required>
                                        @error('kapasitas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Status Penggunaan <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-control custom-input select2-no-search @error('status') is-invalid @enderror"
                                            name="status" required>
                                            <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>
                                                Nonaktif</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="form-label text-dark font-weight-medium">Deskripsi Laboratorium <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control custom-input @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="5"
                                        required>{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- MEDIA & AKSI --}}
                            <div class="col-lg-4 pl-lg-4 d-flex flex-column">
                                <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-4">
                                    <i class="fas fa-image mr-2"></i> Media & Aksi
                                </h5>

                                <div class="form-group mb-4">
                                    <label class="form-label text-dark font-weight-medium">Foto Laboratorium <span
                                            class="text-danger">*</span></label>

                                    <div class="drag-drop-zone custom-radius" id="dragDropZone">
                                        <div class="dz-message">
                                            <i class="fas fa-cloud-upload-alt text-primary mb-2 fa-3x"></i>
                                            <h6 class="font-weight-bold text-dark mb-1 mt-2">Unggah Foto Ruangan</h6>
                                            <p class="text-muted font-12 mb-3">Tarik & Lepas atau Klik di sini</p>
                                            <span class="badge bg-light-secondary text-secondary px-2 py-1 border">Maks:
                                                2MB | JPG, PNG</span>
                                        </div>
                                        <input type="file" id="foto" name="foto" class="d-none"
                                            accept="image/jpeg,image/png,image/jpg" required>
                                    </div>
                                    @error('foto')
                                        <div class="text-danger font-13 mt-2">{{ $message }}</div>
                                    @enderror

                                    <div id="previewContainer" class="d-none mt-2 text-center p-3 border bg-light">
                                        <img id="imagePreview" src="" alt="Preview Foto Lab"
                                            class="rounded shadow-sm mb-3 d-block mx-auto"
                                            style="width: 100%; height: 200px; object-fit: cover;">
                                        <div class="d-flex justify-content-center">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 mr-2"
                                                onclick="document.getElementById('foto').click()">
                                                <i class="fas fa-sync-alt mr-1"></i> Ganti
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger rounded-pill px-3"
                                                id="removeImage">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="mt-auto">
                                    <hr class="my-4 border-light">
                                    <button type="submit" class="btn btn-sm btn-success btn-block font-weight-medium py-2 shadow-sm mb-2">
                                        <i class="fa fa-save mr-2"></i> Simpan
                                    </button>
                                    <button type="reset" class="btn btn-sm btn-light btn-block font-weight-medium py-2 shadow-sm">
                                        <i class="fa fa-undo mr-2"></i> Reset
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-radius {
            border-radius: 12px !important;
        }

        @media (min-width: 992px) {
            .border-right-lg {
                border-right: 1px solid #e9ecef;
            }
        }

        /* Drag & Drop Zone Styles */
        .drag-drop-zone {
            border: 2px dashed #b8c2cc;
            background-color: #fcfcfc;
            padding: 30px 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 240px;
        }

        .drag-drop-zone:hover,
        .drag-drop-zone.dragover {
            background-color: #f0f5ff;
            border-color: #5f76e8;
        }

        .drag-drop-zone .dz-message {
            pointer-events: none;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2-no-search').select2({
                theme: "bootstrap-5",
                width: '100%',
                minimumResultsForSearch: Infinity
            });

            // ==========================================
            // LOGIKA DRAG & DROP FOTO
            // ==========================================
            const dropZone = document.getElementById('dragDropZone');
            const fileInput = document.getElementById('foto');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const removeBtn = document.getElementById('removeImage');

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

            fileInput.addEventListener('change', handlePreview);

            function handlePreview() {
                if (fileInput.files && fileInput.files[0]) {
                    const file = fileInput.files[0];
                    if (file.size > 2097152) {
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

            removeBtn.addEventListener('click', () => {
                fileInput.value = '';
                imagePreview.src = '';
                previewContainer.classList.add('d-none');
                dropZone.classList.remove('d-none');
            });
        });
    </script>
@endpush
