@extends('layouts.master')
@section('title', 'Data Laboratorium')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Data Laboratorium</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Laboratorium</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <a href="{{ route('lab.create') }}" class="btn btn-sm btn-info border-0 shadow-sm">
                            <i class="fas fa-plus"></i> Tambah Laboratorium
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h4 class="card-title">Daftar Laboratorium</h4>
                            <h6 class="card-subtitle mb-4">Manajemen fasilitas laboratorium yang tersedia.</h6>

                            <div class="table-responsive">
                                <table id="table-lab" class="table table-striped table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th style="width: 10%" class="text-center">Foto</th>
                                            <th>Nama Laboratorium</th>
                                            <th style="width: 15%">Kode</th>
                                            <th style="width: 10%" class="text-center">Kapasitas</th>
                                            <th style="width: 15%" class="text-center">Status</th>
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
            table = $('#table-lab').DataTable({
                responsive: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route('lab.data') }}',
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
                        data: 'nama'
                    },
                    {
                        data: 'kode'
                    },
                    {
                        data: 'kapasitas',
                        render: function(data) {
                            return data + ' Orang';
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
                ]
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
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fas fa-check-circle"></i> Ya, hapus!',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                customClass: {
                    confirmButton: 'btn btn-danger me-2 mr-2',
                    cancelButton: 'btn btn-secondary ms-2 ml-2'
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
