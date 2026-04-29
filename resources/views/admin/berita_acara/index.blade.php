@extends('layouts.master')
@section('title', 'Kelola Berita Acara')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb mb-3">
            <h4 class="page-title text-dark font-weight-bold mb-1">Kelola Berita Acara</h4>
            <p class="text-muted font-14">Daftar praktikum yang telah disetujui/selesai untuk dibuatkan Berita Acara.</p>
        </div>
        <div class="container-fluid pb-5">
            <div class="card">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="table-ba" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Tanggal</th>
                                    <th>Mata Kuliah</th>
                                    <th>Dosen</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="10%"><i class="fas fa-cog"></i></th>
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
    <script>
        $(document).ready(function() {
            $('#table-ba').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('berita-acara.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'makul'
                    },
                    {
                        data: 'dosen'
                    },
                    {
                        data: 'status_ba',
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
                ]
            });
        });
    </script>
@endpush
