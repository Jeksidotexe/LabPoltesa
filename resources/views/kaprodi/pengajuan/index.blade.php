@extends('layouts.master')
@section('title', 'Review Pengajuan')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <h4 class="page-title text-dark">Verifikasi Pengajuan (Tahap 1: Kaprodi)</h4>
            <p class="text-muted m-0">Tinjau kesesuaian mata kuliah dan jobsheet yang diajukan oleh Dosen.</p>
        </div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-verif" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Dosen Pengaju</th>
                                    <th>Laboratorium</th>
                                    <th>Mata Kuliah</th>
                                    <th>Jadwal Pelaksanaan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi Review</th>
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
        let table = $('#table-verif').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('pengajuan.data') }}',
            columns: [{
                    data: 'dosen',
                    className: 'font-weight-bold'
                },
                {
                    data: 'lab.nama'
                },
                {
                    data: 'makul.nama'
                },
                {
                    data: 'jadwal'
                },
                {
                    data: 'status_badge',
                    className: 'text-center'
                },
                {
                    data: 'aksi',
                    className: 'text-center'
                },
            ]
        });

        function verifyKaprodi(id) {
            Swal.fire({
                title: 'Tinjau Pengajuan',
                html: `
                <div class="text-left mt-3">
                    <label class="font-weight-bold">Keputusan Kaprodi:</label><br>
                    <div class="mb-2">
                        <input type="radio" id="terima" name="status_review" value="Terima" checked>
                        <label for="terima" class="text-success ml-1"><i class="fas fa-check"></i> Setujui & Teruskan ke Admin</label>
                    </div>
                    <div>
                        <input type="radio" id="tolak" name="status_review" value="Tolak">
                        <label for="tolak" class="text-danger ml-1"><i class="fas fa-times"></i> Tolak Pengajuan</label>
                    </div>

                    <textarea id="catatan" class="form-control mt-3" rows="3" placeholder="Opsional: Tulis alasan jika ditolak..."></textarea>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'Kirim Review',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    return {
                        status: document.querySelector('input[name="status_review"]:checked').value,
                        catatan: document.getElementById('catatan').value
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/pengajuan/verifikasi-kaprodi/${id}`, {
                        _token: '{{ csrf_token() }}',
                        status: result.value.status,
                        catatan: result.value.catatan
                    }).done(res => {
                        table.ajax.reload();
                        Swal.fire('Terkirim!', res.message, 'success');
                    }).fail(err => {
                        Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');
                    });
                }
            });
        }
    </script>
@endpush
