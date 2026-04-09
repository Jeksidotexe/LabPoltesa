@extends('layouts.master')
@section('title', 'Data Alat Laboratorium')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb pt-3 pb-3">
            <div class="row align-items-center">
                {{-- Bagian Kiri: Judul & Breadcrumb --}}
                <div class="col-md-5 col-12 align-self-center mb-3 mb-md-0">
                    <h4 class="page-title text-truncate text-dark font-weight-bold mb-1">Data Alat Laboratorium</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Alat Lab</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                {{-- Bagian Kanan: Filter & Tombol Tambah --}}
                <div class="col-md-7 col-12 align-self-center d-flex justify-content-md-end justify-content-start">
                    <div class="d-flex align-items-center w-100 justify-content-md-end">

                        {{-- Dropdown Filter --}}
                        <div class="mr-2" style="min-width: 200px; max-width: 220px; flex-grow: 1;">
                            <select id="filter_lab" class="form-control select2 custom-shadow">
                                <option value="">Semua Laboratorium</option>
                                @foreach ($lab as $l)
                                    <option value="{{ $l->id_lab }}">{{ $l->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Tambah --}}
                        <a href="{{ route('alat.create') }}"
                            class="btn btn-sm btn-info d-flex align-items-center">
                            <i class="fas fa-plus mr-1"></i> Tambah
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid pt-2">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-lg">
                        <div class="card-body p-4">
                            <div class="mb-4 pb-3 border-bottom">
                                <h4 class="card-title font-weight-bold text-dark mb-1">Daftar Alat Laboratorium</h4>
                                <h6 class="card-subtitle text-muted mb-0">Manajemen peralatan yang tersedia di dalam seluruh
                                    laboratorium.</h6>
                            </div>

                            <div class="table-responsive">
                                <table id="table-alat" class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%" class="text-center">No</th>
                                            <th style="width: 10%" class="text-center">Foto</th>
                                            <th>Nama Alat</th>
                                            <th>Spesifikasi</th>
                                            <th>Lokasi Lab</th>
                                            <th style="width: 10%" class="text-center">Tahun</th>
                                            <th style="width: 10%" class="text-center">Jumlah</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let table;
        $(document).ready(function() {
            // Inisialisasi Select2
            if ($('.select2').length) {
                $('.select2').select2({
                    width: '100%',
                    theme: 'bootstrap-5'
                });
            }

            table = $('#table-alat').DataTable({
                responsive: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('alat.data') }}',
                    data: function(d) {
                        // Kirim parameter filter_lab ke controller
                        d.id_lab = $('#filter_lab').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'foto',
                        searchable: false,
                        sortable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama_alat',
                    },
                    {
                        data: 'spesifikasi_alat'
                    },
                    {
                        data: 'nama_lab'
                    },
                    {
                        data: 'tahun_pengadaan',
                        className: 'text-center'
                    },
                    {
                        data: 'jumlah',
                        className: 'text-center',
                        render: function(data) {
                            return '<span class="badge bg-secondary text-white px-3 py-1 border">' +
                                data + '</span>';
                        }
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false,
                        className: 'text-center'
                    },
                ]
            });

            // Reload DataTables saat dropdown filter diubah
            $('#filter_lab').on('change', function() {
                table.ajax.reload();
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}'
                });
            @endif
        });

        function deleteData(url) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data alat yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fas fa-check-circle"></i> Ya, hapus!',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                customClass: {
                    confirmButton: 'btn btn-sm btn-danger me-2',
                    cancelButton: 'btn btn-sm btn-secondary ms-2',
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                            '_token': '{{ csrf_token() }}',
                            '_method': 'delete'
                        })
                        .done((response) => {
                            table.ajax.reload();
                            Swal.fire({
                                title: 'Terhapus!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        })
                        .fail((jqXHR) => {
                            let errorMessage = 'Tidak dapat menghapus data.';
                            if (jqXHR.responseJSON && jqXHR.responseJSON.message) errorMessage = jqXHR
                                .responseJSON.message;
                            Swal.fire({
                                title: 'Gagal!',
                                text: errorMessage,
                                icon: 'error'
                            });
                        });
                }
            });
        }
    </script>
@endpush
