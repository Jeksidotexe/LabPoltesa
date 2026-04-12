@extends('layouts.master')
@section('title', 'Detail Data Alat')
@section('content')

    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0 text-dark font-weight-medium">Detail Alat</h3>
                    <div class="d-flex align-items-center mt-1">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('alat.index') }}" class="text-muted">Data
                                        Alat</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Detail</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-md-6 col-4 align-self-center text-right">
                    <a href="{{ route('alat.index') }}" class="btn btn-sm btn-secondary font-weight-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            {{-- PENAMBAHAN: d-flex align-items-stretch pada row --}}
            <div class="row d-flex align-items-stretch">

                {{-- KOLOM KIRI --}}
                {{-- PENAMBAHAN: d-flex flex-column agar card di dalamnya bisa merenggang --}}
                <div class="col-lg-4 col-md-12 d-flex flex-column mb-4">
                    {{-- PENAMBAHAN: flex-grow-1 d-flex flex-column pada card --}}
                    <div class="card flex-grow-1 d-flex flex-column mb-0">
                        <div class="card-body text-center">
                            <img src="{{ $urlFoto }}" alt="{{ $alat->nama_alat }}" class="img-fluid border shadow-sm"
                                style="max-height: 250px; width: 100%; object-fit: cover;">
                            <h4 class="card-title mt-4 mb-1 text-dark">{{ $alat->nama_alat }}</h4>
                        </div>

                        {{-- Baris Info Total & Tahun --}}
                        <div class="card-body border-top">
                            <div class="row text-center">
                                <div class="col-6 border-right">
                                    <p class="text-muted font-14 mb-1"><i data-feather="calendar" class="mr-1"
                                            style="width: 14px;"></i> Tahun Pengadaan</p>
                                    <h5 class="font-weight-medium text-dark mb-0">{{ $alat->tahun_pengadaan ?? '-' }}</h5>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted font-14 mb-1"><i data-feather="layers" class="mr-1"
                                            style="width: 14px;"></i> Total Unit Fisik</p>
                                    <h5 class="font-weight-medium text-dark mb-0">{{ $totalFisik }} Unit</h5>
                                </div>
                            </div>
                        </div>

                        {{-- Baris Info Rincian Kondisi Alat --}}
                        <div class="card-body border-top p-4">
                            <h6 class="text-muted font-weight-bold mb-4 text-center text-uppercase font-11 tracking-wide">
                                <i class="fas fa-chart-pie mr-1 text-primary"></i> Rincian Ketersediaan
                            </h6>
                            <div class="row text-center mx-0">
                                <div class="col-6 px-2 mb-3">
                                    <div class="border rounded p-3 h-100 transition-hover bg-white">
                                        <i class="fas fa-check-circle text-success mb-2 font-16"></i>
                                        <h4 class="font-weight-bold text-dark mb-0">{{ $alat->jumlah }}</h4>
                                        <span class="font-12 text-muted">Tersedia</span>
                                    </div>
                                </div>
                                <div class="col-6 px-2 mb-3">
                                    <div class="border rounded p-3 h-100 transition-hover bg-white">
                                        <i class="fas fa-hand-holding text-warning mb-2 font-16"></i>
                                        <h4 class="font-weight-bold text-dark mb-0">{{ $dipinjam }}</h4>
                                        <span class="font-12 text-muted">Dipinjam</span>
                                    </div>
                                </div>
                                <div class="col-6 px-2">
                                    <div class="border rounded p-3 h-100 transition-hover bg-white">
                                        <i class="fa fa-wrench text-secondary mb-2 font-16"></i>
                                        <h4 class="font-weight-bold text-dark mb-0">{{ $alat->jumlah_rusak_ringan ?? '-' }}
                                        </h4>
                                        <span class="font-12 text-muted">Rusak Ringan</span>
                                    </div>
                                </div>
                                <div class="col-6 px-2">
                                    <div class="border rounded p-3 h-100 transition-hover bg-white">
                                        <i class="fa fa-times-circle text-danger mb-2 font-16"></i>
                                        <h4 class="font-weight-bold text-dark mb-0">{{ $alat->jumlah_rusak_berat ?? '-' }}
                                        </h4>
                                        <span class="font-12 text-muted">Rusak Berat</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body border-top p-4 mt-auto">
                            {{-- TOMBOL : Catat Perbaikan (Hanya muncul jika ada alat rusak) --}}
                            @if ($alat->jumlah_rusak_ringan > 0 || $alat->jumlah_rusak_berat > 0)
                                <button type="button"
                                    class="btn btn-sm btn-dark btn-block font-weight-medium mb-2"
                                    data-toggle="modal" data-target="#modalPerbaikan">
                                    <i class="fa fa-wrench mr-2"></i> Catat Perbaikan Alat
                                </button>
                            @endif

                            <a href="{{ route('alat.edit', $alat->id_alat) }}"
                                class="btn btn-sm btn-primary btn-block font-weight-medium">
                                <i class="fa fa-edit mr-2"></i> Edit Data Alat
                            </a>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                {{-- PENAMBAHAN: d-flex flex-column --}}
                <div class="col-lg-8 col-md-12 d-flex flex-column mb-4">

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-dark mb-4">
                                <i data-feather="map-pin" class="text-danger mr-2" style="width: 20px;"></i> Lokasi
                                Penyimpanan
                            </h4>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted font-weight-normal mb-1">Nama Laboratorium</h6>
                                    <h5 class="font-weight-medium text-dark">
                                        {{ $alat->lab ? $alat->lab->nama : 'Belum Ditetapkan' }}</h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted font-weight-normal mb-1">Kode Ruangan</h6>
                                    <h5 class="font-weight-medium text-dark">{{ $alat->lab->kode ?? '-' }}</h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted font-weight-normal mb-1">Kapasitas Laboratorium</h6>
                                    <h5 class="font-weight-medium text-dark">{{ $alat->lab->kapasitas ?? '-' }} <span
                                            class="font-14 text-muted font-weight-normal">Orang</span></h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted font-weight-normal mb-1">Status Operasional Laboratorium</h6>
                                    @if ($alat->lab && $alat->lab->status == 'Aktif')
                                        <span class="badge badge-success px-3 py-1 font-12">Aktif</span>
                                    @else
                                        <span class="badge badge-danger px-3 py-1 font-12">Tidak Aktif</span>
                                    @endif
                                </div>
                                <div class="col-md-12 mt-2">
                                    <h6 class="text-muted font-weight-normal mb-1">Lokasi Detail</h6>
                                    <p class="text-dark">{{ $alat->lab->lokasi ?? 'Lokasi detail tidak diketahui' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-dark mb-3">
                                <i data-feather="cpu" class="text-primary mr-2" style="width: 20px;"></i> Spesifikasi
                                Teknis
                            </h4>
                            <div class="font-14">
                                @if ($alat->spesifikasi_alat)
                                    <p class="text-dark mb-0" style="line-height: 1.7;">{!! nl2br(e($alat->spesifikasi_alat)) !!}</p>
                                @else
                                    <p class="text-muted font-italic mb-0">- Spesifikasi teknis belum ditambahkan -</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- PENAMBAHAN: flex-grow-1 dan hapus mb-4 (ganti mb-0) agar card ini memanjang menyentuh bawah --}}
                    <div class="card flex-grow-1 mb-0">
                        <div class="card-body">
                            <h4 class="card-title text-dark mb-3">
                                <i data-feather="clipboard" class="text-success mr-2" style="width: 20px;"></i> Instruksi
                                Kerja
                            </h4>
                            <div class="font-14">
                                @if ($alat->instruksi_kerja)
                                    <p class="text-dark mb-0" style="line-height: 1.7;">{!! nl2br(e($alat->instruksi_kerja)) !!}</p>
                                @else
                                    <p class="text-muted font-italic mb-0">- Instruksi kerja belum ditambahkan -</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- INCLUDE MODAL PERBAIKAN ALAT --}}
    @include('admin.alat.form')
@endsection

@push('scripts')
    <script>
        feather.replace();
    </script>
@endpush
