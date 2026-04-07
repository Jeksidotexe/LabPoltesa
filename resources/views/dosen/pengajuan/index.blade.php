@extends('layouts.master')
@section('title', 'Riwayat Pengajuan')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Pengajuan Praktikum Saya</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Riwayat Pengajuan</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <a href="{{ route('pengajuan.create') }}"
                            class="btn btn-sm btn-info border-0">
                            <i class="fas fa-plus"></i> Buat Pengajuan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-pengajuan" class="table table-striped table-bordered w-100">
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            $('#table-pengajuan').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('pengajuan.data') }}',
                order: [], // Mematikan sorting otomatis
                columns: [{
                        data: 'dosen_nama',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'lab_nama',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'makul_nama',
                        orderable: false,
                        searchable: false
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
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endpush
