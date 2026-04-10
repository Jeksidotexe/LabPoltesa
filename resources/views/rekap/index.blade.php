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
                    {{-- Tombol Cetak Diubah menjadi Button dengan ID --}}
                    <button type="button" id="btn-cetak" class="btn btn-sm btn-info">
                        <i class="fas fa-print mr-2"></i>Cetak Rekap
                    </button>
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
                    <div class="card shadow-sm border-0 rounded-lg">
                        <div class="card-body p-4">

                            <div
                                class="d-flex justify-content-between align-items-center flex-wrap mb-4 pb-3 border-bottom">
                                <div class="mb-2 mb-md-0">
                                    <h4 class="card-title text-dark font-weight-bold mb-1">
                                        <i data-feather="server" class="text-primary mr-2" style="width: 20px;"></i> Tabel
                                        Riwayat Penggunaan
                                    </h4>
                                </div>
                                <div style="min-width: 250px;">
                                    <select id="filter_prodi" class="form-control select2">
                                        <option value="">Semua Program Studi</option>
                                        @foreach ($prodi as $p)
                                            <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="table-rekap" class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr>
                                            {{-- Tambahan Kolom Checkbox Select All --}}
                                            <th class="text-center" style="width: 3%">
                                                <input type="checkbox" id="check-all"
                                                    style="cursor: pointer; width: 16px; height: 16px;">
                                            </th>
                                            <th class="text-center">No</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Laboratorium</th>
                                            <th>Mata Kuliah</th>
                                            <th>Prodi</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') feather.replace();

            if ($('.select2').length) {
                $('.select2').select2({
                    width: '100%',
                    theme: 'bootstrap-5'
                });
            }

            let table = $('#table-rekap').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('rekap.kegiatan.data') }}',
                    data: function(d) {
                        d.id_prodi = $('#filter_prodi').val();
                    }
                },
                order: [],
                columns: [{
                        data: 'checkbox', // Kolom Checkbox Baru
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
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
                        data: 'prodi_nama',
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
                ],
                drawCallback: function() {
                    if (typeof feather !== 'undefined') feather.replace();
                    // Reset check-all setiap kali tabel diganti halamannya
                    $('#check-all').prop('checked', false);
                }
            });

            // Reload DataTables saat dropdown filter diubah
            $('#filter_prodi').on('change', function() {
                table.ajax.reload();
            });

            // Logika Check All
            $('#check-all').on('click', function() {
                $('.row-checkbox').prop('checked', this.checked);
            });

            // Logika Tombol Cetak
            $('#btn-cetak').on('click', function() {
                let selectedIds = [];
                // Ambil semua checkbox yang dicentang
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                let url = '{{ route('rekap.cetak') }}';
                let params = [];

                // Jika ada yang dicentang, kirim ID-nya
                if (selectedIds.length > 0) {
                    params.push('ids=' + selectedIds.join(','));
                } else {
                    // Jika tidak ada yang dicentang, tapi ada filter prodi, kirim filternya
                    let idProdi = $('#filter_prodi').val();
                    if (idProdi) {
                        params.push('id_prodi=' + idProdi);
                    }
                }

                // Gabungkan URL dengan parameter
                if (params.length > 0) {
                    url += '?' + params.join('&');
                }

                // Buka tab baru untuk cetak
                window.open(url, '_blank');
            });
        });
    </script>
@endpush
