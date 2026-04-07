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

        .meta-info {
            margin-bottom: 15px;
            font-size: 12px;
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
        }

        th {
            background-color: #f2f2f2 !important;
            /* Terlihat saat print berwarna */
            -webkit-print-color-adjust: exact;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .status-badge {
            font-weight: bold;
        }

        /* Pengaturan Kertas Print */
        @media print {
            @page {
                margin: 1.5cm;
                size: A4 landscape;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header">
        <h2>REKAPITULASI KEGIATAN LABORATORIUM</h2>
        <h4>Manajemen Laboratorium (LabPoltesa)</h4>
    </div>

    <div class="meta-info">
        <strong>Tanggal Dicetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB <br>
        <strong>Dicetak Oleh:</strong> {{ auth()->user()->username }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="15%">Tanggal Pelaksanaan</th>
                <th width="12%">Waktu (WIB)</th>
                <th width="20%">Laboratorium</th>
                <th width="20%">Mata Kuliah</th>
                <th width="18%">Dosen Pengampu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataRekap as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y') }}</td>
                    <td class="text-center">{{ substr($row->jam_mulai, 0, 5) }} - {{ substr($row->jam_selesai, 0, 5) }}
                    </td>
                    <td>{{ $row->lab ? $row->lab->nama : '-' }}</td>
                    <td>{{ $row->makul ? $row->makul->nama : '-' }}</td>
                    <td>{{ $row->user && $row->user->dosen ? $row->user->dosen->nama : ($row->user ? $row->user->username : '-') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data kegiatan praktikum.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
