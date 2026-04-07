@extends('layouts.master')
@section('title', 'Dashboard Kajur')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid pt-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-gradient-purple border-0 shadow-lg overflow-hidden relative-card">
                        <div class="card-body p-4 p-md-5 text-white">
                            <div class="row align-items-center relative-z">
                                <div class="col-md-9">
                                    <h2 class="font-weight-bold text-white mb-2">Selamat Datang, Ketua Jurusan!</h2>
                                    <p class="mb-0 text-white-50" style="font-size: 1.1rem;">Portal Executive Summary &
                                        Laporan Operasional Laboratorium secara komprehensif.</p>
                                </div>
                            </div>
                            <i data-feather="pie-chart" class="watermark-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body text-center py-5 px-4">
                            <div class="mb-4">
                                <div
                                    class="bg-light-purple text-purple rounded-circle d-inline-flex align-items-center justify-content-center p-4 shadow-sm">
                                    <i data-feather="activity" style="width: 50px; height: 50px;"></i>
                                </div>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-3">Pusat Monitoring Terpadu</h3>
                            <p class="text-muted mx-auto" style="font-size: 1.05rem; max-width: 700px; line-height: 1.7;">
                                Sebagai pimpinan, Anda akan memiliki akses penuh untuk meninjau statistik pemakaian lab,
                                evaluasi ketersediaan inventaris, dan mengekspor laporan kinerja seluruh program studi
                                secara *real-time*.
                            </p>

                            <div class="mt-5 pt-4 border-top w-75 mx-auto">
                                <button class="btn btn-outline-purple btn-lg px-5 shadow-sm disabled"
                                    style="cursor: not-allowed; opacity: 0.6;"
                                    title="Fitur pelaporan dalam tahap pengembangan">
                                    <i data-feather="printer" class="mr-2"></i> Export Rekap Kegiatan Lab (Coming Soon)
                                </button>
                            </div>
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
            box-shadow: 0 10px 30px rgba(116, 96, 238, 0.3) !important;
        }

        .bg-gradient-purple {
            background: linear-gradient(135deg, #7460ee 0%, #5e4bcf 100%) !important;
        }

        .bg-light-purple {
            background-color: rgba(116, 96, 238, 0.1) !important;
        }

        .text-purple {
            color: #7460ee !important;
        }

        .btn-outline-purple {
            color: #7460ee;
            border-color: #7460ee;
            background-color: transparent;
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
            transform: translateY(-50%) rotate(-10deg);
            width: 140px;
            height: 140px;
            opacity: 0.15;
            color: white;
            z-index: 1;
        }
    </style>
@endsection

@push('scripts')
    <script>
        feather.replace();
    </script>
@endpush
