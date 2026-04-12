{{-- ================================================= --}}
{{-- MODAL: CATAT PERBAIKAN ALAT (KHUSUS ADMIN) --}}
{{-- ================================================= --}}
@if ($alat->jumlah_rusak_ringan > 0 || $alat->jumlah_rusak_berat > 0)
    <div class="modal fade" id="modalPerbaikan" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-sm" style="border-radius: 12px;">
                <div class="modal-header bg-white border-bottom p-4">
                    <h5 class="modal-title font-weight-bold text-dark">
                        <i class="fa fa-wrench text-success mr-2"></i> Catat Perbaikan Alat
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('alat.repair', $alat->id_alat) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 bg-white">
                        <p class="text-muted font-14 mb-4">
                            Pindahkan alat yang telah selesai diperbaiki ke dalam stok <strong>Kondisi Baik
                                (Tersedia)</strong> agar dapat dipinjam kembali.
                        </p>

                        <div class="form-group mb-4">
                            <label class="text-dark font-weight-bold font-13">Pilih Sumber Alat Rusak <span
                                    class="text-danger">*</span></label>
                            {{-- Menggunakan class select2-perbaikan --}}
                            <select name="jenis_rusak" class="form-control select2-perbaikan" required>
                                <option value="" disabled selected>-- Pilih Kondisi Awal --</option>
                                @if ($alat->jumlah_rusak_ringan > 0)
                                    <option value="ringan">Rusak Ringan (Tersedia: {{ $alat->jumlah_rusak_ringan }}
                                        Unit)</option>
                                @endif
                                @if ($alat->jumlah_rusak_berat > 0)
                                    <option value="berat">Rusak Berat (Tersedia: {{ $alat->jumlah_rusak_berat }} Unit)
                                    </option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label class="text-dark font-weight-bold font-13">Jumlah Unit yang Diperbaiki <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="jumlah_diperbaiki" class="form-control shadow-none"
                                min="1" placeholder="Masukkan angka..." required>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-3">
                        <button type="button"
                            class="btn btn-sm btn-secondary font-weight-medium"
                            data-dismiss="modal"><i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit"
                            class="btn btn-sm btn-dark px-4 font-weight-medium">
                            <i class="fa fa-check mr-1"></i> Simpan Perbaikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#modalPerbaikan').on('shown.bs.modal', function() {
                    $('.select2-perbaikan').select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        minimumResultsForSearch: Infinity,
                        dropdownParent: $(
                            '#modalPerbaikan')
                    });
                });
            });
        </script>
    @endpush
@endif
