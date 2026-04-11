<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\PengajuanPraktikum;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    // Fungsi bantuan untuk men-generate Nomor Registrasi
    private function generateSmartId($kategori_nama, $created_at, $lab_kode, $id_pengajuan)
    {
        $namaKategori = $kategori_nama ?: 'UMUM';
        $words = explode(' ', $namaKategori);
        $prefix = count($words) > 1
            ? substr(collect($words)->reject(fn($w) => in_array(strtolower($w), ['ke', 'dan', 'di', 'pada', 'untuk']))->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode(''), 0, 4)
            : strtoupper(substr($namaKategori, 0, 3));

        if (empty($prefix)) $prefix = 'REQ';

        $tahunBulan = Carbon::parse($created_at)->format('Ym');
        $kodeLab    = $lab_kode ?: 'NOLAB';
        $idPad      = str_pad($id_pengajuan, 4, '0', STR_PAD_LEFT);

        return "{$prefix}/{$tahunBulan}/{$kodeLab}/{$idPad}";
    }

    public function index()
    {
        // Ambil data lab aktif untuk filter Select2
        $labs = Laboratorium::where('status', 'Aktif')->orderBy('nama', 'asc')->get();

        return view('jadwal.index', compact('labs'));
    }

    public function data(Request $request)
    {
        $query = PengajuanPraktikum::with(['user', 'lab', 'makul'])
            ->where('status', 'Disetujui')
            ->whereDate('tanggal', '>=', Carbon::today('Asia/Jakarta'))
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc');

        // LOGIKA FILTER LABORATORIUM
        if ($request->has('id_lab') && $request->id_lab != '') {
            $query->where('id_lab', $request->id_lab);
        }

        return datatables()
            ->of($query)
            ->addIndexColumn()
            ->addColumn('dosen_nama', function ($row) {
                if ($row->user) {
                    if ($row->user->nama) {
                        $nama = $row->user->nama;
                        if ($row->user->gelar_depan) $nama = $row->user->gelar_depan . ' ' . $nama;
                        if ($row->user->gelar_belakang) $nama .= ', ' . $row->user->gelar_belakang;
                        return $nama;
                    }
                    return $row->user->username;
                }
                return '-';
            })
            ->addColumn('lab_nama', function ($row) {
                return $row->lab ? $row->lab->nama : '-';
            })
            ->addColumn('makul_nama', function ($row) {
                return $row->makul ? $row->makul->nama : '-';
            })
            ->editColumn('tanggal', function ($row) {
                $date = Carbon::parse($row->tanggal)->timezone('Asia/Jakarta');
                $formattedDate = $date->translatedFormat('l, d F Y');

                if ($date->isToday()) {
                    return '<span class="badge bg-danger text-white px-2 py-1 mr-2 rounded-pill"><i class="fas fa-fire mr-1"></i> Hari Ini</span> ' . $formattedDate;
                } elseif ($date->isTomorrow()) {
                    return '<span class="badge bg-warning text-dark px-2 py-1 mr-2 rounded-pill"><i class="fas fa-bolt mr-1"></i> Besok</span> ' . $formattedDate;
                }
                return $formattedDate;
            })
            ->addColumn('waktu', function ($row) {
                return substr($row->jam_mulai, 0, 5) . ' - ' . substr($row->jam_selesai, 0, 5) . ' WIB';
            })
            ->addColumn('aksi', function ($row) {
                return '
                <div class="d-flex justify-content-center">
                    <a href="' . route('jadwal.show', $row->id_pengajuan) . '" class="btn btn-sm btn-info btn-rounded" title="Detail">
                        <i class="fa fa-eye"></i></a>
                </div>';
            })
            ->rawColumns(['tanggal', 'aksi'])
            ->make(true);
    }

    public function show($id)
    {
        // Hanya tampilkan detail jika statusnya sudah Disetujui
        $jadwal = PengajuanPraktikum::with(['user.prodi', 'lab', 'makul', 'kategori'])
            ->where('status', 'Disetujui')
            ->findOrFail($id);

        $nomorReg = $this->generateSmartId(
            $jadwal->kategori->nama_kategori ?? null,
            $jadwal->created_at,
            $jadwal->lab->kode ?? null,
            $jadwal->id_pengajuan
        );

        // Format Nama Dosen
        $namaDosen = $jadwal->user->username;
        if ($jadwal->user->nama) {
            $namaDosen = $jadwal->user->nama;
            if ($jadwal->user->gelar_depan) $namaDosen = $jadwal->user->gelar_depan . ' ' . $namaDosen;
            if ($jadwal->user->gelar_belakang) $namaDosen .= ', ' . $jadwal->user->gelar_belakang;
        }

        $waktuPrak = substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) . ' WIB';
        $tglPrak = Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y');

        return view('jadwal.show', compact('jadwal', 'nomorReg', 'namaDosen', 'waktuPrak', 'tglPrak'));
    }
}
