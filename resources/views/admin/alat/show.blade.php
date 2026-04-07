@extends('layouts.master')
@section('title', 'Detail Data Alat')
@section('content')
    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0 text-dark font-weight-medium">Detail Alat Inventaris</h3>
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
                    <a href="{{ route('alat.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-body text-center">
                            @if ($alat->foto)
                                <img src="{{ Storage::url($alat->foto) }}" alt="{{ $alat->nama_alat }}"
                                    class="img-fluid rounded" style="max-height: 250px; width: 100%; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex flex-column align-items-center justify-content-center rounded"
                                    style="height: 250px; border: 2px dashed #ddd;">
                                    <i data-feather="camera-off" class="text-muted mb-2"
                                        style="width: 45px; height: 45px; opacity: 0.5;"></i>
                                    <span class="text-muted font-14">Tidak ada foto</span>
                                </div>
                            @endif

                            <h4 class="card-title mt-4 mb-1 text-dark">{{ $alat->nama_alat }}</h4>
                            <h6 class="card-subtitle text-muted mb-0">Kode Inventaris:
                                INV-{{ str_pad($alat->id_alat, 4, '0', STR_PAD_LEFT) }}</h6>
                        </div>

                        <div class="card-body border-top">
                            <div class="row text-center">
                                <div class="col-6 border-right">
                                    <p class="text-muted font-14 mb-1"><i data-feather="calendar" class="mr-1"
                                            style="width: 14px;"></i> Tahun Masuk</p>
                                    <h5 class="font-weight-medium text-dark mb-0">{{ $alat->tahun_pengadaan ?? '-' }}</h5>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted font-14 mb-1"><i data-feather="layers" class="mr-1"
                                            style="width: 14px;"></i> Total Unit</p>
                                    <h5 class="font-weight-medium text-dark mb-0">{{ $alat->jumlah ?? 0 }} Unit</h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-body border-top">
                            <a href="{{ route('alat.edit', $alat->id_alat) }}"
                                class="btn btn-sm btn-primary btn-block font-weight-medium">
                                <i data-feather="edit-2" class="mr-2" style="width: 16px;"></i> Edit Data Alat
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-md-12">

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-dark mb-4">
                                <i data-feather="map-pin" class="text-danger mr-2" style="width: 20px;"></i> Lokasi
                                Penyimpanan (Laboratorium)
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
                                    <h6 class="text-muted font-weight-normal mb-1">Status Operasional Lab</h6>
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
                                <i data-feather="cpu" class="text-primary mr-2" style="width: 20px;"></i> Spesifikasi Teknis
                            </h4>
                            <div class="bg-light p-3 rounded">
                                @if ($alat->spesifikasi_alat)
                                    <p class="text-dark mb-0" style="line-height: 1.7;">{!! nl2br(e($alat->spesifikasi_alat)) !!}</p>
                                @else
                                    <p class="text-muted font-italic mb-0">- Spesifikasi teknis belum ditambahkan -</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-dark mb-3">
                                <i data-feather="clipboard" class="text-success mr-2" style="width: 20px;"></i> Instruksi
                                Kerja / SOP Penggunaan
                            </h4>
                            <div class="bg-light p-3 rounded">
                                @if ($alat->instruksi_kerja)
                                    <p class="text-dark mb-0" style="line-height: 1.7;">{!! nl2br(e($alat->instruksi_kerja)) !!}</p>
                                @else
                                    <p class="text-muted font-italic mb-0">- Instruksi kerja / SOP belum ditambahkan -</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        feather.replace();
    </script>
@endpush
