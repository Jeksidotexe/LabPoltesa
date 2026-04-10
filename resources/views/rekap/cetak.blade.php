<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Kegiatan Laboratorium</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 20px;
            color: #000;
        }

        /* --- STYLING UNTUK KOP SURAT --- */
        .kop-header {
            border-bottom: 3px solid #000;
            /* Garis tebal pemisah kop */
            padding-bottom: 15px;
            margin-bottom: 20px;
            text-align: center;
            /* Memastikan fallback center */
        }

        .kop-table {
            margin: 0 auto;
            /* Membuat keseluruhan blok (gambar+teks) berada tepat di tengah */
            width: auto;
            /* Lebar tabel hanya mengikuti isi teks dan gambar */
            border-collapse: collapse;
        }

        .kop-table td {
            border: none !important;
            /* Hilangkan garis kotak pada kop */
            padding: 0;
            vertical-align: middle;
        }

        .img-kop {
            max-width: 90px;
            /* Ukuran logo, bisa Anda sesuaikan */
            height: auto;
            margin-right: 25px;
            /* Jarak antara logo dan teks */
        }

        .text-kop {
            text-align: center;
        }

        .text-kop h4 {
            margin: 0 0 5px 0;
            font-size: 20px;
            text-transform: uppercase;
            font-weight: 900;
        }

        /* --- STYLING UNTUK TABEL DATA --- */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .table-data,
        .table-data th,
        .table-data td {
            border: 1px solid #000;
        }

        .table-data th,
        .table-data td {
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }

        .table-data th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
            text-align: center;
            font-weight: bold;
        }

        /* Pengaturan Kertas Print */
        @media print {
            @page {
                margin: 1.5cm;
                size: A4 portrait;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="kop-header">
        <table class="kop-table">
            <tr>
                <td>
                    <img src="{{ asset('poltesa.png') }}" alt="Logo" class="img-kop">
                </td>
                <td class="text-kop">
                    <h4>REKAPITULASI KEGIATAN PRAKTIKUM</h4>
                    <h4>LABORATORIUM</h4>
                </td>
            </tr>
        </table>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="1%">No</th>
                <th width="10%">Tanggal Pelaksanaan</th>
                <th width="25%">Mata Kuliah</th>
                <th width="25%">Prodi</th>
                <th width="8%">Waktu Pelaksanaan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataRekap as $index => $row)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>

                    {{-- Format Tanggal Lengkap dengan Hari --}}
                    <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('l, d F Y') }}</td>

                    {{-- Mata Kuliah --}}
                    <td>{{ $row->makul ? $row->makul->nama : '-' }}</td>

                    {{-- Prodi --}}
                    <td>
                        {{ $row->makul && $row->makul->prodi ? $row->makul->prodi->nama_prodi : '-' }}
                    </td>

                    {{-- Waktu Pelaksanaan --}}
                    <td>
                        {{ substr($row->jam_mulai, 0, 5) }} - {{ substr($row->jam_selesai, 0, 5) }} WIB
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 15px;">Belum ada data kegiatan praktikum.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
