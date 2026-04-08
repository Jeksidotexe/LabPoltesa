@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid pt-4">
            {{-- CARD WELCOME DOSEN --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-gradient-info border-0 shadow-lg overflow-hidden relative-card">
                        <div class="card-body p-4 p-md-5 text-white">
                            <div class="row align-items-center relative-z">
                                <div class="col-md-12">
                                    @php
                                        // Logika Cerdas Pengambilan Profil
                                        $user = auth()->user();
                                        $namaTampil = $user->dosen?->nama ?? $user->username;
                                    @endphp
                                    <h2 class="font-weight-bold text-white mb-2">Halo, {{ $namaTampil }}! 👋
                                    </h2>
                                    <p class="mb-0 text-white-50" style="font-size: 1.1rem;">Selamat datang di Portal
                                        Pengajuan Praktikum. Pantau status pengajuan Anda atau jadwalkan praktikum baru di
                                        sini.</p>
                                </div>
                            </div>
                            <i data-feather="calendar" class="watermark-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD TABEL RIWAYAT PENGAJUAN --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            {{-- Container Flex untuk Judul Tabel dan Tombol Tambah --}}
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title text-dark font-weight-bold m-0">
                                    <i class="fas fa-history text-info mr-2"></i>Riwayat Pengajuan Praktikum Anda
                                </h4>
                                <a href="{{ route('pengajuan.create') }}" class="btn btn-sm btn-info shadow-sm">
                                    <i class="fas fa-plus"></i> Ajukan Praktikum
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table id="table-pengajuan" class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
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
            box-shadow: 0 10px 30px rgba(17, 205, 239, 0.3) !important;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #11cdef 0%, #1171ef 100%) !important;
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

        .btn-white {
            background-color: #fff;
            color: #11cdef;
            transition: all 0.3s;
        }

        .btn-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@push('scripts')
    <script>
        feather.replace();
        $(function() {
            $('#table-pengajuan').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.pengajuan.data') }}',
                order: [],
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'text-center',
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
                language: {
                    emptyTable: "Anda belum memiliki riwayat pengajuan praktikum."
                }
            });
        });
    </script>
@endpush
