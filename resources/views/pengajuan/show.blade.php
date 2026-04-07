@extends('layouts.master')
@section('title', 'Detail Pengajuan Praktikum')
@section('content')

    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h4 class="page-title text-dark font-weight-bold mb-1">Detail Pengajuan Peminjaman Laboratorium</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0 bg-transparent font-13">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Pengajuan
                                    Lab</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Detail</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-5 text-md-right mt-3 mt-md-0">
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid pb-5">
            <div class="row">

                {{-- KIRI: DETAIL INFORMASI PENGAJUAN --}}
                <div class="col-lg-8 mb-4">
                    <div class="card border border-light shadow-sm">
                        <div class="card-body p-4 p-md-5">

                            {{-- HEADER KONTEN --}}
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                                <div>
                                    <h3 class="font-weight-bold text-dark mb-1">Detail Peminjaman</h3>
                                    <p class="text-muted font-14 mb-0">Nomor Registrasi:
                                        <strong>{{ $nomorReg }}</strong>
                                    </p>
                                </div>
                                <span class="badge bg-{{ $uiStatus['color'] }} text-white px-3 py-2 font-12">
                                    <i data-feather="{{ $uiStatus['icon'] }}" class="mr-1"
                                        style="width: 14px; height: 14px;"></i> {{ $uiStatus['label'] }}
                                </span>
                            </div>

                            {{-- DATA GRID --}}
                            <h6 class="text-uppercase text-dark font-weight-bold font-12 tracking-wide mb-3 mt-4">Informasi
                                Akademik</h6>
                            <div class="row mb-4">
                                <div class="col-sm-4 font-weight-medium font-14 mb-1 mb-sm-0">Dosen Pengampu</div>
                                <div class="col-sm-8 font-14">{{ $namaDosen }}</div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-4 font-weight-medium font-14 mb-1 mb-sm-0">Mata Kuliah</div>
                                <div class="col-sm-8 font-14">{{ $namaMakul }}</div>
                            </div>

                            <h6 class="text-uppercase text-dark font-weight-bold font-12 tracking-wide mb-3 mt-5">Jadwal &
                                Lokasi</h6>
                            <div class="row mb-4">
                                <div class="col-sm-4 font-weight-medium font-14 mb-1 mb-sm-0">Laboratorium</div>
                                <div class="col-sm-8 font-14">{{ $namaLab }}</div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-4 font-weight-medium font-14 mb-1 mb-sm-0">Tanggal Praktikum</div>
                                <div class="col-sm-8 font-14">{{ $tglPrak }}</div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-4 font-weight-medium font-14 mb-1 mb-sm-0">Waktu Pelaksanaan</div>
                                <div class="col-sm-8 font-14">{{ $waktuPrak }}</div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-4 font-weight-medium font-14 mb-1 mb-sm-0">Tanggal & Waktu Pengajuan
                                </div>
                                <div class="col-sm-8 font-14">{{ $tglDibuat }} WIB</div>
                            </div>

                            {{-- JOBSHEET --}}
                            <h6 class="text-uppercase text-dark font-weight-bold font-12 tracking-wide mb-3 mt-5">Lampiran
                                Dokumen</h6>

                            <div class="border border-light rounded-lg p-3 d-flex justify-content-between align-items-center bg-white transition-hover"
                                style="box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light-danger text-danger rounded-lg d-flex justify-content-center align-items-center mr-3"
                                        style="width: 48px; height: 48px;">
                                        <i class="fas fa-file-pdf font-20"></i>
                                    </div>

                                    {{-- Keterangan File --}}
                                    <div>
                                        <h6 class="font-weight-bold text-dark mb-1 font-14" style="letter-spacing: 0.3px;">
                                            {{-- Mengambil nama file asli dari database --}}
                                            {{ $pengajuan->jobsheet ? basename($pengajuan->jobsheet) : 'Tidak ada lampiran' }}
                                        </h6>
                                        <div class="d-flex align-items-center text-muted font-12">
                                            <i data-feather="paperclip" class="mr-1"
                                                style="width: 12px; height: 12px;"></i>
                                            <span>PDF File</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol Minimalis (Hanya tampil jika file ada) --}}
                                @if ($pengajuan->jobsheet)
                                    <a href="{{ Storage::url($pengajuan->jobsheet) }}" target="_blank"
                                        class="btn btn-sm btn-dark">
                                        Buka File <i data-feather="external-link" class="ml-1 text-muted"
                                            style="width: 14px; height: 14px;"></i>
                                    </a>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                {{-- KANAN: TRACKING & TINDAKAN --}}
                <div class="col-lg-4 mb-4">
                    <div class="card border border-light shadow-sm">
                        <div class="card-body p-4">
                            <h6 class="font-weight-bold text-dark mb-4 border-bottom pb-3">Pelacakan Status</h6>

                            {{-- Catatan Penolakan (Jika ada) --}}
                            @if ($pengajuan->catatan)
                                <div class="alert alert-danger font-13 mb-4 p-3 border-0 bg-white"
                                    style="border-left: 3px solid #dc3545 !important; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                    <strong class="d-block mb-2 text-danger">Catatan:</strong>
                                    <div class="scrollable-note pr-2">
                                        <span class="font-italic text-dark">"{!! nl2br(e($pengajuan->catatan)) !!}"</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Clean Timeline --}}
                            <div class="clean-timeline mt-2">
                                {{-- Tahap 1 --}}
                                <div class="ct-item {{ $tl_1 }}">
                                    <div class="ct-point"></div>
                                    <div class="ct-content">
                                        <h6 class="font-weight-bold mb-1">Pengajuan Dibuat</h6>
                                        <small class="text-muted">{{ $tglDibuat }}</small>
                                    </div>
                                </div>

                                {{-- Tahap 2 --}}
                                <div class="ct-item {{ $tl_2 }}">
                                    <div class="ct-point"></div>
                                    <div class="ct-content">
                                        <h6 class="font-weight-bold mb-1">Verifikasi Kaprodi</h6>
                                        <small class="text-muted">
                                            @if ($tl_2 == 'active')
                                                Sedang diproses...
                                            @elseif($tl_2 == 'rejected')
                                                Ditolak
                                            @elseif($tl_2 == 'done')
                                                Disetujui
                                            @else
                                                Menunggu
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                {{-- Tahap 3 --}}
                                <div class="ct-item {{ $tl_3 }} mb-0">
                                    <div class="ct-point"></div>
                                    <div class="ct-content">
                                        <h6 class="font-weight-bold mb-1">Finalisasi Super Admin</h6>
                                        <small class="text-muted">
                                            @if ($tl_3 == 'active')
                                                Sedang divalidasi...
                                            @elseif($tl_3 == 'rejected')
                                                Ditolak
                                            @elseif($tl_3 == 'done')
                                                Terjadwal
                                            @else
                                                Menunggu Verifikasi Kaprodi
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- Aksi --}}
                            @if ($canReview || ($isOwner && str_contains($statusDB, 'Ditolak')))
                                <div class="mt-4 pt-4 border-top">
                                    @if ($canReview)
                                        <div class="row">
                                            <div class="col-6 pr-2">
                                                <button type="button"
                                                    class="btn btn-sm btn-success btn-block font-weight-medium"
                                                    data-toggle="modal" data-target="#modalTerima">
                                                    <i class="fas fa-check mr-1"></i> Setuju
                                                </button>
                                            </div>
                                            <div class="col-6 pl-2">
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-block font-weight-medium"
                                                    data-toggle="modal" data-target="#modalTolak">
                                                    <i class="fas fa-times mr-1"></i> Tolak
                                                </button>
                                            </div>
                                        </div>
                                    @elseif ($isOwner && str_contains($statusDB, 'Ditolak'))
                                        <a href="{{ route('pengajuan.create') }}"
                                            class="btn btn-sm btn-danger btn-block font-weight-medium">
                                            <i class="fas fa-redo mr-1"></i> Buat Pengajuan Ulang
                                        </a>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- INCLUDE MODAL VERIFIKASI (JIKA ADA AKSES) --}}
    @if ($canReview)
        @include('pengajuan.form')
    @endif

    <style>
        /* Styling Dasar Clean UI */
        .tracking-wide {
            letter-spacing: 0.5px;
        }

        /* Clean Timeline CSS */
        .clean-timeline {
            position: relative;
            padding-left: 20px;
        }

        .clean-timeline::before {
            content: '';
            position: absolute;
            left: 4px;
            top: 6px;
            bottom: 6px;
            width: 2px;
            background-color: #e9ecef;
        }

        .ct-item {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .ct-point {
            position: absolute;
            left: -20px;
            top: 4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #e9ecef;
            border: 2px solid #fff;
            box-shadow: 0 0 0 1px #ced4da;
            z-index: 2;
        }

        .ct-content {
            padding-left: 15px;
        }

        /* Status: Active (Kuning/Primary) */
        .ct-item.active .ct-point {
            background-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .ct-item.active .ct-content h6 {
            color: #007bff !important;
        }

        /* Status: Done (Hijau) */
        .ct-item.done .ct-point {
            background-color: #28a745;
            box-shadow: 0 0 0 1px #28a745;
        }

        /* Status: Rejected (Merah) */
        .ct-item.rejected .ct-point {
            background-color: #dc3545;
            box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.25);
        }

        .ct-item.rejected .ct-content h6 {
            color: #dc3545 !important;
        }

        /* Status: Pending (Abu-abu - default) */
        .ct-item.pending .ct-content h6 {
            color: #6c757d !important;
        }

        /* Latar merah sangat lembut untuk ikon PDF */
        .bg-light-danger {
            background-color: #fff0f1 !important;
        }

        /* Efek tombol saat di-hover */
        .hover-primary:hover {
            color: #5f76e8 !important;
            border-color: #5f76e8 !important;
        }

        .hover-primary:hover i {
            color: #5f76e8 !important;
        }

        /* Transisi halus */
        .transition-hover {
            transition: all 0.2s ease-in-out;
        }

        .transition-hover:hover {
            border-color: #ced4da !important;
        }

        /* Scrollable Note & Custom Scrollbar */
        .scrollable-note {
            max-height: 120px;
            /* Batas tinggi maksimal (sekitar 5-6 baris) */
            overflow-y: auto;
            /* Munculkan scroll vertikal jika teks melebihi tinggi */
        }

        /* Mempercantik Scrollbar (Khusus Webkit: Chrome, Safari, Edge) */
        .scrollable-note::-webkit-scrollbar {
            width: 5px;
        }

        .scrollable-note::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 10px;
        }

        .scrollable-note::-webkit-scrollbar-thumb {
            background: #dc3545;
            /* Warna merah senada dengan border */
            border-radius: 10px;
            opacity: 0.5;
        }

        .scrollable-note::-webkit-scrollbar-thumb:hover {
            background: #c82333;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') feather.replace();
        });

        @if ($canReview)
            function submitReview(statusKeputusan, roleTipe) {

                let catatanId = statusKeputusan === 'Terima' ? '#catatan_terima' : '#catatan_tolak';
                let catatan = $(catatanId).val();

                if (statusKeputusan === 'Tolak' && catatan.trim() === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Catatan alasan wajib diisi jika pengajuan ditolak.'
                    });
                    $(catatanId).focus();
                    return;
                }

                let urlPost = roleTipe === 'kaprodi' ?
                    '{{ route('pengajuan.verify.kaprodi', $pengajuan->id_pengajuan) }}' :
                    '{{ route('pengajuan.verify.admin', $pengajuan->id_pengajuan) }}';

                let btnId = statusKeputusan === 'Terima' ? '#btn-terima' : '#btn-tolak';
                let btnSubmit = $(btnId);
                let originalText = btnSubmit.html();

                btnSubmit.html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...').prop('disabled', true);

                $.post(urlPost, {
                        _token: '{{ csrf_token() }}',
                        status: statusKeputusan,
                        catatan: catatan
                    })
                    .done(res => {
                        $('.modal').modal('hide');
                        Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            .then(() => window.location.reload());
                    })
                    .fail(() => {
                        btnSubmit.html(originalText).prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan sistem saat memproses data.'
                        });
                    });
            }
        @endif
    </script>
@endpush
