@extends('layouts.master')
@section('title', 'Edit Data Dosen')
@section('content')

    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-7">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Edit Data Dosen</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('dosen.index') }}"
                                        class="text-muted">Dosen</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="float-right">
                        <a href="{{ route('dosen.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4 p-md-5">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="card-title text-dark font-weight-bold mb-1">Form Edit Dosen</h4>
                                    <p class="text-muted font-14 mb-0">Lakukan perubahan data dosen pada formulir di bawah
                                        ini.</p>
                                </div>
                            </div>

                            <div
                                class="alert alert-warning py-3 px-4 d-flex align-items-center mb-5">
                                <i class="fas fa-exclamation-triangle mr-3 fa-2x text-warning"></i>
                                <span class="text-dark"><strong>Peringatan:</strong> Jika Anda mengubah <b>NIP</b>, maka
                                    Username login dosen tersebut akan otomatis ikut tersinkronisasi/berubah.</span>
                            </div>

                            <form action="{{ route('dosen.update', $dosen->id_dosen) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- INFORMASI PRIBADI --}}
                                <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-4"><i
                                        class="fas fa-user-circle mr-2"></i>Informasi Pribadi</h5>
                                <div class="row">
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Nomor Induk Pegawai (NIP)
                                            <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('nip') is-invalid @enderror"
                                            name="nip" value="{{ old('nip', $dosen->nip) }}" required>
                                        @error('nip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            name="nama" value="{{ old('nama', $dosen->nama) }}" required>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Gelar Depan &
                                            Belakang</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="gelar_depan"
                                                value="{{ old('gelar_depan', $dosen->gelar_depan) }}" placeholder="Depan">
                                            <input type="text" class="form-control" style="border-left: 0;"
                                                name="gelar_belakang"
                                                value="{{ old('gelar_belakang', $dosen->gelar_belakang) }}"
                                                placeholder="Belakang">
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email', $dosen->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Tanggal Lahir <span
                                                class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="text"
                                                class="form-control datepicker @error('tanggal_lahir') is-invalid @enderror"
                                                name="tanggal_lahir"
                                                value="{{ old('tanggal_lahir', $dosen->tanggal_lahir) }}" required>
                                            <i class="far fa-calendar-alt position-absolute text-muted"
                                                style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Jenis Kelamin <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-control select2-no-search @error('jenis_kelamin') is-invalid @enderror"
                                            name="jenis_kelamin" required>
                                            <option value="L"
                                                {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                                Laki-Laki</option>
                                            <option value="P"
                                                {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">No. Telepon<span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('telepon') is-invalid @enderror"
                                            name="telepon" value="{{ old('telepon', $dosen->telepon) }}" required>
                                    </div>
                                </div>

                                {{-- SEGMEN 2: INFORMASI AKADEMIK & FOTO --}}
                                <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-4 mt-3"><i
                                        class="fas fa-briefcase mr-2"></i>Informasi Akademik & Profil</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12 form-group mb-4">
                                                <label class="form-label text-dark font-weight-medium">Program Studi <span
                                                        class="text-danger">*</span></label>
                                                <select
                                                    class="form-control select2 @error('id_prodi') is-invalid @enderror"
                                                    id="id_prodi" name="id_prodi" required>
                                                    <option value="" disabled selected>Pilih Program Studi</option>
                                                    @foreach ($prodi as $p)
                                                        <option value="{{ $p->id_prodi }}"
                                                            {{ old('id_prodi', $dosen->id_prodi) == $p->id_prodi ? 'selected' : '' }}>
                                                            {{ $p->kode }} - {{ $p->nama_prodi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_prodi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 form-group mb-4">
                                                <label class="form-label text-dark font-weight-medium">Jabatan Akademik
                                                    <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('jabatan') is-invalid @enderror"
                                                    name="jabatan" value="{{ old('jabatan', $dosen->jabatan) }}"
                                                    required>
                                            </div>
                                            <div class="col-md-6 form-group mb-4">
                                                <label class="form-label text-dark font-weight-medium">Tanggal Bergabung
                                                    <span class="text-danger">*</span></label>
                                                <div class="position-relative">
                                                    <input type="text"
                                                        class="form-control datepicker @error('tanggal_bergabung') is-invalid @enderror"
                                                        name="tanggal_bergabung"
                                                        value="{{ old('tanggal_bergabung', $dosen->tanggal_bergabung) }}"
                                                        required>
                                                    <i class="far fa-calendar-alt position-absolute text-muted"
                                                        style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6 form-group mb-4">
                                                <label class="form-label text-dark font-weight-medium">Status Dosen <span
                                                        class="text-danger">*</span></label>
                                                <select
                                                    class="form-control select2-no-search @error('status') is-invalid @enderror"
                                                    name="status" required>
                                                    <option value="Aktif"
                                                        {{ old('status', $dosen->status) == 'Aktif' ? 'selected' : '' }}>
                                                        Aktif</option>
                                                    <option value="Nonaktif"
                                                        {{ old('status', $dosen->status) == 'Nonaktif' ? 'selected' : '' }}>
                                                        Nonaktif</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- AREA DRAG & DROP FOTO (KONDISI EDIT) --}}
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Foto Profil</label>
                                        <small class="text-muted d-block mb-2">Biarkan jika tidak ingin mengubah foto saat
                                            ini.</small>

                                        {{-- Cek apakah user sudah punya foto --}}
                                        @php $hasFoto = $dosen->foto && Storage::disk('public')->exists($dosen->foto); @endphp

                                        {{-- Drag Zone (Sembunyi jika punya foto) --}}
                                        <div class="drag-drop-zone custom-radius {{ $hasFoto ? 'd-none' : '' }}"
                                            id="dragDropZone">
                                            <div class="dz-message">
                                                <i class="fas fa-cloud-upload-alt text-primary mb-2 fa-3x"></i>
                                                <h6 class="font-weight-bold text-dark mb-1">Tarik & Lepas Foto di Sini</h6>
                                                <p class="text-muted font-13 mb-3">atau klik untuk menelusuri file</p>
                                                <span
                                                    class="badge bg-light-secondary text-secondary px-3 py-1 border">Maks:
                                                    2MB | JPG, PNG</span>
                                            </div>
                                            <input type="file" id="foto" name="foto" class="d-none"
                                                accept="image/jpeg,image/png,image/jpg">
                                        </div>
                                        @error('foto')
                                            <div class="text-danger font-13 mt-2">{{ $message }}</div>
                                        @enderror

                                        {{-- Preview Zone (Tampil jika punya foto) --}}
                                        <div id="previewContainer"
                                            class="mt-2 text-center p-3 border custom-radius bg-light {{ $hasFoto ? '' : 'd-none' }}">
                                            <img id="imagePreview" src="{{ $hasFoto ? Storage::url($dosen->foto) : '' }}"
                                                alt="Preview Foto" class="img-fluid rounded shadow-sm mb-3"
                                                style="max-height: 180px; object-fit: cover;">
                                            <div class="d-flex justify-content-center">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 mr-2"
                                                    onclick="document.getElementById('foto').click()">
                                                    <i class="fas fa-sync-alt mr-1"></i> Ganti Foto Baru
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger rounded-pill px-3"
                                                    id="removeImage">
                                                    <i class="fas fa-times mr-1"></i> Batal / Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions mt-4 border-top pt-4">
                                    <button type="submit"
                                        class="btn btn-sm btn-dark">
                                        <i class="fa fa-save mr-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-radius {
            border-radius: 12px;
        }

        /* Drag & Drop Zone Styles */
        .drag-drop-zone {
            border: 2px dashed #b8c2cc;
            background-color: #f8f9fc;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            min-height: 245px;
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
    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <script>
        $(document).ready(function() {
            // Select2 Inits
            $('.select2').select2({
                theme: "bootstrap-5",
                width: '100%'
            });
            $('.select2-no-search').select2({
                theme: "bootstrap-5",
                width: '100%',
                minimumResultsForSearch: Infinity
            });

            // Datepicker Flatpickr Init (Format Indonesia)
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true
            });

            // ==========================================
            // LOGIKA DRAG & DROP FOTO
            // ==========================================
            const dropZone = document.getElementById('dragDropZone');
            const fileInput = document.getElementById('foto');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const removeBtn = document.getElementById('removeImage');

            // Simpan URL gambar lama jika user membatalkan pengunggahan file baru
            const oldImageSrc = imagePreview.src;

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

            // Hapus / Batal upload file baru
            removeBtn.addEventListener('click', () => {
                fileInput.value = ''; // Hapus file yang ada di memori input

                if (oldImageSrc && oldImageSrc !== window.location.href) {
                    // Jika sebelumnya punya foto, kembalikan ke foto lama
                    imagePreview.src = oldImageSrc;
                } else {
                    // Jika sebelumnya tidak punya foto, sembunyikan preview dan tampilkan form drop
                    imagePreview.src = '';
                    previewContainer.classList.add('d-none');
                    dropZone.classList.remove('d-none');
                }
            });
        });
    </script>
@endpush
