@extends('layouts.master')

@section('title', 'Data Dosen')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Data Dosen</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item text-muted active" aria-current="page">Dosen</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <a href="{{ route('dosen.create') }}"
                            class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Tambah Dosen
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
                            <h4 class="card-title">Daftar Dosen</h4>
                            <h6 class="card-subtitle mb-4">Kelola semua data profil dosen yang terdaftar.</h6>

                            <div class="table-responsive">
                                <table id="table-dosen" class="table table-striped table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th style="width: 5%">Foto</th>
                                            <th>NIP</th>
                                            <th>Nama Lengkap</th>
                                            <th>Program Studi</th>
                                            <th>Jabatan</th>
                                            <th>Status</th>
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
            table = $('#table-dosen').DataTable({
                responsive: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route('dosen.data') }}',
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
                        data: 'nip'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'nama_prodi'
                    },
                    {
                        data: 'jabatan'
                    },
                    {
                        data: 'status',
                        render: function(data) {
                            if (data === 'Aktif')
                            return '<span class="badge bg-success text-white px-2 py-1">Aktif</span>';
                            return '<span class="badge bg-danger text-white px-2 py-1">Nonaktif</span>';
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
                text: "Data dosen beserta akun loginnya akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fas fa-check-circle"></i> Ya, hapus!',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                customClass: {
                    confirmButton: 'btn btn-sm btn-danger me-2 mr-2',
                    cancelButton: 'btn btn-sm btn-secondary ms-2 ml-2'
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
