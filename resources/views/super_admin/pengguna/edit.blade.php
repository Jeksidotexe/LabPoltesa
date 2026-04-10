@extends('layouts.master')
@section('title', 'Edit Akun Pengguna')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-7">
                    <h4 class="page-title text-truncate text-dark font-weight-bold mb-1">Edit Pengguna</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('pengguna.index') }}"
                                        class="text-muted">Pengguna</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="float-right">
                        <a href="{{ route('pengguna.index') }}" class="btn btn-sm btn-secondary">
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
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="card-title text-dark font-weight-bold mb-1">Formulir Edit Pengguna</h4>
                                    <p class="text-muted font-14 mb-0">Lakukan perubahan data profil pada form di bawah ini.
                                    </p>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('pengguna.update', $pengguna->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-4 mt-3"><i
                                        class="fas fa-address-card mr-2"></i>Informasi Akun & Profil</h5>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Role / Hak Akses <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2-no-search @error('role') is-invalid @enderror"
                                            name="role" required>
                                            <option value="Super Admin"
                                                {{ old('role', $pengguna->role) == 'Super Admin' ? 'selected' : '' }}>Super
                                                Admin</option>
                                            <option value="Admin"
                                                {{ old('role', $pengguna->role) == 'Admin' ? 'selected' : '' }}>Admin
                                            </option>
                                            <option value="Dosen"
                                                {{ old('role', $pengguna->role) == 'Dosen' ? 'selected' : '' }}>Dosen
                                            </option>
                                            <option value="Kaprodi"
                                                {{ old('role', $pengguna->role) == 'Kaprodi' ? 'selected' : '' }}>Kaprodi
                                            </option>
                                            <option value="Kajur"
                                                {{ old('role', $pengguna->role) == 'Kajur' ? 'selected' : '' }}>Kajur
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-5 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">NIP <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nip"
                                            value="{{ old('nip', $pengguna->nip) }}" required>
                                        <small class="text-info font-12"><i class="fas fa-info-circle"></i> Username akan
                                            otomatis disinkronisasi.</small>
                                    </div>
                                    <div class="col-md-5 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama"
                                            value="{{ old('nama', $pengguna->nama) }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Gelar Depan &
                                            Belakang</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="gelar_depan"
                                                value="{{ old('gelar_depan', $pengguna->gelar_depan) }}"
                                                placeholder="Depan (Contoh: Ir.)">
                                            <input type="text" class="form-control border-left-0" name="gelar_belakang"
                                                value="{{ old('gelar_belakang', $pengguna->gelar_belakang) }}"
                                                placeholder="Belakang (Contoh: M.T.)">
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email', $pengguna->email) }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Program Studi <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2" name="id_prodi" required>
                                            <option value="" selected>Tidak Ada Program Studi</option>
                                            @foreach ($prodi as $p)
                                                <option value="{{ $p->id_prodi }}"
                                                    {{ old('id_prodi', $pengguna->id_prodi) == $p->id_prodi ? 'selected' : '' }}>
                                                    {{ $p->kode }} - {{ $p->nama_prodi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Jabatan <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="jabatan"
                                            value="{{ old('jabatan', $pengguna->jabatan) }}" required>
                                    </div>
                                    <div class="col-md-4 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">No. Telepon <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="telepon"
                                            value="{{ old('telepon', $pengguna->telepon) }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Jenis Kelamin <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2-no-search" name="jenis_kelamin" required>
                                            <option value="" selected disabled>Pilih Role</option>
                                            <option value="L"
                                                {{ old('jenis_kelamin', $pengguna->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                                Laki-Laki</option>
                                            <option value="P"
                                                {{ old('jenis_kelamin', $pengguna->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Tanggal Lahir <span
                                                class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control datepicker" name="tanggal_lahir"
                                                value="{{ old('tanggal_lahir', $pengguna->tanggal_lahir) }}" required>
                                            <i class="far fa-calendar-alt position-absolute text-muted"
                                                style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Tanggal Bergabung <span
                                                class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control datepicker"
                                                name="tanggal_bergabung"
                                                value="{{ old('tanggal_bergabung', $pengguna->tanggal_bergabung) }}"
                                                required>
                                            <i class="far fa-calendar-alt position-absolute text-muted"
                                                style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-3 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2-no-search" name="status" required>
                                            <option value="Aktif"
                                                {{ old('status', $pengguna->status) == 'Aktif' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="Nonaktif"
                                                {{ old('status', $pengguna->status) == 'Nonaktif' ? 'selected' : '' }}>
                                                Nonaktif</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- AREA DRAG & DROP FOTO --}}
                                @php $hasFoto = $pengguna->foto && Storage::disk('public')->exists($pengguna->foto); @endphp
                                <div class="row">
                                    <div class="col-md-12 form-group mb-4 mt-2">
                                        <label class="form-label text-dark font-weight-medium">Foto Profil
                                            (Opsional)</label>

                                        <div class="drag-drop-zone custom-radius {{ $hasFoto ? 'd-none' : '' }}"
                                            id="dragDropZone">
                                            <div class="dz-message">
                                                <i class="fas fa-cloud-upload-alt text-primary mb-2 fa-3x"></i>
                                                <h6 class="font-weight-bold text-dark mb-1">Tarik & Lepas Foto di Sini</h6>
                                                <p class="text-muted font-13 mb-3">atau klik untuk menelusuri file komputer
                                                    Anda</p>
                                                <span
                                                    class="badge bg-light-secondary text-secondary px-3 py-1 border">Maks:
                                                    2MB | JPG, PNG</span>
                                            </div>
                                            <input type="file" id="foto" name="foto" class="d-none"
                                                accept="image/jpeg,image/png,image/jpg">
                                        </div>

                                        <div id="previewContainer"
                                            class="mt-3 text-center p-4 border custom-radius bg-light {{ $hasFoto ? '' : 'd-none' }}">
                                            <img id="imagePreview"
                                                src="{{ $hasFoto ? Storage::url($pengguna->foto) : '' }}"
                                                alt="Preview Foto" class="img-fluid rounded shadow-sm mb-3"
                                                style="max-height: 180px; object-fit: cover;">
                                            <div class="d-flex justify-content-center">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-info rounded-pill px-3 mr-2"
                                                    onclick="document.getElementById('foto').click()">
                                                    <i class="fas fa-sync-alt mr-1"></i> Ganti
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger rounded-pill px-3"
                                                    id="removeImage">
                                                    <i class="fas fa-times mr-1"></i> Batal / Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions mt-4 border-top pt-4 text-right">
                                    <button type="submit" class="btn btn-sm btn-dark font-weight-medium">
                                        <i class="fa fa-save mr-2"></i> Simpan Perubahan
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
            border-radius: 12px !important;
        }

        .shadow-sm {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04) !important;
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
                width: '100%'
            });
            $('.select2-no-search').select2({
                theme: "bootstrap-5",
                width: '100%',
                minimumResultsForSearch: Infinity
            });

            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true
            });

            // LOGIKA DRAG & DROP FOTO
            const dropZone = document.getElementById('dragDropZone');
            const fileInput = document.getElementById('foto');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const removeBtn = document.getElementById('removeImage');
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

            removeBtn.addEventListener('click', () => {
                fileInput.value = '';
                if (oldImageSrc && oldImageSrc !== window.location.href) {
                    imagePreview.src = oldImageSrc;
                } else {
                    imagePreview.src = '';
                    previewContainer.classList.add('d-none');
                    dropZone.classList.remove('d-none');
                }
            });
        });
    </script>
@endpush
