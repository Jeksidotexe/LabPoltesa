@extends('layouts.master')
@section('title', 'Dashboard Admin')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid pt-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-gradient-cyan border-0 shadow-lg overflow-hidden relative-card">
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

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm card-hover h-100">
                        <div class="card-body text-center p-4">
                            <div class="bg-light-primary text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 70px; height: 70px;">
                                <i data-feather="box" style="width: 30px; height: 30px;"></i>
                            </div>
                            <h4 class="text-muted font-weight-normal mb-1">Total Jenis Alat</h4>
                            <h1 class="text-dark font-weight-bold mb-0">#</h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm card-hover h-100">
                        <div class="card-body text-center p-4">
                            <div class="bg-light-success text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 70px; height: 70px;">
                                <i data-feather="layers" style="width: 30px; height: 30px;"></i>
                            </div>
                            <h4 class="text-muted font-weight-normal mb-1">Total Unit Barang</h4>
                            <h1 class="text-dark font-weight-bold mb-0">#</h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm card-hover h-100">
                        <div class="card-body text-center p-4">
                            <div class="bg-light-warning text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 70px; height: 70px;">
                                <i data-feather="file-text" style="width: 30px; height: 30px;"></i>
                            </div>
                            <h4 class="text-muted font-weight-normal mb-1">Berita Acara Selesai</h4>
                            <h1 class="text-dark font-weight-bold mb-0">#</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="mb-3"><i data-feather="database" class="text-muted"
                                    style="width: 60px; height: 60px; opacity: 0.5;"></i></div>
                            <h4 class="font-weight-bold text-dark">Pendataan Inventaris Aktif</h4>
                            <p class="text-muted mx-auto mb-4" style="max-width: 600px;">Gunakan menu Data Alat untuk
                                menambah spesifikasi mesin dan SOP penggunaan terbaru.</p>
                            <a href="{{ route('alat.index') }}"
                                class="btn btn-cyan text-white btn-lg px-5 shadow-sm btn-hover">
                                <i data-feather="arrow-right-circle" class="mr-2"></i>Kelola Data Alat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .shadow-sm {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
        }

        .shadow-lg {
            box-shadow: 0 10px 30px rgba(34, 198, 171, 0.3) !important;
        }

        .bg-gradient-cyan {
            background: linear-gradient(135deg, #22c6ab 0%, #17a2b8 100%) !important;
        }

        .btn-cyan {
            background-color: #22c6ab;
            border: none;
        }

        .bg-light-primary {
            background-color: rgba(94, 114, 228, 0.1) !important;
        }

        .bg-light-success {
            background-color: rgba(45, 206, 137, 0.1) !important;
        }

        .bg-light-warning {
            background-color: rgba(251, 99, 64, 0.1) !important;
        }

        .relative-card {
            position: relative;
            overflow: hidden;
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
            z-index: 1;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08) !important;
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(34, 198, 171, 0.3) !important;
        }
    </style>
@endsection

@push('scripts')
    <script>
        feather.replace();
    </script>
@endpush
