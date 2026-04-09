@extends('layouts.master')
@section('title', 'Dashboard Admin')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card bg-gradient-cyan border-0 overflow-hidden relative-card">
                        <div class="card-body p-4 p-md-5 text-white">
                            <div class="row align-items-center relative-z">
                                <div class="col-md-9">
                                    <h2 class="font-weight-bold text-white mb-2">Selamat Datang, Admin Laboratorium!</h2>
                                    <p class="mb-0 text-white-50" style="font-size: 1.1rem;">Pusat Pengelolaan Inventaris &
                                        Operasional Lab. Pastikan seluruh peralatan tercatat dan terpelihara dengan baik.
                                    </p>
                                </div>
                            </div>
                            <i data-feather="box" class="watermark-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU STATISTIK (DESAIN CARD-GROUP) --}}
            <div class="card-group mb-4" style="border-radius: 10px; overflow: hidden;">

                {{-- Card 1: Total Jenis Alat --}}
                <div class="card border-right border-top-0 border-bottom-0 border-left-0">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $total_jenis_alat }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Jenis Alat</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-primary"><i data-feather="box"
                                        style="width: 24px; height: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Total Unit Barang --}}
                <div class="card border-right border-top-0 border-bottom-0 border-left-0">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $total_unit_barang }}
                                </h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Unit Barang</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-success"><i data-feather="layers"
                                        style="width: 24px; height: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Berita Acara Selesai --}}
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $berita_acara }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Berita Acara Selesai</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-warning"><i data-feather="file-text"
                                        style="width: 24px; height: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MENU PINTASAN --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <div
                                    class="rounded-circle d-inline-flex justify-content-center align-items-center p-4">
                                    <i data-feather="database" class="text-info" style="width: 40px; height: 40px;"></i>
                                </div>
                            </div>
                            <h4 class="font-weight-bold text-dark">Pendataan Inventaris Aktif</h4>
                            <p class="text-muted mx-auto mb-4" style="max-width: 600px;">Gunakan menu Data Alat untuk
                                menambah spesifikasi mesin dan SOP penggunaan terbaru.</p>
                            <a href="{{ route('alat.index') }}"
                                class="btn btn-sm btn-info px-5 font-weight-medium">
                                <i data-feather="arrow-right-circle" class="mr-2" style="width: 18px;"></i> Kelola Data Alat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-cyan {
            background: linear-gradient(135deg, #22c6ab 0%, #17a2b8 100%) !important;
        }

        .relative-card {
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .relative-z {
            position: relative;
            z-index: 2;
        }

        .text-white-50 {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        .watermark-icon {
            position: absolute;
            right: 5%;
            top: 50%;
            transform: translateY(-50%) rotate(15deg);
            width: 140px;
            height: 140px;
            opacity: 0.15;
            color: white;
            z-index: 0;
            pointer-events: none;
        }

        /* Penyesuaian desain Card Group */
        .card-group {
            border-radius: 10px;
        }

        .border-right {
            border-right: 1px solid rgba(0, 0, 0, .08) !important;
        }

        .opacity-7 {
            opacity: 0.7;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') feather.replace();
        });
    </script>
@endpush
