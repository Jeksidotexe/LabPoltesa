@extends('layouts.master')
@section('title', 'Buat Berita Acara Praktikum')
@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb mb-3">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h4 class="page-title text-dark font-weight-bold mb-1">Form Berita Acara Praktikum</h4>
                    <p class="text-muted font-14 mb-0">Data otomatis diambil dari jadwal. Form tidak disimpan penuh, hanya
                        untuk cetak.</p>
                </div>
                <div class="col-md-6 col-4 align-self-center text-right">
                    <a href="{{ route('berita-acara.index') }}" class="btn btn-sm btn-secondary font-weight-medium">
                        <i class="fa fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid pb-5">
            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('berita-acara.print') }}" method="POST" target="_blank" id="formBeritaAcara">
                        @csrf
                        <input type="hidden" name="id_pengajuan" value="{{ $pengajuan ? $pengajuan->id_pengajuan : '' }}">

                        <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3"><i
                                class="fas fa-file-alt mr-2"></i>Header Dokumen</h5>
                        <div class="row mb-4">
                            <div class="col-md-4 form-group">
                                <label>No. Dokumen</label>
                                <input type="text" name="no_dokumen" class="form-control">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Tanggal Dokumen</label>
                                <input type="text" id="tgl_dokumen" name="tgl_dokumen" class="form-control"
                                    value="{{ $tanggal ?: \Carbon\Carbon::now()->translatedFormat('d F Y') }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Halaman</label>
                                <input type="text" name="halaman" class="form-control">
                            </div>
                        </div>

                        <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3"><i
                                class="fas fa-info-circle mr-2"></i>Informasi Praktikum</h5>
                        <div class="row mb-4">
                            <div class="col-md-4 form-group">
                                <label>Hari</label>
                                <input type="text" name="hari" class="form-control" value="{{ $hari }}"
                                    {{ $hari ? 'readonly' : 'required' }}>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Tanggal Praktikum</label>
                                <input type="text" id="tanggal_praktikum" name="tanggal_praktikum" class="form-control"
                                    value="{{ $tanggal }}" {{ $tanggal ? 'readonly' : 'required' }}>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Tempat Praktik</label>
                                <input type="text" name="tempat" class="form-control" value="{{ $lab }}"
                                    {{ $lab ? 'readonly' : 'required' }}>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Semester <span class="text-danger">*</span></label>
                                <input type="text" name="semester" class="form-control" value="{{ $semester }}"
                                    required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Mata Kuliah</label>
                                <input type="text" name="makul" class="form-control" value="{{ $makul }}"
                                    {{ $makul ? 'readonly' : 'required' }}>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Waktu Praktikum</label>
                                <input type="text" name="waktu" class="form-control" value="{{ $waktu }}"
                                    {{ $waktu ? 'readonly' : 'required' }}>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Judul Praktikum <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control" value="{{ $judul }}"
                                    required>
                            </div>
                        </div>

                        <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3"><i
                                class="fas fa-boxes mr-2"></i>Alat dan Bahan</h5>
                        <table class="table table-bordered table-sm" id="tableAlatBahan">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Nama Alat</th>
                                    <th width="10%" class="text-center">Jumlah</th>
                                    <th>Nama Bahan</th>
                                    <th width="10%" class="text-center">Jumlah</th>
                                    <th width="50" class="text-center"><button type="button"
                                            class="btn btn-sm btn-primary" onclick="addAlatBahan()"><i
                                                class="fas fa-plus"></i></button></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($pengajuan) && $pengajuan->alat && $pengajuan->alat->count() > 0)
                                    @foreach ($pengajuan->alat as $item)
                                        <tr>
                                            <td><input type="text" name="alat[]"
                                                    class="form-control form-control-sm bg-light"
                                                    value="{{ $item->nama_alat }}" readonly tabindex="-1"></td>
                                            <td><input type="number" name="jml_alat[]"
                                                    class="form-control form-control-sm bg-light text-center"
                                                    value="{{ $item->pivot->jumlah_pinjam }}" readonly tabindex="-1">
                                            </td>
                                            <td><input type="text" name="bahan[]"
                                                    class="form-control form-control-sm"></td>
                                            <td><input type="number" name="jml_bahan[]"
                                                    class="form-control form-control-sm text-center"></td>
                                            <td class="text-center"><button type="button" class="btn btn-sm btn-danger"
                                                    onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
                                        </tr>
                                    @endforeach
                                @else
                                    @for ($i = 0; $i < 3; $i++)
                                        <tr>
                                            <td><input type="text" name="alat[]"
                                                    class="form-control form-control-sm"></td>
                                            <td><input type="number" name="jml_alat[]"
                                                    class="form-control form-control-sm text-center"></td>
                                            <td><input type="text" name="bahan[]"
                                                    class="form-control form-control-sm"></td>
                                            <td><input type="number" name="jml_bahan[]"
                                                    class="form-control form-control-sm text-center"></td>
                                            <td class="text-center"><button type="button" class="btn btn-sm btn-danger"
                                                    onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
                                        </tr>
                                    @endfor
                                @endif
                            </tbody>
                        </table>

                        <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3 mt-4"><i
                                class="fas fa-users mr-2"></i>Kehadiran Peserta</h5>
                        <div class="row mb-3">
                            <div class="col-md-6 form-group">
                                <label>Jumlah Peserta Hadir (Orang)</label>
                                <input type="number" name="jml_hadir" class="form-control" value="0" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Jumlah Peserta Tidak Hadir (Orang)</label>
                                <input type="number" name="jml_tidak_hadir" class="form-control" value="0"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="font-weight-bold text-dark mb-2">Import Daftar Seluruh Mahasiswa</label>
                                <div id="drop-zone" class="border rounded border-primary bg-light-primary text-center p-4"
                                    style="border: 2px dashed #5f76e8 !important; cursor: pointer; transition: all 0.3s;">
                                    <input type="file" id="fileExcel" class="d-none" accept=".xlsx, .xls, .csv">
                                    <div class="text-primary mb-2">
                                        <i class="fas fa-file-excel fa-3x"></i>
                                    </div>
                                    <h6 class="text-dark font-weight-medium m-0">Klik atau Tarik file Excel (XLSX/CSV) ke
                                        sini</h6>
                                    <p class="text-muted font-12 m-0 mt-1">Format: Kolom A = Nama Mahasiswa | Kolom B = NIM
                                    </p>
                                    <div id="file-name-display" class="mt-2 badge badge-primary font-12 d-none"></div>
                                </div>
                            </div>
                        </div>

                        <div id="previewContainer" class="d-none mb-4">
                            <div
                                class="d-flex justify-content-between align-items-center bg-light border border-bottom-0 px-3 py-2 rounded-top">
                                <h6 class="font-weight-bold text-dark m-0"><i
                                        class="fas fa-list-ol text-primary mr-2"></i>Pratinjau Data (<span
                                        id="previewCount">0</span> Mahasiswa)</h6>
                            </div>
                            <div class="table-responsive border rounded-bottom"
                                style="max-height: 250px; overflow-y: auto;">
                                <table class="table table-bordered table-sm table-hover m-0" id="tablePreviewPeserta">
                                    <thead class="bg-white"
                                        style="position: sticky; top: 0; z-index: 1; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                                        <tr>
                                            <th width="10%" class="text-center border-0">No</th>
                                            <th class="border-0">Nama & NIM</th>
                                            <th width="10%" class="text-center border-0">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3 mt-4"><i
                                class="fas fa-clipboard-check mr-2"></i>Catatan & Tanda Tangan</h5>
                        <div class="row mb-4">
                            <div class="col-md-12 form-group">
                                <label>Kejadian selama praktikum berlangsung</label>
                                <textarea name="kejadian" class="form-control" rows="3">{{ $kejadian }}</textarea>
                            </div>

                            {{-- Input Dosen, Teknisi, dan Tgl TTD Readonly --}}
                            <div class="col-md-4 form-group">
                                <label>Dosen Pendamping</label>
                                <input type="text" name="dosen_pendamping" class="form-control bg-light"
                                    value="{{ $dosen }}" readonly tabindex="-1" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Teknisi Pendamping / PLP</label>
                                <input type="text" name="teknisi" class="form-control bg-light"
                                    value="{{ $teknisi }}" readonly tabindex="-1" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Tempat, Tanggal TTD</label>
                                <input type="text" id="tgl_ttd" name="tgl_ttd" class="form-control bg-light"
                                    value="Sambas, {{ $tanggal ?: \Carbon\Carbon::now()->translatedFormat('d F Y') }}"
                                    readonly tabindex="-1">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold text-dark">Tanda Tangan Dosen Pendamping</label>
                                <div class="border rounded bg-secondary" style="overflow: hidden;">
                                    <canvas id="canvasDosen" width="400" height="200"
                                        style="width: 100%; border-bottom: 1px solid #dee2e6; cursor: crosshair; background: #fff;"></canvas>
                                    <div class="p-2 text-right">
                                        <button type="button" class="btn btn-sm btn-dark" onclick="padDosen.clear()"><i
                                                class="fas fa-eraser mr-1"></i> Hapus</button>
                                    </div>
                                </div>
                                <input type="hidden" name="ttd_dosen" id="ttd_dosen">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold text-dark">Tanda Tangan Teknisi / PLP</label>
                                <div class="border rounded bg-secondary" style="overflow: hidden;">
                                    <canvas id="canvasTeknisi" width="400" height="200"
                                        style="width: 100%; border-bottom: 1px solid #dee2e6; cursor: crosshair; background: #fff;"></canvas>
                                    <div class="p-2 text-right">
                                        <button type="button" class="btn btn-sm btn-dark"
                                            onclick="padTeknisi.clear()"><i class="fas fa-eraser mr-1"></i> Hapus</button>
                                    </div>
                                </div>
                                <input type="hidden" name="ttd_teknisi" id="ttd_teknisi">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end border-top pt-3 mt-4">
                            <button type="reset" class="btn btn-sm btn-secondary mr-2"><i class="fas fa-undo mr-1"></i>
                                Bersihkan Form</button>
                            <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-print mr-1"></i> Cetak
                                Dokumen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        #drop-zone:hover,
        #drop-zone.dragover {
            background-color: #e0e8ff !important;
            border-color: #3b5bdb !important;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

    <script>
        // Tanggal TTD dan Dokumen akan tetap tersinkron otomatis apabila Tanggal Praktikum diubah
        $('#tanggal_praktikum').on('input', function() {
            let val = $(this).val();
            $('#tgl_dokumen').val(val);
            $('#tgl_ttd').val('Sambas, ' + val);
        });

        const padDosen = new SignaturePad(document.getElementById('canvasDosen'), {
            backgroundColor: 'rgba(255,255,255,0)'
        });
        const padTeknisi = new SignaturePad(document.getElementById('canvasTeknisi'), {
            backgroundColor: 'rgba(255,255,255,0)'
        });

        $('#formBeritaAcara').on('submit', function() {
            if (!padDosen.isEmpty()) $('#ttd_dosen').val(padDosen.toDataURL('image/png'));
            if (!padTeknisi.isEmpty()) $('#ttd_teknisi').val(padTeknisi.toDataURL('image/png'));
        });

        function addAlatBahan() {
            $('#tableAlatBahan tbody').append(`<tr>
            <td><input type="text" name="alat[]" class="form-control form-control-sm"></td>
            <td><input type="number" name="jml_alat[]" class="form-control form-control-sm text-center"></td>
            <td><input type="text" name="bahan[]" class="form-control form-control-sm"></td>
            <td><input type="number" name="jml_bahan[]" class="form-control form-control-sm text-center"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
        </tr>`);
        }

        function removeRow(btn) {
            $(btn).closest('tr').remove();
        }

        function removePeserta(btn) {
            $(btn).closest('tr').remove();
            let count = 0;
            $('#tablePreviewPeserta tbody tr').each(function() {
                count++;
                $(this).find('td:first').text(count);
            });
            $('#previewCount').text(count);
            if (count === 0) $('#previewContainer').addClass('d-none');
        }

        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('fileExcel');

        dropZone.addEventListener('click', () => fileInput.click());
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
        });

        dropZone.addEventListener('drop', function(e) {
            let dt = e.dataTransfer;
            let files = dt.files;
            if (files.length) handleExcelFile(files[0]);
        }, false);

        fileInput.addEventListener('change', function(e) {
            if (this.files.length) handleExcelFile(this.files[0]);
        });

        function handleExcelFile(file) {
            $('#file-name-display').text(file.name).removeClass('d-none');
            var reader = new FileReader();
            reader.readAsArrayBuffer(file);
            reader.onload = function(e) {
                var data = new Uint8Array(reader.result);
                var workbook = XLSX.read(data, {
                    type: 'array'
                });
                var firstSheet = workbook.SheetNames[0];
                var excelRows = XLSX.utils.sheet_to_json(workbook.Sheets[firstSheet], {
                    header: 1
                });

                $('#tablePreviewPeserta tbody').empty();
                let importedCount = 0;

                for (var i = 1; i < excelRows.length; i++) {
                    var row = excelRows[i];
                    if (row.length > 0) {
                        var nama = row[0] ? row[0].toString().trim() : '';
                        var nim = row[1] ? ' - ' + row[1].toString().trim() : '';
                        var teksInput = nama + nim;

                        if (nama !== '') {
                            importedCount++;
                            let html = `<tr>
                            <td class="text-center align-middle">${importedCount}</td>
                            <td class="align-middle text-dark font-weight-medium">
                                ${teksInput}
                                <input type="hidden" name="peserta[]" value="${teksInput}">
                            </td>
                            <td class="text-center align-middle">
                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="removePeserta(this)"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>`;
                            $('#tablePreviewPeserta tbody').append(html);
                        }
                    }
                }
                $('#previewCount').text(importedCount);
                if (importedCount > 0) $('#previewContainer').removeClass('d-none');
                fileInput.value = '';
            };
        }
    </script>
@endpush
