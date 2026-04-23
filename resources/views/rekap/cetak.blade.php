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

        .kop-header {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-table {
            margin: 0 auto;
            width: auto;
            border-collapse: collapse;
        }

        .kop-table td {
            border: none !important;
            padding: 0;
            vertical-align: middle;
        }

        .img-kop {
            max-width: 110px;
            height: auto;
            margin-right: 20px;
        }

        .text-kop {
            text-align: center;
            line-height: 1.1;
        }

        .teks-kementerian {
            font-size: 24px;
            font-weight: normal;
        }

        .teks-poltesa {
            font-size: 20px;
            font-weight: bold;
            margin-top: 5px;
        }

        .teks-jurusan {
            font-size: 18px;
            font-weight: bold;
            margin-top: 3px;
        }

        .teks-alamat {
            font-size: 14px;
            margin-top: 8px;
        }

        .teks-kontak {
            font-size: 14px;
            margin-top: 2px;
        }

        .teks-kontak a {
            color: blue;
            text-decoration: underline;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .judul-dokumen {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

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
            background-color: #5cb0dd !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            text-align: center;
            font-weight: bold;
        }

        @media print {
            @page {
                margin: 10mm 15mm;
                size: A4 landscape;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    {{-- KOP SURAT RESMI --}}
    <div class="kop-header">
        <table class="kop-table">
            <tr>
                <td>
                    <img src="{{ asset('LogoKop.jpg') }}" alt="Logo POLTESA" class="img-kop">
                </td>
                <td class="text-kop">
                    <div class="teks-kementerian">KEMENTERIAN PENDIDIKAN TINGGI, SAINS,<br>DAN TEKNOLOGI</div>
                    <div class="teks-poltesa">POLITEKNIK NEGERI SAMBAS</div>
                    <div class="teks-jurusan">JURUSAN AGRIBISNIS</div>
                    <div class="teks-alamat">Jl. Raya Sejangkung, Sambas, 79462 Kalimantan Barat</div>
                    <div class="teks-kontak">
                        Tel. 0562-6303123; Sel. 08115636111; Laman: <a
                            href="http://www.poltesa.ac.id">www.poltesa.ac.id</a>; email: <a
                            href="mailto:info@poltesa.ac.id">info@poltesa.ac.id</a>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- JUDUL DOKUMEN --}}
    <div class="judul-dokumen">
        REKAPITULASI KEGIATAN PRAKTIKUM
    </div>

    {{-- TABEL DATA --}}
    <table class="table-data">
        <thead>
            <tr>
                <th width="1%">No</th>
                <th width="15%">Tanggal Pelaksanaan</th>
                <th width="30%">Mata Kuliah</th>
                <th width="25%">Prodi</th>
                <th width="10%">Waktu Pelaksanaan</th>
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
                    <td style="text-align: center;">
                        {{ substr($row->jam_mulai, 0, 5) }} - {{ substr($row->jam_selesai, 0, 5) }} WIB
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 15px; text-align: center;">Belum ada data kegiatan praktikum.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
