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
                                        name="id_lab" id="id_lab" required>
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

                        <div class="row border-bottom pb-4 mb-4">
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
                                    <small class="text-info mt-1 d-block"><i class="fas fa-info-circle"></i> Min. 7 Hari
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

                        {{-- SEKSI PEMINJAMAN ALAT DINAMIS --}}
                        <div class="row border-bottom pb-4 mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label text-dark font-weight-bold m-0">
                                        <i class="fas fa-boxes text-info mr-2"></i> Peminjaman Alat <small
                                            class="text-muted font-weight-normal">(Opsional)</small>
                                    </label>
                                    <button type="button"
                                        class="btn btn-sm btn-dark d-none"
                                        id="btn-tambah-alat">
                                        <i class="fas fa-plus mr-1"></i> Tambah Alat Lain
                                    </button>
                                </div>

                                {{-- Alert Info Awal --}}
                                <div id="alert-pilih-lab" class="alert alert-light border font-13 mb-3">
                                    <i class="fas fa-info-circle mr-1 text-info"></i> Silakan pilih Laboratorium terlebih
                                    dahulu untuk menampilkan daftar alat yang tersedia di ruangan tersebut.
                                </div>

                                <div id="container-alat">
                                    {{-- Baris Alat Dinamis Akan Muncul Di Sini --}}
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
                            <button type="submit" class="btn btn-sm btn-success font-weight-medium px-4"><i
                                    class="fa fa-paper-plane mr-2"></i> Kirim Pengajuan</button>
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
            // Data Master Alat dilempar dari Controller
            const dataAlatMaster = @json($alat);

            $('.select2').select2({
                theme: "bootstrap-5",
                width: '100%'
            });

            // Variable Min Date dari Controller (Otomatis H+7)
            const minDate = "{{ $minDate }}";

            // Datepicker Flatpickr Init
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                locale: "id",
                minDate: minDate,
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
            // LOGIKA TAMBAH ALAT DINAMIS (LEBIH CLEAN)
            // ==========================================
            function tambahRowAlat(selectedLab, isFirstRow = false) {
                let alatTersedia = dataAlatMaster.filter(a => a.id_lab == selectedLab);

                if (alatTersedia.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Kosong',
                        text: 'Tidak ada alat yang tersedia (Kondisi Baik) di Lab ini saat ini.'
                    });
                    return;
                }

                let optionsHtml = '<option value="" disabled selected>-- Pilih Alat --</option>';
                alatTersedia.forEach(a => {
                    // Menghapus tulisan sisa stok dari text option agar terlihat bersih
                    optionsHtml +=
                        `<option value="${a.id_alat}" data-stok="${a.jumlah}">${a.nama_alat}</option>`;
                });

                let rowHtml = `
                <div class="row align-items-start mb-3 item-alat p-3 border mx-0">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <label class="font-12 font-weight-medium text-uppercase">Nama Alat</label>
                        <select class="form-control select-alat" name="id_alat[]" ${isFirstRow ? '' : 'required'}>
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <label class="font-12 font-weight-medium text-uppercase">Jumlah Pinjam</label>
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control input-jumlah mr-2" name="jumlah_pinjam[]" min="1" placeholder="0" style="max-width: 100px;" ${isFirstRow ? '' : 'required'}>
                            <span class="badge bg-white border border-dark text-dark info-stok d-none">Stok Tersedia: <span class="stok-value font-weight-bold">0</span> Unit</span>
                        </div>
                    </div>
                    <div class="col-md-2 mt-4 text-right">
                        <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-alat"><i class="fas fa-trash"></i> Hapus</button>
                    </div>
                </div>`;

                $('#container-alat').append(rowHtml);
                $('.select-alat').select2({
                    theme: "bootstrap-5",
                    width: '100%'
                });
            }

            // Trigger saat Laboratorium diubah
            $('#id_lab').change(function() {
                let selectedLab = $(this).val();

                // Kosongkan container alat jika lab diganti
                $('#container-alat').empty();

                if (selectedLab) {
                    $('#alert-pilih-lab').slideUp('fast');
                    $('#btn-tambah-alat').removeClass('d-none');

                    // Tambah 1 baris otomatis saat lab pertama kali dipilih
                    tambahRowAlat(selectedLab, true);
                }
            });

            // Trigger saat tombol tambah alat lain di klik manual
            $('#btn-tambah-alat').click(function() {
                let selectedLab = $('#id_lab').val();
                if (selectedLab) {
                    tambahRowAlat(selectedLab, false);
                }
            });

            // Hapus Row Alat
            $(document).on('click', '.btn-hapus-alat', function() {
                $(this).closest('.item-alat').remove();
            });

            // Validasi Input Jumlah Maksimal & Munculkan Badge Stok
            $(document).on('change', '.select-alat', function() {
                let maxStok = $(this).find(':selected').data('stok');
                let container = $(this).closest('.item-alat');
                let inputJumlah = container.find('.input-jumlah');
                let badgeStok = container.find('.info-stok');

                // Set atribut required & max secara dinamis
                $(this).prop('required', true);
                inputJumlah.prop('required', true);

                inputJumlah.attr('max', maxStok).val(1);

                // Tampilkan badge stok yang ada disebelah input angka
                badgeStok.removeClass('d-none');
                badgeStok.find('.stok-value').text(maxStok);
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

                    if (file.type !== "application/pdf") {
                        alert('Format file tidak didukung! Harap unggah file PDF.');
                        fileInput.value = '';
                        return;
                    }

                    if (file.size > 5242880) {
                        alert('Ukuran file terlalu besar! Maksimal 5MB.');
                        fileInput.value = '';
                        return;
                    }

                    fileNameDisplay.textContent = file.name;

                    let sizeKB = file.size / 1024;
                    if (sizeKB > 1024) {
                        fileSizeDisplay.textContent = (sizeKB / 1024).toFixed(2) + ' MB';
                    } else {
                        fileSizeDisplay.textContent = sizeKB.toFixed(2) + ' KB';
                    }

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
