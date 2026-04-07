@extends('layouts.master')
@section('title', 'Data Program Studi')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Data Program Studi</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Program Studi</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <a href="{{ route('prodi.create') }}"
                            class="btn btn-sm btn-info border-0">
                            <i class="fas fa-plus"></i> Tambah Prodi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Daftar Program Studi</h4>
                            <h6 class="card-subtitle mb-4">Kelola semua program studi.</h6>

                            <div class="table-responsive">
                                <table id="table-prodi" class="table table-striped table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th style="width: 15%">Kode Prodi</th>
                                            <th>Nama Program Studi</th>
                                            <th style="width: 15%" class="text-center">Akreditasi</th>
                                            <th style="width: 20%">Tanggal Berdiri</th>
                                            <th style="width: 10%" class="text-center"><i class="fas fa-cog"></i></th>
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
        $(function() {
            table = $('#table-prodi').DataTable({
                responsive: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route('prodi.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'kode'
                    },
                    {
                        data: 'nama_prodi'
                    },
                    {
                        data: 'akreditasi',
                        className: 'text-center'
                    },
                    {
                        data: 'tanggal_berdiri'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false,
                        className: 'text-center'
                    },
                ]
            });

            // Trigger SweetAlert2 untuk Session Success/Error
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
                title: 'Yakin ingin menghapus data ini?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fas fa-check-circle"></i> Ya, hapus!',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                customClass: {
                    confirmButton: 'btn btn-sm btn-danger me-2 mr-2',
                    cancelButton: 'btn btn-sm btn-secondary ms-2 ml-2',
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
