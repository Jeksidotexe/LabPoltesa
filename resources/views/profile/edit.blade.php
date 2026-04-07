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
                            <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Profil</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-5 text-right">
                    <a href="{{ route('profile.show') }}" class="btn btn-sm btn-secondary shadow-sm">
                        <i class="fa fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid pb-5">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- PANEL KIRI: INFO & INSTRUKSI --}}
                    <div class="col-lg-4 col-md-12 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="text-center mb-4">
                                    <div class="bg-light-primary text-primary rounded-circle d-inline-flex justify-content-center align-items-center mb-3"
                                        style="width: 70px; height: 70px;">
                                        <i class="fas fa-user-edit fa-2x"></i>
                                    </div>
                                    <h5 class="font-weight-bold text-dark">Perbarui Data Anda</h5>
                                    <p class="text-muted font-13">Pastikan data yang Anda masukkan sudah benar dan valid.
                                    </p>
                                </div>

                                <hr>

                                @if ($user->role == 'Dosen')
                                    <div class="alert alert-info py-3 px-3 mt-3 mb-0 border-0" style="font-size: 13px;">
                                        <i class="fas fa-info-circle mr-1 mb-2 d-block fa-lg"></i>
                                        Data spesifik akademik seperti <b>Nama Lengkap, NIP, Program Studi, dan Jabatan</b>
                                        telah dikunci oleh sistem untuk menjaga integritas data. Silakan hubungi
                                        Administrator jika terdapat kesalahan data.
                                    </div>
                                @else
                                    <div class="alert alert-light py-3 px-3 mt-3 mb-0 border" style="font-size: 13px;">
                                        <i class="fas fa-shield-alt mr-1"></i>
                                        Gunakan kombinasi password yang kuat (huruf, angka, dan simbol) untuk menjaga
                                        keamanan akun Anda.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- PANEL KANAN: FORMULIR INPUT --}}
                    <div class="col-lg-8 col-md-12">
                        <div class="card border-0 shadow-sm rounded-lg">
                            <div class="card-body p-4 p-md-5">

                                {{-- BAGIAN 1: KREDENSIAL LOGIN --}}
                                <h5 class="text-dark font-weight-bold border-bottom pb-2 mb-4">
                                    <i class="fas fa-lock text-warning mr-2"></i> Keamanan Akun
                                </h5>
                                <div class="row mb-2">
                                    <div class="col-md-12 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Username Login <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control bg-white @error('username') is-invalid @enderror"
                                            name="username" value="{{ old('username', $user->username) }}" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 form-group mb-4">
                                        <label class="form-label text-dark font-weight-medium">Password Baru</label>
                                        <div class="position-relative">
                                            <input type="password" id="password"
                                                class="form-control bg-white @error('password') is-invalid @enderror"
                                                name="password" placeholder="Kosongkan jika tidak ingin ganti"
                                                style="padding-right: 2.5rem;">
                                            <span class="toggle-password" data-target="#password"
                                                style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); cursor: pointer; color: #6c757d;">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                        @error('password')
                                            <div class="text-danger font-13 mt-1">{{ $message }}</div>
                                        @enderror
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

                                {{-- BAGIAN 2: DATA KONTAK & FOTO (HANYA DOSEN) --}}
                                @if ($user->role == 'Dosen' && $user->dosen)
                                    <h5 class="text-dark font-weight-bold border-bottom pb-2 mb-4 mt-2">
                                        <i class="fas fa-address-book text-info mr-2"></i> Kontak & Foto Profil
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-4">
                                            <label class="form-label text-dark font-weight-medium">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email', $user->dosen->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 form-group mb-4">
                                            <label class="form-label text-dark font-weight-medium">No. Telepon<span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('telepon') is-invalid @enderror" name="telepon"
                                                value="{{ old('telepon', $user->dosen->telepon) }}" required>
                                            @error('telepon')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- AREA DRAG & DROP FOTO PROFIL --}}
                                        <div class="col-md-12 form-group mb-4">
                                            <label class="form-label text-dark font-weight-medium">Ganti Foto Profil</label>
                                            <small class="text-muted d-block mb-2">Biarkan jika tidak ingin mengubah foto
                                                saat ini.</small>

                                            @php $hasFoto = $user->dosen->foto && Storage::disk('public')->exists($user->dosen->foto); @endphp

                                            {{-- Drag Zone (Tersembunyi jika punya foto) --}}
                                            <div class="drag-drop-zone custom-radius {{ $hasFoto ? 'd-none' : '' }}"
                                                id="dragDropZone">
                                                <div class="dz-message">
                                                    <i class="fas fa-cloud-upload-alt text-primary mb-2 fa-3x"></i>
                                                    <h6 class="font-weight-bold text-dark mb-1">Tarik & Lepas Foto di Sini
                                                    </h6>
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
                                                <img id="imagePreview"
                                                    src="{{ $hasFoto ? Storage::url($user->dosen->foto) : '' }}"
                                                    alt="Preview Foto"
                                                    class="img-fluid rounded-circle shadow-sm mb-3 border border-white"
                                                    style="width: 150px; height: 150px; object-fit: cover; border-width: 4px !important;">
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
                                @endif

                                <div class="form-actions mt-4 border-top pt-4 text-right">
                                    <button type="submit" class="btn btn-sm btn-primary font-weight-medium">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

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
            @if ($user->role == 'Dosen' && $user->dosen)
                const dropZone = document.getElementById('dragDropZone');
                const fileInput = document.getElementById('foto');
                const previewContainer = document.getElementById('previewContainer');
                const imagePreview = document.getElementById('imagePreview');
                const removeBtn = document.getElementById('removeImage');

                // Simpan URL gambar lama jika membatalkan upload file baru
                const oldImageSrc = imagePreview.src;

                // Klik area untuk buka file explorer
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

                        // Validasi Ukuran (2MB)
                        if (file.size > 2097152) {
                            alert('Ukuran file terlalu besar! Maksimal 2MB.');
                            fileInput.value = '';
                            return;
                        }

                        // Tampilkan Gambar
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            dropZone.classList.add('d-none');
                            previewContainer.classList.remove('d-none');
                        }
                        reader.readAsDataURL(file);
                    }
                }

                // Hapus pilihan dan kembali ke form drag/foto lama
                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        fileInput.value = '';

                        if (oldImageSrc && oldImageSrc !== window.location.href) {
                            // Kembali ke foto lama di database
                            imagePreview.src = oldImageSrc;
                        } else {
                            // Kosong murni
                            imagePreview.src = '';
                            previewContainer.classList.add('d-none');
                            dropZone.classList.remove('d-none');
                        }
                    });
                }
            @endif
        });
    </script>
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // === TANGKAP SESSION SUCCESS/ERROR DARI CONTROLLER ===
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: {
                        popup: 'rounded-lg shadow-sm'
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Tutup'
                });
            @endif
        });
    </script>
@endpush
