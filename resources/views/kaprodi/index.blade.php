@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <h4 class="page-title text-dark text-uppercase font-weight-bold mb-1">Selamat Datang, {{ $namaTampil }}
                    </h4>
                    <p class="text-muted mb-0 font-14">Verifikasi Dokumen &
                        Kesesuaian Kurikulum Praktikum.</p>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            {{-- KARTU STATISTIK --}}
            <div class="card-group">

                {{-- Card 1: Total Pengajuan --}}
                <div class="card border-right">
                    <div class="card-body p-4">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $total }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Pengajuan</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-primary"><i data-feather="file-text"
                                        style="width: 24px; height: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Total Pengajuan Disetujui --}}
                <div class="card border-right">
                    <div class="card-body p-4">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $disetujui }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Disetujui</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-success"><i data-feather="check-circle"
                                        style="width: 24px; height: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Total Pengajuan Ditolak --}}
                <div class="card border-right">
                    <div class="card-body p-4">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $ditolak }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Ditolak</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-danger"><i data-feather="x-circle"
                                        style="width: 24px; height: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Total Pengajuan Menunggu --}}
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $menunggu }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Menunggu Verifikasi</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-info"><i data-feather="clock"
                                        style="width: 24px; height: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL PENGAJUAN --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                                <div>
                                    <h4 class="card-title text-dark font-weight-bold m-0"><i
                                            class="fas fa-check-square text-warning mr-2"></i>Verifikasi Pengajuan (Tahap 1)
                                    </h4>
                                    <p class="text-muted m-0 mt-1">Tinjau kesesuaian mata kuliah dan jobsheet yang diajukan
                                        oleh Dosen sebelum Anda teruskan ke Super Admin.</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="table-verif" class="table table-hover table-borderless w-100 font-14">
                                    <thead class="bg-light">
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
            box-shadow: 0 10px 30px rgba(251, 99, 64, 0.3) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #fb6340 0%, #fbb140 100%) !important;
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
