@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <h4 class="page-title text-dark text-uppercase font-weight-bold mb-1">Dashboard Super Admin</h4>
                    <p class="text-muted mb-0 font-14">Pusat Kendali Utama Sistem Manajemen Laboratorium. Pantau seluruh
                        aktivitas dan finalisasi jadwal dengan mudah.</p>
                </div>
            </div>
        </div>
        <div class="container-fluid pb-5">

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
                <div class="card border-right border-top-0 border-bottom-0 border-left-0">
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

                {{-- Antrean Aktivasi Akun --}}
                <div class="card border-0">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="text-danger mb-1 font-weight-medium">{{ $antrean_akun }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Menunggu Aktivasi</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-danger"><i data-feather="user-plus"
                                        style="width: 24px; height: 24px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL ANTREAN AKTIVASI AKUN PENGGUNA --}}
            @if ($antrean_akun > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                                    <div>
                                        <h4 class="card-title text-dark font-weight-bold m-0">
                                            <i data-feather="user-check" class="text-danger mr-2"
                                                style="width:18px; height:18px;"></i>
                                            Persetujuan Pendaftaran Akun Baru
                                        </h4>
                                        <p class="text-muted m-0 mt-1">Daftar pengguna yang baru melakukan registrasi dan
                                            membutuhkan aktivasi akun.</p>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="table-aktivasi" class="table table-hover table-borderless w-100 font-14">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-0 rounded-left">Nama Lengkap</th>
                                                <th class="border-0">NIP</th>
                                                <th class="border-0">Program Studi</th>
                                                <th class="border-0">Waktu Daftar</th>
                                                <th class="text-center border-0 rounded-right">Aksi</th>
                                            </tr>
                                        </thead>
                                        {{-- TBODY DIKOSONGKAN KARENA DIISI OLEH DATATABLES AJAX --}}
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- TABEL DATA VERIFIKASI TAHAP 2 --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                                <div>
                                    <h4 class="card-title text-dark font-weight-bold m-0">
                                        <i data-feather="check-circle" class="text-success mr-2"
                                            style="width:18px; height:18px;"></i>
                                        Finalisasi Jadwal (Tahap 2)
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
@endsection

@push('scripts')
    {{-- Memuat SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') feather.replace();

            $('#table-aktivasi').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.aktivasi.data') }}',
                order: [],
                columns: [{
                        data: 'nama_lengkap',
                        name: 'nama',
                        orderable: false
                    },
                    {
                        data: 'nip',
                        name: 'nip',
                        orderable: false
                    },
                    {
                        data: 'prodi_nama',
                        name: 'prodi.nama_prodi',
                        orderable: false
                    },
                    {
                        data: 'waktu_daftar',
                        name: 'created_at',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    "emptyTable": "Tidak ada antrean aktivasi akun."
                },
                drawCallback: function() {
                    if (typeof feather !== 'undefined') feather.replace();
                }
            });

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

        // Logika SweetAlert2 untuk Aktivasi Cepat di Dashboard
        function confirmActivation(id, nama) {
            Swal.fire({
                title: "Konfirmasi Aktivasi",
                html: "Apakah Anda yakin ingin mengaktifkan akun milik <b>" + nama + "</b>?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-sm btn-success px-4 mr-2 font-weight-medium',
                    cancelButton: 'btn btn-sm btn-secondary px-4 font-weight-medium'
                },
                confirmButtonText: '<i class="fas fa-check-circle mr-1"></i> Ya, Aktivasi!',
                cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-activate-' + id).submit();
                }
            });
        }
    </script>
@endpush
