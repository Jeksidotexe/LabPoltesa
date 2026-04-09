@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <h4 class="page-title text-dark text-uppercase font-weight-bold mb-1">Dashboard Ketua Jurusan</h4>
                    <p class="text-muted mb-0 font-14">Portal Executive Summary & Monitoring Operasional Laboratorium secara komprehensif.</p>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            {{-- STATISTIK SINGKAT --}}
            <div class="card-group mb-4">
                <div class="card border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">{{ $total }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Pengajuan
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-info"><i data-feather="file-text"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $proses }}
                                </h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Sedang Diproses</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-warning"><i data-feather="clock"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $disetujui }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Telah Disetujui
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-success"><i data-feather="check-circle"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $ditolak }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Ditolak
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-danger"><i data-feather="x-circle"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL MONITORING --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                                <div>
                                    <h4 class="font-weight-bold text-dark mb-1">Monitoring Peminjaman</h4>
                                    <p class="text-muted font-13 mb-0">Pantau aktivitas pengajuan laboratorium oleh Dosen.
                                    </p>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="table-monitoring" class="table table-hover table-borderless w-100 font-14">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 5%" class="text-center rounded-left border-0">No</th>
                                            <th class="border-0">Dosen Pengaju</th>
                                            <th class="border-0">Laboratorium</th>
                                            <th class="border-0">Mata Kuliah</th>
                                            <th class="border-0">Tanggal</th>
                                            <th class="text-center border-0">Waktu</th>
                                            <th class="text-center border-0">Status</th>
                                            <th class="text-center rounded-right border-0"><i class="fas fa-cog"></i></th>
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
        .tracking-wide {
            letter-spacing: 0.5px;
        }

        .bg-gradient-purple {
            background: linear-gradient(135deg, #7460ee 0%, #5e4bcf 100%) !important;
        }

        .text-white-50 {
            color: rgba(255, 255, 255, 0.85) !important;
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
            z-index: 1;
        }

        .relative-z {
            position: relative;
            z-index: 2;
        }

        .watermark-icon {
            position: absolute;
            right: 2%;
            top: 50%;
            transform: translateY(-50%) rotate(-10deg);
            width: 160px;
            height: 160px;
            opacity: 0.1;
            color: white;
            z-index: 0;
            pointer-events: none;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') feather.replace();
            $('#table-monitoring').DataTable({
                responsive: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.pengajuan.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'dosen_nama'
                    },
                    {
                        data: 'lab_nama'
                    },
                    {
                        data: 'makul_nama'
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.jam_mulai + ' - ' + data.jam_selesai;
                        },
                        className: 'text-center'
                    },
                    {
                        data: 'status_badge',
                        className: 'text-center'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false,
                        className: 'text-center'
                    }
                ],
                drawCallback: function() {
                    if (typeof feather !== 'undefined') feather.replace();
                }
            });
        });
    </script>
@endpush
