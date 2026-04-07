@extends('layouts.master')
@section('title', 'Dashboard Kaprodi')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid pt-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-gradient-warning border-0 shadow-lg overflow-hidden relative-card">
                        <div class="card-body p-4 p-md-5 text-white">
                            <div class="row align-items-center relative-z">
                                <div class="col-md-9">
                                    <h2 class="font-weight-bold text-white mb-2">Selamat Datang, Kaprodi!</h2>
                                    <p class="mb-0 text-white-50" style="font-size: 1.1rem;">Pusat Verifikasi Dokumen &
                                        Kesesuaian Kurikulum Praktikum. Mohon tinjau Jobsheet yang diajukan oleh dosen
                                        pengampu.</p>
                                </div>
                            </div>
                            <i data-feather="file-text" class="watermark-icon"></i>
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
                                            class="fas fa-check-square text-warning mr-2"></i>Verifikasi Pengajuan (Tahap 1)
                                    </h4>
                                    <p class="text-muted m-0 mt-1">Tinjau kesesuaian mata kuliah dan jobsheet yang diajukan
                                        oleh Dosen sebelum Anda teruskan ke Super Admin.</p>
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
