@extends('layouts.master')
@section('title', 'Edit Profil Saya')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-7">
                    <h4 class="page-title text-dark font-weight-bold mb-1">Edit Profil</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0 bg-transparent font-14">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('profil.show') }}">Profil</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-5 text-right">
                    <a href="{{ route('profil.show') }}" class="btn btn-sm btn-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid pb-5">
            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- PANEL KIRI: INFO & INSTRUKSI --}}
                    <div class="col-lg-4 col-md-12 mb-4">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="text-center mb-4">
                                    <div class="bg-primary text-white rounded-circle d-inline-flex justify-content-center align-items-center mb-3"
                                        style="width: 70px; height: 70px;">
                                        {{-- Menggunakan Feather Icon 'edit' dengan ukuran disesuaikan --}}
                                        <i data-feather="edit" style="width: 32px; height: 32px;"></i>
                                    </div>
                                    <h5 class="font-weight-bold text-dark">Perbarui Data Anda</h5>
                                    <p class="text-muted font-13">Pastikan data yang Anda masukkan sudah benar dan valid.
                                    </p>
                                </div>

                                <hr>

                                @if ($user->role != 'Super Admin')
                                    <div class="alert alert-info py-3 px-3 mt-3 mb-0" style="font-size: 13px;">
                                        <span class="d-block mb-2 font-weight-bold text-info">
                                            <i class="fas fa-info-circle mr-1 fa-lg"></i> Informasi
                                        </span>
                                        Data spesifik akademik dan identitas utama (Seperti Nama, NIP, Jabatan, dan Kelamin)
                                        telah dikunci oleh sistem untuk menjaga integritas data. Silakan hubungi
                                        Administrator jika terdapat kesalahan data utama.
                                    </div>
                                @else
                                    <div class="alert alert-light py-3 px-3 mt-3 mb-0 border text-center"
                                        style="font-size: 13px;">
                                        <i class="fas fa-shield-alt mr-1 text-primary"></i>
                                        Anda memiliki akses penuh untuk mengubah seluruh data identitas Anda.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- PANEL KANAN: FORMULIR INPUT --}}
                    <div class="col-lg-8 col-md-12">
                        <div class="card">
                            <div class="card-body p-4 p-md-5">

                                {{-- BAGIAN 1: KREDENSIAL & KONTAK DASAR (BISA DIEDIT OLEH SEMUA) --}}
                                <h5 class="text-dark font-weight-bold border-bottom pb-2 mb-4">
                                    <i class="fas fa-lock text-warning mr-2"></i> Keamanan & Kontak Akun
                                </h5>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row mb-2">
                                    <div class="col-md-12 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Username Login <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control bg-white" name="username"
                                            value="{{ old('username', $user->username) }}" required>
                                    </div>

                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email', $user->email) }}" required>
                                    </div>

                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">No. Telepon <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="telepon"
                                            value="{{ old('telepon', $user->telepon) }}" required>
                                    </div>

                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Password Baru</label>
                                        <div class="position-relative">
                                            <input type="password" id="password" class="form-control bg-white"
                                                name="password" placeholder="Kosongkan jika tidak ingin diganti"
                                                style="padding-right: 2.5rem;">
                                            <span class="toggle-password" data-target="#password"
                                                style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); cursor: pointer; color: #6c757d;">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Konfirmasi Password
                                            Baru</label>
                                        <div class="position-relative">
                                            <input type="password" id="password_confirmation" class="form-control bg-white"
                                                name="password_confirmation" placeholder="Ulangi password baru"
                                                style="padding-right: 2.5rem;">
                                            <span class="toggle-password" data-target="#password_confirmation"
                                                style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); cursor: pointer; color: #6c757d;">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                {{-- BAGIAN 2: IDENTITAS LENGKAP (HANYA BISA DIEDIT SUPER ADMIN) --}}
                                @if ($user->role == 'Super Admin')
                                    <h5 class="text-dark font-weight-bold border-bottom pb-2 mb-4 mt-2">
                                        <i class="fas fa-address-book text-info mr-2"></i> Identitas & Profil Akademik
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-4">
                                            <label class="form-label text-dark font-weight-medium">NIP <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nip"
                                                value="{{ old('nip', $user->nip) }}">
                                        </div>
                                        <div class="col-md-6 form-group mb-4">
                                            <label class="form-label text-dark font-weight-medium">Nama Lengkap <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nama"
                                                value="{{ old('nama', $user->nama) }}" required>
                                        </div>
                                        <div class="col-md-6 form-group mb-4">
                                            <label class="form-label text-dark font-weight-medium">Gelar Depan &
                                                Belakang</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="gelar_depan"
                                                    value="{{ old('gelar_depan', $user->gelar_depan) }}">
                                                <input type="text" class="form-control" name="gelar_belakang"
                                                    value="{{ old('gelar_belakang', $user->gelar_belakang) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group mb-4">
                                            <label class="form-label text-dark font-weight-medium">Jabatan <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="jabatan"
                                                value="{{ old('jabatan', $user->jabatan) }}">
                                        </div>
                                        <div class="col-md-6 form-group mb-4">
                                            <label class="form-label text-dark font-weight-medium">Jenis Kelamin <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control select2-no-search" name="jenis_kelamin" required>
                                                <option value="L"
                                                    {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                                    Laki-Laki</option>
                                                <option value="P"
                                                    {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                                    Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group mb-4">
                                            <label class="form-label text-dark font-weight-medium">Tanggal Lahir <span
                                                    class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control datepicker"
                                                    name="tanggal_lahir"
                                                    value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" required>
                                                <i class="far fa-calendar-alt position-absolute text-muted"
                                                    style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                {{-- BAGIAN 3: AREA DRAG & DROP FOTO (UNTUK SEMUA USER) --}}
                                <h5 class="text-dark font-weight-bold border-bottom pb-2 mb-4 mt-2">
                                    <i class="fas fa-camera text-success mr-2"></i> Foto Profil
                                </h5>
                                <div class="row">
                                    <div class="col-md-12 form-group mb-4">
                                        <small class="text-muted d-block mb-2">Pilih gambar dari perangkat Anda. Biarkan
                                            jika tidak ingin mengubah foto saat ini.</small>

                                        {{-- Drag Zone --}}
                                        <div class="drag-drop-zone custom-radius {{ $hasFoto ? 'd-none' : '' }}"
                                            id="dragDropZone">
                                            <div class="dz-message">
                                                <i class="fas fa-cloud-upload-alt text-primary mb-2 fa-3x"></i>
                                                <h6 class="font-weight-bold text-dark mb-1">Tarik & Lepas Foto di Sini</h6>
                                                <p class="text-muted font-13 mb-3">atau klik untuk menelusuri file komputer
                                                    Anda</p>
                                                <span
                                                    class="badge bg-light-secondary text-secondary px-3 py-1 border custom-radius">Maks:
                                                    2MB | JPG, PNG</span>
                                            </div>
                                            <input type="file" id="foto" name="foto" class="d-none"
                                                accept="image/jpeg,image/png,image/jpg">
                                        </div>

                                        {{-- Preview Zone --}}
                                        <div id="previewContainer"
                                            class="mt-2 text-center p-4 border custom-radius bg-light {{ $hasFoto ? '' : 'd-none' }}">
                                            <img id="imagePreview" src="{{ $urlFoto }}" alt="Preview Foto"
                                                class="img-fluid rounded-circle shadow-sm mb-3 border border-white"
                                                style="width: 150px; height: 150px; object-fit: cover; border-width: 4px !important;">
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

                                <div class="form-actions mt-4 border-top pt-4 text-right">
                                    <button type="submit" class="btn btn-sm btn-primary font-weight-medium px-4">
                                        <i class="fa fa-save mr-2"></i> Simpan Perubahan
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <style>
        .custom-radius {
            border-radius: 12px !important;
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
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

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

            // --- Logika Toggle Show/Hide Password ---
            $('.toggle-password').click(function() {
                let targetInput = $($(this).data('target'));
                let icon = $(this).find('i');
                if (targetInput.attr('type') === 'password') {
                    targetInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash text-primary');
                } else {
                    targetInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash text-primary').addClass('fa-eye text-muted');
                }
            });

            // --- Logika Drag & Drop Foto Profil ---
            const dropZone = document.getElementById('dragDropZone');
            const fileInput = document.getElementById('foto');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const removeBtn = document.getElementById('removeImage');
            const oldImageSrc = imagePreview.src;

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

            if (fileInput) {
                fileInput.addEventListener('change', handlePreview);
            }

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

            if (removeBtn) {
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
            }
        });
    </script>
@endpush
