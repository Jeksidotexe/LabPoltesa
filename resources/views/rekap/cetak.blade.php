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

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0 0 5px 0;
            font-size: 20px;
            text-transform: uppercase;
        }

        .header h4 {
            margin: 0;
            font-size: 16px;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        /* Pengaturan Kertas Print */
        @media print {
            @page {
                margin: 1.5cm;
                size: A4 portrait;
                /* Diubah ke portrait karena kolom lebih sedikit */
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header">
        <h4 style="font-weight: 900;">REKAPITULASI KEGIATAN PRAKTIKUM</h4>
        <h4 style="font-weight: 900;">LABORATORIUM</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th width="1%">No</th>
                <th width="10%">Tanggal Pelaksanaan</th>
                <th width="20%">Mata Kuliah</th>
                <th width="15%">Prodi</th>
                <th width="10%">Waktu Pelaksanaan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataRekap as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>

                    {{-- Format Tanggal Lengkap dengan Hari --}}
                    <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('l, d F Y') }}</td>

                    {{-- Mata Kuliah --}}
                    <td>{{ $row->makul ? $row->makul->nama : '-' }}</td>

                    {{-- Prodi --}}
                    <td class="text-center">
                        {{ $row->makul && $row->makul->prodi ? $row->makul->prodi->nama_prodi : '-' }}
                    </td>

                    {{-- Waktu Pelaksanaan --}}
                    <td class="text-center">
                        {{ substr($row->jam_mulai, 0, 5) }} - {{ substr($row->jam_selesai, 0, 5) }} WIB
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px;">Belum ada data kegiatan praktikum.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
