@extends('layouts.master')
@section('title', 'Detail Laboratorium')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb pt-4 pb-0">
            <div class="row align-items-center">
                <div class="col-md-7 col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0 bg-transparent font-13">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('lab.index') }}"
                                    class="text-muted">Laboratorium</a></li>
                            <li class="breadcrumb-item text-dark active" aria-current="page">{{ $lab->kode }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-5 col-12 mt-2 mt-md-0 text-md-right">
                    <a href="{{ route('lab.index') }}"
                        class="btn btn-sm btn-outline-secondary font-12 font-weight-medium mr-2">
                        <i class="fa fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <a href="{{ route('lab.edit', $lab->id_lab) }}"
                        class="btn btn-sm btn-primary font-12 font-weight-medium">
                        <i data-feather="edit-2" class="mr-1" style="width: 12px; height: 12px;"></i> Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid pb-5 pt-4">

            {{-- BAGIAN UTAMA: FOTO & INFORMASI KUNCI (SPLIT LAYOUT) --}}
            <div class="row mb-4">

                {{-- Sisi Kiri: Foto Lab --}}
                <div class="col-lg-6 col-md-12 mb-3 mb-lg-0">
                    <div class="bg-white p-2 border h-100">
                        @php
                            $urlFoto =
                                $lab->foto && Storage::disk('public')->exists($lab->foto)
                                    ? asset('storage/' . $lab->foto)
                                    : asset('images/default.jpg'); // Sesuaikan placeholder
                        @endphp
                        <img src="{{ $urlFoto }}" alt="Foto {{ $lab->nama }}" class="w-100 h-100"
                            style="object-fit: cover; min-height: 280px; max-height: 350px;">
                    </div>
                </div>

                {{-- Sisi Kanan: Informasi Kunci --}}
                <div class="col-lg-6 col-md-12 d-flex">
                    <div class="bg-white p-4 p-md-5 border w-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span
                                class="text-muted font-12 font-weight-medium tracking-wider text-uppercase">{{ $lab->kode }}</span>
                            @if ($lab->status == 'Aktif')
                                <span
                                    class="badge bg-light-success text-success font-11 font-weight-bold">Beroperasi</span>
                            @else
                                <span
                                    class="badge bg-light-danger text-danger font-11 font-weight-bold">Nonaktif</span>
                            @endif
                        </div>

                        <h2 class="font-weight-bold text-dark mb-4 pb-2 border-bottom border-light">{{ $lab->nama }}</h2>

                        <div class="row">
                            <div class="col-sm-6 mb-4">
                                <small class="text-muted d-block mb-1 font-weight-medium text-uppercase font-11">Lokasi
                                    Ruangan</small>
                                <div class="d-flex align-items-center text-dark">
                                    <i data-feather="map-pin" class="text-muted mr-2"
                                        style="width: 16px; height: 16px;"></i>
                                    <span class="font-weight-semibold font-14">{{ $lab->lokasi }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-4">
                                <small class="text-muted d-block mb-1 font-weight-medium text-uppercase font-11">Kapasitas
                                    Maksimal</small>
                                <div class="d-flex align-items-center text-dark">
                                    <i data-feather="users" class="text-muted mr-2" style="width: 16px; height: 16px;"></i>
                                    <span class="font-weight-semibold font-14">{{ $lab->kapasitas }} Orang</span>
                                </div>
                            </div>
                        </div>

                        {{-- Metadata kecil di bawah --}}
                        <div class="mt-4 pt-3 border-top border-light">
                            <small class="text-muted font-11">Terakhir diperbarui:
                                {{ $lab->updated_at ? $lab->updated_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BAGIAN BAWAH: DESKRIPSI & INVENTARIS --}}
            <div class="row">

                {{-- Kiri: Deskripsi Detail --}}
                <div class="col-lg-8 col-md-12 mb-4">
                    <div class="bg-white p-4 p-md-5 border h-100">
                        <h5 class="text-dark font-weight-bold mb-4">
                            <i data-feather="align-left" class="text-primary mr-2"
                                style="width: 18px; height: 18px; margin-top: -3px;"></i>Deskripsi
                        </h5>
                        <div class="text-dark font-14 lab-description"
                            style="line-height: 1.7; text-align: justify; color: #333;">
                            @if ($lab->deskripsi)
                                {!! nl2br(e($lab->deskripsi)) !!}
                            @else
                                <span class="text-muted font-italic font-13">Tidak ada deskripsi tambahan.</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kanan: Ringkasan Inventaris --}}
                <div class="col-lg-4 col-md-12 mb-4 d-flex">
                    <div class="bg-white p-4 border w-100 d-flex flex-column h-100">
                        <h5 class="text-dark font-weight-bold mb-4 pb-2 border-bottom border-light">
                            <i data-feather="package" class="text-info mr-2"
                                style="width: 18px; height: 18px; margin-top: -3px;"></i>Inventaris
                        </h5>

                        <div class="row flex-grow-1">
                            <div class="col-6 pr-2 mb-2 d-flex">
                                <div
                                    class="bg-light-flat w-100 p-3 text-center d-flex flex-column justify-content-center align-items-center">
                                    <i class="fas fa-tools text-muted mb-2 font-16"></i>
                                    <h2 class="m-0 font-weight-bold text-dark font-24">
                                        {{ $lab->alat ? $lab->alat->count() : 0 }}</h2>
                                    <small
                                        class="text-muted font-11 font-weight-medium text-uppercase mt-1 tracking-wider">Jenis
                                        Alat</small>
                                </div>
                            </div>
                            <div class="col-6 pl-2 mb-2 d-flex">
                                <div
                                    class="bg-light-flat w-100 p-3 text-center d-flex flex-column justify-content-center align-items-center">
                                    <i class="fas fa-cubes text-muted mb-2 font-16"></i>
                                    <h2 class="m-0 font-weight-bold text-dark font-24">
                                        {{ $lab->alat ? $lab->alat->sum('jumlah') : 0 }}</h2>
                                    <small
                                        class="text-muted font-11 font-weight-medium text-uppercase mt-1 tracking-wider">Total
                                        Unit</small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .border {
            border: 1px solid #e1e8ed !important;
        }

        .border-light {
            border-color: #f1f4f6 !important;
        }

        /* Tipografi & Spacing */
        .tracking-wider {
            letter-spacing: 0.8px;
        }

        .font-weight-semibold {
            font-weight: 600;
        }

        /* Tata letak tombol header yang clean */
        .btn-outline-secondary {
            color: #6c757d;
            border-color: #ced4da;
            background-color: transparent;
        }

        .btn-outline-secondary:hover {
            color: #333;
            background-color: #f8f9fa;
            border-color: #ced4da;
        }

        /* Warna Badges Flatter (Tanpa Gradien/Shadow) */
        .bg-light-success {
            background-color: #e6f7ed !important;
            border: 1px solid #c3eccd;
        }

        .text-success {
            color: #1e7e34 !important;
        }

        .bg-light-danger {
            background-color: #fdf2f2 !important;
            border: 1px solid #fbd5d5;
        }

        .text-danger {
            color: #c81e1e !important;
        }

        /* Latar belakang datar untuk info inventaris */
        .bg-light-flat {
            background-color: #f7f9fb;
            border: 1px solid #eef1f4;
        }

        /* Penyesuaian gambar agar rapi di mobile */
        @media (max-width: 991.98px) {
            .page-wrapper img {
                border-radius: 6px 6px 0 0 !important;
            }
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Memastikan feather icons dirender dengan benar
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endpush
