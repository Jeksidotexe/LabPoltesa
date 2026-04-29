<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanPraktikum;
use App\Models\BeritaAcara;
use Carbon\Carbon;

class BeritaAcaraController extends Controller
{
    public function index()
    {
        return view('admin.berita_acara.index');
    }

    public function data()
    {
        // Menampilkan jadwal yang sudah disetujui atau selesai saja
        $query = PengajuanPraktikum::with(['user', 'makul', 'beritaAcara'])
            ->whereIn('status', ['Disetujui', 'Selesai'])
            ->orderBy('tanggal', 'desc');

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($row) {
                return Carbon::parse($row->tanggal)->translatedFormat('d F Y');
            })
            ->addColumn('makul', function ($row) {
                return $row->makul ? $row->makul->nama : '-';
            })
            ->addColumn('dosen', function ($row) {
                if ($row->user) {
                    $namaLengkap = $row->user->nama ?? $row->user->username;
                    if ($row->user->gelar_depan) $namaLengkap = $row->user->gelar_depan . ' ' . $namaLengkap;
                    if ($row->user->gelar_belakang) $namaLengkap .= ', ' . $row->user->gelar_belakang;
                    return $namaLengkap;
                }
                return '-';
            })
            ->addColumn('status_ba', function ($row) {
                return $row->beritaAcara
                    ? '<span class="badge bg-success text-white px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Sudah Dibuat</span>'
                    : '<span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-clock mr-1"></i> Belum Dibuat</span>';
            })
            ->addColumn('aksi', function ($row) {
                if ($row->beritaAcara) {
                    return '<div class="d-flex justify-content-center"><a href="' . route('berita-acara.create', $row->id_pengajuan) . '" class="btn btn-sm btn-info btn-rounded" title="Cetak Ulang / Edit BA"><i class="fas fa-print"></i></a></div>';
                } else {
                    return '<div class="d-flex justify-content-center"><a href="' . route('berita-acara.create', $row->id_pengajuan) . '" class="btn btn-sm btn-primary btn-rounded" title="Buat Berita Acara"><i class="fas fa-file-alt"></i></a></div>';
                }
            })
            ->rawColumns(['status_ba', 'aksi'])
            ->make(true);
    }

    public function create(?string $id = null)
    {
        $pengajuan = null;
        if ($id) {
            $pengajuan = PengajuanPraktikum::with(['user', 'lab', 'makul', 'alat', 'beritaAcara'])->findOrFail($id);
            $hari = Carbon::parse($pengajuan->tanggal)->translatedFormat('l');
            $tanggal = Carbon::parse($pengajuan->tanggal)->translatedFormat('d F Y');
            $waktu = substr($pengajuan->jam_mulai, 0, 5) . ' - ' . substr($pengajuan->jam_selesai, 0, 5) . ' WIB';

            // Merangkai Nama Dosen Pendamping
            $dosen = $pengajuan->user->nama;
            if ($pengajuan->user->gelar_depan) $dosen = $pengajuan->user->gelar_depan . ' ' . $dosen;
            if ($pengajuan->user->gelar_belakang) $dosen .= ', ' . $pengajuan->user->gelar_belakang;

            $lab = $pengajuan->lab->nama ?? '';
            $makul = $pengajuan->makul->nama ?? '';

            // Auto-fill jika sudah pernah dibuat
            $semester = $pengajuan->beritaAcara->semester ?? '';
            $judul = $pengajuan->beritaAcara->judul_praktikum ?? '';
            $kejadian = $pengajuan->beritaAcara->kejadian ?? '';
        } else {
            $hari = $tanggal = $waktu = $dosen = $lab = $makul = $semester = $judul = $kejadian = '';
        }

        $userLogin = Auth::user();

        // Merangkai Nama Teknisi lengkap dengan gelar (jika ada)
        $teknisi = $userLogin->nama ?? $userLogin->username;
        if ($userLogin->nama) {
            if ($userLogin->gelar_depan) $teknisi = $userLogin->gelar_depan . ' ' . $teknisi;
            if ($userLogin->gelar_belakang) $teknisi .= ', ' . $userLogin->gelar_belakang;
        }

        return view('admin.berita_acara.create', compact('pengajuan', 'hari', 'tanggal', 'waktu', 'dosen', 'teknisi', 'lab', 'makul', 'semester', 'judul', 'kejadian'));
    }

    public function print(Request $request)
    {
        // Simpan Data Ke DB untuk Kebutuhan Rekap
        if ($request->filled('id_pengajuan')) {
            BeritaAcara::updateOrCreate(
                ['id_pengajuan' => $request->id_pengajuan],
                [
                    'semester' => $request->semester,
                    'judul_praktikum' => $request->judul,
                    'kejadian' => $request->kejadian,
                    'teknisi' => $request->teknisi
                ]
            );
        }

        $data = $request->all();
        return view('admin.berita_acara.print', compact('data'));
    }
}
