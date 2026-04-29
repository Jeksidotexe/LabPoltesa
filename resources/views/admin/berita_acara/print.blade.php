<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Berita Acara Praktikum</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .mt-10 {
            margin-top: 8px;
        }

        .mt-20 {
            margin-top: 15px;
        }

        .mb-5 {
            margin-bottom: 4px;
        }

        .w-100 {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 3px 4px;
            vertical-align: top;
        }

        .kop-table td {
            padding: 3px;
        }

        .kop-table .title-cell {
            text-align: center;
            vertical-align: middle;
            width: 55%;
            font-size: 15px;
            font-weight: bold;
        }

        .kop-table .meta-cell {
            width: 30%;
            font-size: 11px;
        }

        .meta-inner-table {
            width: 100%;
            border: none;
        }

        .meta-inner-table td {
            border: none;
            padding: 1px 0;
            font-size: 11px;
        }

        .layout-table,
        .layout-table th,
        .layout-table td {
            border: none;
            padding: 2px 0;
        }

        .data-table th {
            background-color: #fce4ec !important;
            text-align: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .data-table td {
            height: 15px;
        }

        .kejadian-box {
            border: 1px solid black;
            min-height: 50px;
            padding: 5px;
            margin-top: 4px;
        }

        .ttd-area {
            width: 100%;
            margin-top: 15px;
            border: none;
        }

        .ttd-area td {
            border: none;
            padding: 0;
            width: 50%;
            vertical-align: bottom;
        }

        .ttd-space {
            height: 55px;
        }
    </style>
</head>

<body onload="window.print()">

    <table class="kop-table w-100">
        <tr>
            <td class="text-center" style="width: 15%;">
                <img src="{{ asset('poltesa.png') }}" alt="Logo" width="60">
                <div style="font-weight: bold; font-size: 12px; margin-top: 3px;">POLTESA</div>
            </td>
            <td class="title-cell">BERITA ACARA<br>PRAKTIKUM</td>
            <td class="meta-cell">
                <table class="meta-inner-table">
                    <tr>
                        <td width="35%">No. Dokumen</td>
                        <td width="5%">:</td>
                        <td>{{ $data['no_dokumen'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ $data['tgl_dokumen'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Halaman</td>
                        <td>:</td>
                        <td>{{ $data['halaman'] ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="layout-table w-100 mt-10">
        <tr>
            <td width="15%">Pada Hari Ini</td>
            <td width="2%">:</td>
            <td width="43%">{{ $data['hari'] ?? '-' }}</td>
            <td width="15%">Tanggal</td>
            <td width="2%">:</td>
            <td width="23%">{{ $data['tanggal_praktikum'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tempat Praktek</td>
            <td>:</td>
            <td>{{ $data['tempat'] ?? '-' }}</td>
            <td>Semester</td>
            <td>:</td>
            <td>{{ $data['semester'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>Mata Kuliah</td>
            <td>:</td>
            <td colspan="4">{{ $data['makul'] ?? '-' }}</td>
        </tr>
    </table>

    <div class="text-center font-bold mt-20 mb-5" style="text-decoration: underline;">TELAH DILAKSANAKAN PRAKTIKUM</div>

    <table class="layout-table w-100 mb-5">
        <tr>
            <td width="15%">Judul Praktikum</td>
            <td width="2%">:</td>
            <td>{{ $data['judul'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>Dimulai Pukul</td>
            <td>:</td>
            <td>{{ $data['waktu'] ?? '-' }}</td>
        </tr>
    </table>

    <div class="mb-5">Alat dan Bahan yang digunakan:</div>
    <table class="data-table w-100 mb-5">
        <tr>
            <th width="5%">No</th>
            <th width="35%">Alat</th>
            <th width="10%">Jml</th>
            <th width="35%">Bahan</th>
            <th width="10%">Jml</th>
        </tr>
        @php
            $alats = array_values(array_filter($data['alat'] ?? []));
            $jmlAlats = array_values(array_filter($data['jml_alat'] ?? []));
            $bahans = array_values(array_filter($data['bahan'] ?? []));
            $jmlBahans = array_values(array_filter($data['jml_bahan'] ?? []));

            $maxRows = max(count($alats), count($bahans));
            $maxRows = $maxRows > 0 ? $maxRows : 1;
        @endphp
        @for ($i = 0; $i < $maxRows; $i++)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $alats[$i] ?? '' }}</td>
                <td class="text-center">{{ $jmlAlats[$i] ?? '' }}</td>
                <td>{{ $bahans[$i] ?? '' }}</td>
                <td class="text-center">{{ $jmlBahans[$i] ?? '' }}</td>
            </tr>
        @endfor
    </table>

    <table class="layout-table w-100 mb-5 mt-10">
        <tr>
            <td width="40%">Jumlah Peserta sesuai daftar hadir:</td>
            <td>{{ $data['jml_hadir'] ?? '-' }} Orang</td>
        </tr>
        <tr>
            <td>Peserta yang tidak mengikuti praktikum:</td>
            <td>{{ $data['jml_tidak_hadir'] ?? '-' }} Orang</td>
        </tr>
    </table>

    <table class="data-table w-100">
        <tr>
            <th width="8%">No.</th>
            <th width="42%">Nama & NIM</th>
            <th width="8%">No</th>
            <th width="42%">Nama & NIM</th>
        </tr>
        @php
            $pesertas = array_values(array_filter($data['peserta'] ?? []));
            $totalPeserta = count($pesertas);
            $barisBagi = ceil($totalPeserta / 2);
            $barisBagi = $barisBagi > 0 ? $barisBagi : 1;
        @endphp
        @for ($i = 0; $i < $barisBagi; $i++)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $pesertas[$i] ?? '' }}</td>
                <td class="text-center">{{ $i + 1 + $barisBagi }}</td>
                <td>{{ $pesertas[$i + $barisBagi] ?? '' }}</td>
            </tr>
        @endfor
    </table>

    <table class="layout-table w-100 mt-10">
        <tr>
            <td width="20%">Dosen Pendamping</td>
            <td width="2%">:</td>
            <td>{{ $data['dosen_pendamping'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>Teknisi Pendamping</td>
            <td>:</td>
            <td>{{ $data['teknisi'] ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 5px;">Kejadian selama praktikum berlangsung:</td>
        </tr>
    </table>
    <div class="kejadian-box">
        {{ $data['kejadian'] ?? '' }}
    </div>

    <table class="ttd-area">
        <tr>
            <td class="text-center">
                Mengetahui,<br>
                Dosen Pendamping
                <div class="ttd-space text-center" style="position: relative;">
                    @if (!empty($data['ttd_dosen']))
                        <img src="{{ $data['ttd_dosen'] }}" alt="TTD Dosen"
                            style="height: 60px; max-width: 100%; object-fit: contain; margin-top: 5px;">
                    @endif
                </div>
                <span class="font-bold"
                    style="text-decoration: underline;">{{ $data['dosen_pendamping'] ?? '-' }}</span>
            </td>
            <td class="text-center" style="padding-left: 20%;">
                {{ $data['tgl_ttd'] ?? 'Sambas, ......................' }}<br>
                PLP / Teknisi
                <div class="ttd-space text-center" style="position: relative;">
                    @if (!empty($data['ttd_teknisi']))
                        <img src="{{ $data['ttd_teknisi'] }}" alt="TTD Teknisi"
                            style="height: 60px; max-width: 100%; object-fit: contain; margin-top: 5px;">
                    @endif
                </div>
                <span class="font-bold" style="text-decoration: underline;">{{ $data['teknisi'] ?? '-' }}</span>
            </td>
        </tr>
    </table>
</body>

</html>
