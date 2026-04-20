<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanPraktikum;
use Carbon\Carbon;

class BeritaAcaraController extends Controller
{
    /**
     * Menampilkan form input Berita Acara (Bisa Auto-fill atau Manual)
     */
    public function create($id = null)
    {
        // PERBAIKAN: Inisialisasi variabel pengajuan agar tidak error saat dibuka manual
        $pengajuan = null;

        // JIKA ADA ID (Dibuka dari tabel jadwal) -> AUTO FILL
        if ($id) {
            $pengajuan = PengajuanPraktikum::with(['user', 'lab', 'makul', 'alat'])->findOrFail($id);

            $hari = Carbon::parse($pengajuan->tanggal)->translatedFormat('l');
            $tanggal = Carbon::parse($pengajuan->tanggal)->translatedFormat('d F Y');
            $waktu = substr($pengajuan->jam_mulai, 0, 5) . ' - ' . substr($pengajuan->jam_selesai, 0, 5) . ' WIB';

            $dosen = $pengajuan->user->nama;
            if ($pengajuan->user->gelar_depan) $dosen = $pengajuan->user->gelar_depan . ' ' . $dosen;
            if ($pengajuan->user->gelar_belakang) $dosen .= ', ' . $pengajuan->user->gelar_belakang;

            $lab = $pengajuan->lab->nama ?? '';
            $makul = $pengajuan->makul->nama ?? '';
        }
        // JIKA TIDAK ADA ID (Dibuka dari Sidebar) -> FORM MANUAL (KOSONG)
        else {
            $hari = '';
            $tanggal = '';
            $waktu = '';
            $dosen = '';
            $lab = '';
            $makul = '';
        }

        // Nama Teknisi (Admin yang sedang login) otomatis selalu terisi
        $userLogin = auth()->user();
        $teknisi = $userLogin->nama ?? $userLogin->username;

        // PERBAIKAN: Tambahkan 'pengajuan' ke dalam compact()
        return view('admin.berita_acara.create', compact('pengajuan', 'hari', 'tanggal', 'waktu', 'dosen', 'teknisi', 'lab', 'makul'));
    }

    /**
     * Memproses data dan menampilkannya di halaman cetak (Tanpa masuk DB)
     */
    public function print(Request $request)
    {
        $data = $request->all();
        return view('admin.berita_acara.print', compact('data'));
    }
}
