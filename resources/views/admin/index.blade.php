@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <h4 class="page-title text-dark text-uppercase font-weight-bold mb-1">Dashboard Admin Laboratorium</h4>
                    <p class="text-muted mb-0 font-14">Pusat Pengelolaan Inventaris & Monitoring Operasional Laboratorium</p>
                </div>
            </div>
        </div>

        <div class="container-fluid">

            {{-- KARTU STATISTIK --}}
            <div class="card-group">

                {{-- Card 1: Total Jenis Alat --}}
                <div class="card border-right">
                    <div class="card-body p-4">
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
                <div class="card border-right">
                    <div class="card-body p-4">
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
                <div class="card">
                    <div class="card-body p-4">
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

            {{-- TABEL MONITORING --}}
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
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

            {{-- MENU PINTASAN --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <div
                                    class="bg-light rounded-circle d-inline-flex justify-content-center align-items-center p-4">
                                    <i data-feather="database" class="text-info" style="width: 40px; height: 40px;"></i>
                                </div>
                            </div>
                            <h4 class="font-weight-bold text-dark">Pendataan Inventaris Aktif</h4>
                            <p class="text-muted mx-auto mb-4" style="max-width: 600px;">Gunakan menu Data Alat untuk
                                menambah spesifikasi mesin dan SOP penggunaan terbaru.</p>
                            <a href="{{ route('alat.index') }}"
                                class="btn btn-sm btn-info px-5 font-weight-medium">
                                <i data-feather="arrow-right-circle" class="mr-2" style="width: 18px;"></i> Kelola Data
                                Alat
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
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
                        data: 'dosen_nama',
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
