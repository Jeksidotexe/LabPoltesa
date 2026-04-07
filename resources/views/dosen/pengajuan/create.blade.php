@extends('layouts.master')
@section('title', 'Form Pengajuan Praktikum')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Pengajuan Praktikum</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Pengajuan Lab</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="float-right">
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary font-weight-medium"><i
                                class="fa fa-arrow-left mr-1"></i>
                            Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <h4 class="card-title m-0 text-dark font-weight-bold">Formulir Pengajuan Jadwal Lab</h4>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row border-bottom pb-4 mb-4">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Kategori Praktikum <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('id_kategori') is-invalid @enderror"
                                        name="id_kategori" required>
                                        <option value="" disabled selected>-- Pilih Kategori --</option>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id_kategori }}"
                                                {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                                                {{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Laboratorium <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('id_lab') is-invalid @enderror"
                                        name="id_lab" required>
                                        <option value="" disabled selected>-- Pilih Lab --</option>
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
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Mata Kuliah Praktikum <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('id_makul') is-invalid @enderror"
                                        name="id_makul" required>
                                        <option value="" disabled selected>-- Pilih Mata Kuliah --</option>
                                        @foreach ($makul as $m)
                                            <option value="{{ $m->id_makul }}"
                                                {{ old('id_makul') == $m->id_makul ? 'selected' : '' }}>
                                                {{ $m->kode }} - {{ $m->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_makul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row pb-2">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Tanggal Pelaksanaan <span
                                            class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text"
                                            class="form-control custom-input datepicker bg-white @error('tanggal') is-invalid @enderror"
                                            name="tanggal" value="{{ old('tanggal') }}" placeholder="Pilih Tanggal"
                                            required>
                                        <i class="far fa-calendar-alt position-absolute text-muted"
                                            style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                                    </div>
                                    <small class="text-info mt-1 d-block"><i class="fas fa-info-circle"></i> Maks. 1 minggu
                                        dari hari ini.</small>
                                    @error('tanggal')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Jam Mulai <span
                                            class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text"
                                            class="form-control custom-input timepicker bg-white @error('jam_mulai') is-invalid @enderror"
                                            name="jam_mulai" value="{{ old('jam_mulai') }}" placeholder="-- : --" required>
                                        <i class="far fa-clock position-absolute text-muted"
                                            style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                                    </div>
                                    @error('jam_mulai')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label text-dark font-weight-medium">Jam Selesai <span
                                            class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text"
                                            class="form-control custom-input timepicker bg-white @error('jam_selesai') is-invalid @enderror"
                                            name="jam_selesai" value="{{ old('jam_selesai') }}" placeholder="-- : --"
                                            required>
                                        <i class="far fa-clock position-absolute text-muted"
                                            style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                                    </div>
                                    @error('jam_selesai')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-4 mt-2">
                                    <label
                                        class="form-label text-dark font-weight-medium border-top pt-4 d-block w-100">Upload
                                        Jobsheet<span class="text-danger">*</span></label>

                                    {{-- Drag & Drop Area untuk PDF --}}
                                    <div class="drag-drop-zone custom-radius mt-2" id="dragDropZone">
                                        <div class="dz-message">
                                            <i class="far fa-file-pdf text-danger mb-2 fa-3x"></i>
                                            <h6 class="font-weight-bold text-dark mb-1 mt-2">Tarik & Lepas File PDF di Sini
                                            </h6>
                                            <p class="text-muted font-13 mb-3">atau klik untuk menelusuri dokumen</p>
                                            <span class="badge bg-light-secondary text-secondary px-3 py-1 border">Wajib
                                                format PDF | Maks: 5MB</span>
                                        </div>
                                        <input type="file" id="jobsheet" name="jobsheet" class="d-none"
                                            accept="application/pdf" required>
                                    </div>
                                    @error('jobsheet')
                                        <div class="text-danger font-13 mt-2">{{ $message }}</div>
                                    @enderror

                                    {{-- Area Preview Dokumen Terpilih --}}
                                    <div id="previewContainer" class="d-none mt-3 p-3 border custom-radius">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center overflow-hidden">
                                                <i class="fas fa-file-pdf text-danger fa-2x mr-3"></i>
                                                <div class="text-truncate">
                                                    <h6 class="mb-0 text-dark font-weight-bold text-truncate"
                                                        id="fileName"></h6>
                                                    <small class="text-muted" id="fileSize"></small>
                                                </div>
                                            </div>
                                            <div class="ml-3 d-flex flex-shrink-0">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 mr-2"
                                                    onclick="document.getElementById('jobsheet').click()">
                                                    <i class="fas fa-sync-alt"></i> Ganti
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger rounded-pill px-3"
                                                    id="removeFile">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-4 pt-3 text-right">
                            <button type="submit" class="btn btn-sm btn-success font-weight-medium"><i
                                    class="fa fa-paper-plane mr-1"></i> Kirim Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-radius {
            border-radius: 8px;
        }

        /* Drag & Drop Zone Styles */
        .drag-drop-zone {
            border: 2px dashed #b8c2cc;
            background-color: #fcfcfc;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }

        .drag-drop-zone:hover,
        .drag-drop-zone.dragover {
            background-color: #fff5f5;
            /* Sentuhan merah muda tipis untuk PDF */
            border-color: #dc3545;
        }

        .drag-drop-zone .dz-message {
            pointer-events: none;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
                width: '100%'
            });

            // Variable Min & Max Date dari Controller
            const minDate = "{{ $minDate }}";
            const maxDate = "{{ $maxDate }}";

            // Datepicker Flatpickr Init (Format Indonesia)
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                locale: "id",
                minDate: minDate,
                maxDate: maxDate,
                allowInput: true
            });

            // Timepicker Flatpickr Init
            flatpickr(".timepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                allowInput: true
            });

            // ==========================================
            // LOGIKA DRAG & DROP FILE PDF
            // ==========================================
            const dropZone = document.getElementById('dragDropZone');
            const fileInput = document.getElementById('jobsheet');
            const previewContainer = document.getElementById('previewContainer');
            const fileNameDisplay = document.getElementById('fileName');
            const fileSizeDisplay = document.getElementById('fileSize');
            const removeBtn = document.getElementById('removeFile');

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

                    // Validasi PDF
                    if (file.type !== "application/pdf") {
                        alert('Format file tidak didukung! Harap unggah file PDF.');
                        fileInput.value = '';
                        return;
                    }

                    // Validasi Ukuran (Maks 5MB = 5242880 bytes)
                    if (file.size > 5242880) {
                        alert('Ukuran file terlalu besar! Maksimal 5MB.');
                        fileInput.value = '';
                        return;
                    }

                    // Menampilkan Nama dan Ukuran File
                    fileNameDisplay.textContent = file.name;

                    let sizeKB = file.size / 1024;
                    if (sizeKB > 1024) {
                        fileSizeDisplay.textContent = (sizeKB / 1024).toFixed(2) + ' MB';
                    } else {
                        fileSizeDisplay.textContent = sizeKB.toFixed(2) + ' KB';
                    }

                    // Ubah Tampilan UI
                    dropZone.classList.add('d-none');
                    previewContainer.classList.remove('d-none');
                    previewContainer.classList.add('d-block');
                }
            }

            removeBtn.addEventListener('click', () => {
                fileInput.value = '';
                fileNameDisplay.textContent = 'nama_file.pdf';
                fileSizeDisplay.textContent = '0 KB';

                previewContainer.classList.remove('d-block');
                previewContainer.classList.add('d-none');
                dropZone.classList.remove('d-none');
            });

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Jadwal Bentrok!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33'
                });
            @endif
        });
    </script>
@endpush
