{{-- ================================================= --}}
{{-- MODAL 1: SETUJUI PENGAJUAN --}}
{{-- ================================================= --}}
<div class="modal fade" id="modalTerima" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-success font-weight-bold">
                    <i class="fas fa-check-circle mr-2"></i> Konfirmasi Persetujuan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted font-14 mb-4">
                    Anda akan menyetujui pengajuan praktikum <strong>{{ $nomorReg }}</strong>.
                    {{ $canReviewAdmin ? 'Jadwal akan dikunci dan disetujui secara final.' : 'Pengajuan akan diteruskan ke Super Admin untuk finalisasi jadwal.' }}
                </p>

                <div class="form-group mb-0">
                    <label class="text-dark font-weight-bold font-13">Catatan (Opsional)</label>
                    <textarea id="catatan_terima" class="form-control" rows="3"
                        placeholder="Tambahkan pesan persetujuan jika perlu..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button type="button" onclick="submitReview('Terima', '{{ $roleTipe }}')"
                    class="btn btn-sm btn-success px-4 font-weight-bold" id="btn-terima">
                    <i class="fas fa-check mr-1"></i> Ya, Setujui Pengajuan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================================================= --}}
{{-- MODAL 2: TOLAK / REVISI PENGAJUAN --}}
{{-- ================================================= --}}
<div class="modal fade" id="modalTolak" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-danger font-weight-bold">
                    <i class="fas fa-times-circle mr-2"></i> Tolak Pengajuan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted font-14 mb-4">
                    Anda akan menolak pengajuan <strong>{{ $nomorReg }}</strong>. Dosen yang bersangkutan akan
                    diminta untuk membuat pengajuan ulang sesuai dengan catatan evaluasi.
                </p>

                <div class="form-group mb-0">
                    <label class="text-dark font-weight-bold font-13">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea id="catatan_tolak" class="form-control" rows="4"
                        placeholder="Jelaskan alasan mengapa pengajuan ini ditolak..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button type="button" onclick="submitReview('Tolak', '{{ $roleTipe }}')"
                    class="btn btn-sm btn-danger px-4 font-weight-bold" id="btn-tolak">
                    <i class="fas fa-ban mr-1"></i> Ya, Tolak Pengajuan
                </button>
            </div>
        </div>
    </div>
</div>
