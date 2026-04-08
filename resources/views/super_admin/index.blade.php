@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid pt-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-gradient-primary border-0 shadow-lg overflow-hidden relative-card">
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

            <div class="row">
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="card border-0 card-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div
                                    class="bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center p-3">
                                    <i data-feather="users" class="feather-icon"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="font-weight-bold text-dark mb-0">#</h3>
                                    <span class="text-muted font-14">Total Pengguna</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="card border-0 card-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div
                                    class="bg-light-success text-success rounded-circle d-flex align-items-center justify-content-center p-3">
                                    <i data-feather="user-check" class="feather-icon"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="font-weight-bold text-dark mb-0">#</h3>
                                    <span class="text-muted font-14">Total Dosen</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="card border-0 card-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div
                                    class="bg-light-info text-info rounded-circle d-flex align-items-center justify-content-center p-3">
                                    <i data-feather="monitor" class="feather-icon"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="font-weight-bold text-dark mb-0">#</h3>
                                    <span class="text-muted font-14">Lab Aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="card border-0 card-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div
                                    class="bg-light-warning text-warning rounded-circle d-flex align-items-center justify-content-center p-3">
                                    <i data-feather="clock" class="feather-icon"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="font-weight-bold text-dark mb-0">#</h3>
                                    <span class="text-muted font-14">Antrean Verifikasi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
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
                                <table id="table-verif" class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr>
                                            <th>Dosen Pengaju</th>
                                            <th>Laboratorium</th>
                                            <th>Mata Kuliah</th>
                                            <th>Tanggal</th>
                                            <th class="text-center">Mulai</th>
                                            <th class="text-center">Selesai</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
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
        .shadow-sm {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
        }

        .shadow-lg {
            box-shadow: 0 10px 30px rgba(94, 114, 228, 0.2) !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%) !important;
        }

        .bg-light-primary {
            background-color: rgba(94, 114, 228, 0.1) !important;
        }

        .bg-light-success {
            background-color: rgba(45, 206, 137, 0.1) !important;
        }

        .bg-light-info {
            background-color: rgba(17, 205, 239, 0.1) !important;
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
            z-index: 1;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08) !important;
        }
    </style>
@endsection

@push('scripts')
    <script>
        feather.replace();
        let table = $('#table-verif').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('dashboard.pengajuan.data') }}',
            order: [],
            columns: [{
                    data: 'dosen_nama',
                    orderable: false
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
            ]
        });
    </script>
@endpush
