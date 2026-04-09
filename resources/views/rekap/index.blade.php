@extends('layouts.master')
@section('title', 'Rekap Kegiatan Laboratorium')
@section('content')
    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0 text-dark font-weight-medium">Rekap Data Kegiatan Lab</h3>
                    <div class="d-flex align-items-center mt-1">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Rekap Kegiatan</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-md-6 col-4 align-self-center text-right">
                    <a href="{{ route('rekap.cetak') }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="fas fa-print mr-2"></i>Cetak Rekap
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid">

            <div class="card-group mb-4">
                <div class="card border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">{{ $totalKegiatan }}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Praktikum Disetujui
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-success"><i data-feather="check-circle"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">{{ $menungguVerifikasi }}
                                </h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Menunggu Verifikasi</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-warning"><i data-feather="clock"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $totalLab }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Laboratorium Terdaftar
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-info"><i data-feather="monitor"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-dark mb-4">
                                <i data-feather="server" class="text-primary mr-2" style="width: 20px;"></i> Tabel Riwayat
                                Penggunaan Laboratorium
                            </h4>

                            <div class="table-responsive">
                                <table id="table-rekap" class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Laboratorium</th>
                                            <th>Mata Kuliah</th>
                                            <th>Dosen Pengampu</th>
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
@endsection

@push('scripts')
    <script>
        feather.replace();
        $(function() {
            $('#table-rekap').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('rekap.kegiatan.data') }}',
                order: [],
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tanggal',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'waktu',
                        orderable: false,
                        searchable: false
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
                        data: 'dosen_nama',
                        orderable: false
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
        });
    </script>
@endpush
