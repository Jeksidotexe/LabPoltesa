@extends('layouts.master')
@section('title', 'Detail Jadwal Praktikum')
@section('content')
    <div class="page-wrapper">

        {{-- HEADER BREADCRUMB --}}
        <div class="page-breadcrumb pt-3 pb-3">
            <div class="row align-items-center">
                <div class="col-md-7 col-12">
                    <h4 class="page-title text-dark font-weight-bold mb-1">Detail Jadwal Praktikum</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0 bg-transparent font-14">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('jadwal.index') }}">Jadwal</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-5 col-12 mt-3 mt-md-0 text-md-right">
                    <a href="{{ route('jadwal.index') }}"
                        class="btn btn-sm btn-secondary font-weight-medium">
                        <i class="fa fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid pb-5">
            <div class="row">

                {{-- KOLOM KIRI: INFORMASI AKADEMIK & DOKUMEN --}}
                <div class="col-lg-7 col-md-12 mb-4 d-flex">
                    <div class="card w-100">
                        <div class="card-body p-4 p-md-5">

                            {{-- HEADER KARTU KIRI --}}
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-md-center border-bottom pb-4 mb-4">
                                <div>
                                    <h4 class="text-dark font-weight-bold mb-1">Informasi Akademik</h4>
                                    <p class="text-muted font-14 mb-0">Nomor Registrasi:
                                        <strong>{{ $nomorReg }}</strong>
                                    </p>
                                </div>
                                <div class="mt-3 mt-md-0">
                                    <span
                                        class="badge bg-success text-white px-3 py-2 rounded-pill font-13 font-weight-bold shadow-sm">
                                        <i class="fas fa-check-circle mr-1"></i> Disetujui
                                    </span>
                                </div>
                            </div>

                            {{-- ISI DETAIL AKADEMIK (GRID 2x2) --}}
                            <div class="row border-bottom pb-4 mb-4">
                                <div class="col-md-6 mb-4">
                                    <small class="text-muted d-block mb-1">Dosen Pengampu</small>
                                    <span class="text-dark font-15 font-weight-bold">{{ $namaDosen }}</span>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <small class="text-muted d-block mb-1">Program Studi</small>
                                    <span
                                        class="text-dark font-15 font-weight-bold">{{ $jadwal->user->prodi ? $jadwal->user->prodi->nama_prodi : '-' }}</span>
                                </div>
                                <div class="col-md-6 mb-4 mb-md-0">
                                    <small class="text-muted d-block mb-1">Mata Kuliah</small>
                                    <span
                                        class="text-dark font-15 font-weight-bold">{{ $jadwal->makul->nama ?? '-' }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Kategori Praktikum</small>
                                    <span
                                        class="text-dark font-15 font-weight-bold">{{ $jadwal->kategori->nama_kategori ?? 'Umum' }}</span>
                                </div>
                            </div>

                            {{-- BAGIAN JOBSHEET --}}
                            <h6 class="text-danger font-weight-bold mb-3"><i class="fas fa-file-pdf mr-2"></i>Jobsheet</h6>

                            @if ($jadwal->jobsheet)
                                <div class="d-flex align-items-center justify-content-between bg-light p-3 rounded border">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-pdf fa-2x text-danger mr-3"></i>
                                        <div>
                                            <h6 class="mb-0 text-dark font-weight-bold">{{ basename($jadwal->jobsheet) }}
                                            </h6>
                                            <small class="text-muted">Dokumen PDF Terlampir</small>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($jadwal->jobsheet) }}" target="_blank"
                                        class="btn btn-sm btn-dark">
                                        <i class="fas fa-external-link-alt mr-1"></i> Buka File
                                    </a>
                                </div>
                            @else
                                <div class="bg-light p-3 rounded border text-muted">
                                    <i class="fas fa-info-circle mr-1"></i> Tidak ada dokumen jobsheet yang dilampirkan.
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: KARTU LOKASI & WAKTU --}}
                <div class="col-lg-5 col-md-12 mb-4 d-flex">
                    <div class="card w-100 overflow-hidden">
                        <div class="card-header bg-white border-bottom pt-4 pb-3">
                            <h6 class="text-dark font-weight-bold mb-0">
                                <i class="fas fa-map-marker-alt text-danger mr-2"></i>Lokasi & Waktu Praktikum
                            </h6>
                        </div>

                        {{-- Area Foto Rasio 16:9 --}}
                        <div class="card-body p-0 bg-light d-flex justify-content-center align-items-center position-relative"
                            style="aspect-ratio: 16/9; overflow: hidden;">
                            @if ($jadwal->lab && $jadwal->lab->foto)
                                <img src="{{ Storage::url($jadwal->lab->foto) }}" alt="Foto Lab" class="w-100 h-100"
                                    style="object-fit: cover; object-position: center;">
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-3x mb-2 text-light-gray"></i>
                                    <p class="m-0 font-13">Foto tidak tersedia</p>
                                </div>
                            @endif
                        </div>

                        {{-- Footer Kartu: Info Lab & Waktu --}}
                        <div class="card-footer bg-white border-top p-4 p-md-5">

                            {{-- Info Ruangan --}}
                            <div class="mb-4">
                                <small class="text-muted d-block mb-1 text-uppercase"
                                    style="font-size: 11px; letter-spacing: 0.5px;">Laboratorium</small>
                                <h5 class="font-weight-bold text-dark mb-0">
                                    {{ $jadwal->lab->nama ?? 'Laboratorium Tidak Diketahui' }}</h5>
                            </div>

                            <hr class="my-4 border-light">

                            {{-- Info Waktu --}}
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Tanggal Pelaksanaan</small>
                                <span class="text-dark font-15 font-weight-bold"><i
                                        class="far fa-calendar-alt text-primary mr-1"></i> {{ $tglPrak }}</span>
                            </div>
                            <div>
                                <small class="text-muted d-block mb-1">Jam Pelaksanaan</small>
                                <span class="text-dark font-15 font-weight-bold"><i
                                        class="far fa-clock text-primary mr-1"></i> {{ $waktuPrak }}</span>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
