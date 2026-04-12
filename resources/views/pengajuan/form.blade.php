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
                    <label class="text-dark font-weight-bold font-13">Alasan Penolakan <span
                            class="text-danger">*</span></label>
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

{{-- ================================================= --}}
{{-- MODAL 3: PENGEMBALIAN ALAT (KHUSUS ADMIN) --}}
{{-- ================================================= --}}
@if (isset($canReturn) && $canReturn)
    <div class="modal fade" id="modalKembali" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-sm" style="border-radius: 12px;">
                <div class="modal-header bg-white border-bottom p-4">
                    <h5 class="modal-title font-weight-bold text-dark">
                        Validasi Pengembalian Alat
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <p class="text-muted font-14 mb-4">
                        Silakan masukkan rincian kondisi alat saat dikembalikan. Pastikan total pengembalian sesuai
                        dengan jumlah alat yang dipinjam.
                    </p>

                    <form id="form-pengembalian">
                        @if ($pengajuan->alat->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-borderless font-14">
                                    <thead class="bg-dark text-white align-middle">
                                        <tr>
                                            <th class="font-weight-medium" style="width: 40%;">Nama Alat</th>
                                            <th class="text-center font-weight-medium">Dipinjam</th>
                                            <th class="text-center font-weight-medium" style="width: 12%">Baik</th>
                                            <th class="text-center font-weight-medium" style="width: 12%">Rusak Ringan
                                            </th>
                                            <th class="text-center font-weight-medium" style="width: 12%">Rusak Berat
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pengajuan->alat as $item)
                                            <tr class="row-alat border-bottom"
                                                data-pinjam="{{ $item->pivot->jumlah_pinjam }}">
                                                <td class="font-weight-medium text-dark">
                                                    {{ $item->nama_alat }}
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge badge-warning border px-3 py-2 text-16">{{ $item->pivot->jumlah_pinjam }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" name="jml_baik[{{ $item->id_alat }}]"
                                                        class="form-control form-control-sm text-center input-kondisi"
                                                        min="0" max="{{ $item->pivot->jumlah_pinjam }}"
                                                        value="{{ $item->pivot->jumlah_pinjam }}" required>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" name="jml_ringan[{{ $item->id_alat }}]"
                                                        class="form-control form-control-sm text-center input-kondisi"
                                                        min="0" max="{{ $item->pivot->jumlah_pinjam }}"
                                                        value="0" required>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" name="jml_berat[{{ $item->id_alat }}]"
                                                        class="form-control form-control-sm text-center input-kondisi"
                                                        min="0" max="{{ $item->pivot->jumlah_pinjam }}"
                                                        value="0" required>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pesan Error Validasi (Muncul jika jumlah tidak sesuai) --}}
                            <div id="error-pengembalian" class="mt-3 text-danger font-13 d-none">
                                <i class="fas fa-exclamation-circle mr-1"></i> Total alat yang dikembalikan tidak
                                sesuai dengan jumlah yang dipinjam.
                            </div>
                        @else
                            <div class="alert alert-light border font-14 m-0 text-center text-muted">
                                Tidak ada alat yang dipinjam pada sesi ini.
                            </div>
                        @endif
                    </form>
                </div>
                <div class="modal-footer bg-white border-top p-3">
                    <button type="button" class="btn btn-sm btn-light px-4 font-weight-medium" data-dismiss="modal">
                        <i class="fas fa-times mr-1 text-muted"></i> Batal
                    </button>
                    <button type="button" onclick="submitPengembalian()"
                        class="btn btn-sm btn-dark px-4 font-weight-medium shadow-sm" id="btn-submit-kembali">
                        <i class="fas fa-check mr-1"></i> Konfirmasi Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Validasi Real-time Anti Salah Input
                $('.input-kondisi').on('input', function() {
                    let row = $(this).closest('.row-alat');
                    let totalPinjam = parseInt(row.data('pinjam'));

                    let inputs = row.find('.input-kondisi');
                    let valBaik = parseInt($(inputs[0]).val()) || 0;
                    let valRingan = parseInt($(inputs[1]).val()) || 0;
                    let valBerat = parseInt($(inputs[2]).val()) || 0;

                    let totalKembali = valBaik + valRingan + valBerat;

                    // Jika jumlah tidak sama dengan yang dipinjam
                    if (totalKembali !== totalPinjam) {
                        row.css('background-color', '#fff9fa'); // Warna merah super pudar
                        $('#btn-submit-kembali').prop('disabled', true);
                        $('#error-pengembalian').removeClass('d-none');
                    } else {
                        row.css('background-color', 'transparent');

                        // Cek apakah semua baris alat sudah valid
                        let isAllValid = true;
                        $('.row-alat').each(function() {
                            let tPinjam = parseInt($(this).data('pinjam'));
                            let tInputs = $(this).find('.input-kondisi');
                            let tVal = (parseInt($(tInputs[0]).val()) || 0) + (parseInt($(tInputs[1])
                                .val()) || 0) + (parseInt($(tInputs[2]).val()) || 0);
                            if (tVal !== tPinjam) isAllValid = false;
                        });

                        if (isAllValid) {
                            $('#btn-submit-kembali').prop('disabled', false);
                            $('#error-pengembalian').addClass('d-none');
                        }
                    }
                });
            });
        </script>
    @endpush
@endif
