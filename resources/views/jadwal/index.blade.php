@extends('layouts.master')
@section('title', 'Jadwal Praktikum')
@section('content')
    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-12 align-self-center mb-3 mb-md-0">
                    <h4 class="page-title text-truncate text-dark font-weight-bold mb-1">Jadwal Praktikum</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0 bg-transparent font-14">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Jadwal Praktikum</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid pt-2 pb-5">
            <div class="row">
                <div class="col-12">

                    <div class="alert alert-info py-3 px-4 d-flex align-items-center mb-4">
                        <i class="fas fa-calendar-check fa-2x mr-3 text-info"></i>
                        <span class="text-dark font-14">
                            <strong>Informasi:</strong> Tabel di bawah ini hanya menampilkan jadwal penggunaan laboratorium
                            yang telah disetujui secara final mulai dari <b>Hari ini</b> hingga waktu yang akan datang.
                        </span>
                    </div>

                    <div class="card">
                        <div class="card-body p-4">

                            {{-- HEADER KARTU & FILTER --}}
                            <div
                                class="d-flex justify-content-between align-items-center flex-wrap border-bottom pb-3 mb-4">
                                <div class="mb-2 mb-md-0">
                                    <h4 class="card-title font-weight-bold text-dark">Agenda Laboratorium</h4>
                                    <h6 class="card-subtitle text-muted mb-0">Daftar jadwal kegiatan praktikum aktif.</h6>
                                </div>
                                {{-- Dropdown Filter Laboratorium --}}
                                <div style="min-width: 250px;">
                                    <select id="filter_lab" class="form-control select2">
                                        <option value="">Semua Laboratorium</option>
                                        @foreach ($labs as $lab)
                                            <option value="{{ $lab->id_lab }}">{{ $lab->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="table-jadwal" class="table table-striped table-hover table-bordered w-100">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%" class="text-center">No</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Laboratorium</th>
                                            <th>Mata Kuliah</th>
                                            <th>Dosen Pengampu</th>
                                            <th style="width: 10%" class="text-center"><i
                                                    class="fas fa-cog"></i></th>
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
        $(document).ready(function() {
            // Init Select2
            $('.select2').select2({
                theme: "bootstrap-5",
                width: '100%'
            });

            let table = $('#table-jadwal').DataTable({
                responsive: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('jadwal.data') }}',
                    data: function(d) {
                        d.id_lab = $('#filter_lab').val(); // Kirim filter ke Controller
                    }
                },
                order: [],
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'waktu',
                        searchable: false,
                        sortable: false,
                    },
                    {
                        data: 'lab_nama',
                        name: 'lab.nama'
                    },
                    {
                        data: 'makul_nama',
                        name: 'makul.nama'
                    },
                    {
                        data: 'dosen_nama',
                        name: 'user.nama'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false,
                        className: 'text-center'
                    },
                ]
            });

            // Reload tabel saat dropdown lab diubah
            $('#filter_lab').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
