@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card bg-gradient-primary border-0 overflow-hidden relative-card">
                        <div class="card-body p-4 p-md-5 text-white">
                            <div class="row align-items-center relative-z">
                                <div class="col-md-8">
                                    <h2 class="font-weight-bold text-white mb-2">Selamat Datang, Super Admin!</h2>
                                    <p class="mb-0 text-white-50" style="font-size: 1.1rem;">Pusat Kendali Utama Sistem
                                        Informasi Manajemen Laboratorium (LabPoltesa). Pantau seluruh aktivitas dan
                                        finalisasi jadwal dengan mudah.</p>
                                </div>
                            </div>
                            <i data-feather="cpu" class="watermark-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU STATISTIK --}}
            <div class="card-group mb-4" style="border-radius: 10px; overflow: hidden;">

                {{-- Total Pengguna --}}
                <div class="card border-right border-top-0 border-bottom-0 border-left-0">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">{{ $total_pengguna }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Pengguna</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-primary"><i data-feather="users"
                                        style="width: 24px; height: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Dosen --}}
                <div class="card border-right border-top-0 border-bottom-0 border-left-0">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $total_dosen }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Dosen</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-success"><i data-feather="user-check"
                                        style="width: 24px; height: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Lab Aktif --}}
                <div class="card border-right border-top-0 border-bottom-0 border-left-0">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $lab_aktif }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Lab Aktif</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-info"><i data-feather="monitor"
                                        style="width: 24px; height: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Antrean Verifikasi --}}
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $antrean_verif }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Antrean Verifikasi</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-warning"><i data-feather="clock"
                                        style="width: 24px; height: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL DATA VERIFIKASI TAHAP 2 --}}
            <div class="row">
                <div class="col-12">
                    <div class="card border-0">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                                <div>
                                    <h4 class="card-title text-dark font-weight-bold m-0"><i
                                            class="fas fa-check-double text-success mr-2"></i>Finalisasi Jadwal (Tahap 2)
                                    </h4>
                                    <p class="text-muted m-0 mt-1">Verifikasi ketersediaan ruang Lab untuk memastikan tidak
                                        ada jadwal yang bentrok.</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="table-verif" class="table table-hover table-borderless w-100 font-14">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 rounded-left">Dosen Pengaju</th>
                                            <th class="border-0">Laboratorium</th>
                                            <th class="border-0">Mata Kuliah</th>
                                            <th class="border-0">Tanggal</th>
                                            <th class="text-center border-0">Mulai</th>
                                            <th class="text-center border-0">Selesai</th>
                                            <th class="text-center border-0">Status</th>
                                            <th class="text-center border-0 rounded-right">Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%) !important;
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
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .watermark-icon {
            position: absolute;
            right: 5%;
            top: 50%;
            transform: translateY(-50%) rotate(-15deg);
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

            let table = $('#table-verif').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.pengajuan.data') }}',
                order: [],
                columns: [{
                        data: 'dosen_nama',
                        orderable: false,
                    },
                    {
                        data: 'lab_nama',
                        orderable: false
                    },
                    {
                        data: 'makul_nama',
                        orderable: false
                    },
                    {
                        data: 'tanggal',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'jam_mulai',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'jam_selesai',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status_badge',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                ],
                drawCallback: function() {
                    if (typeof feather !== 'undefined') feather.replace();
                }
            });
        });
    </script>
@endpush
