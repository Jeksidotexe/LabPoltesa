<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanPraktikum;
use App\Models\BeritaAcara;
use App\Models\Laboratorium;
use App\Models\User;
use Carbon\Carbon;

class BeritaAcaraController extends Controller
{
    public function index()
    {
        return view('admin.berita_acara.index');
    }

    public function data()
    {
        $user = Auth::user();
        $query = PengajuanPraktikum::with(['user', 'makul', 'beritaAcara'])
            ->whereIn('status', ['Disetujui', 'Selesai'])
            ->orderBy('tanggal', 'desc');

        if ($user->role === 'Dosen') {
            $query->where('id_users', $user->id);
        } elseif ($user->role === 'Admin') {
            $labMilikAdmin = Laboratorium::where('id_admin', $user->id)->pluck('id_lab')->toArray();
            $query->whereIn('id_lab', $labMilikAdmin);
        }

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('tanggal', fn($row) => Carbon::parse($row->tanggal)->translatedFormat('d F Y'))
            ->addColumn('makul', fn($row) => $row->makul ? $row->makul->nama : '-')
            ->addColumn('dosen', function ($row) {
                if (!$row->user) return '-';
                $u = $row->user;
                $nama = $u->nama ?? $u->username;
                if ($u->gelar_depan) $nama = $u->gelar_depan . ' ' . $nama;
                if ($u->gelar_belakang) $nama .= ', ' . $u->gelar_belakang;
                return $nama;
            })
            ->addColumn('status_ba', function ($row) use ($user) {
                if ($row->beritaAcara) {
                    return $user->role === 'Admin'
                        ? '<span class="badge bg-info text-white px-2 py-1"><i class="fas fa-print mr-1"></i> Siap Dicetak</span>'
                        : '<span class="badge bg-success text-white px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Telah Diinput</span>';
                }
                return '<span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-clock mr-1"></i> Belum Dibuat</span>';
            })
            ->addColumn('aksi', function ($row) use ($user) {
                if ($user->role === 'Dosen') {
                    $btnClass = $row->beritaAcara ? 'btn-success' : 'btn-primary';
                    $icon = $row->beritaAcara ? 'fa-edit' : 'fa-print';
                    return '<div class="d-flex justify-content-center"><a href="' . route('berita-acara.create', $row->id_pengajuan) . '" class="btn btn-sm ' . $btnClass . ' btn-rounded" title="Input Berita Acara"><i class="fas ' . $icon . '"></i></a></div>';
                }
                if ($row->beritaAcara) {
                    return '<div class="d-flex justify-content-center"><a href="' . route('berita-acara.create', $row->id_pengajuan) . '" class="btn btn-sm btn-info btn-rounded" title="Cetak Berita Acara"><i class="fas fa-print"></i></a></div>';
                }
                return '<div class="d-flex justify-content-center"><button class="btn btn-sm btn-secondary btn-rounded" disabled><i class="fas fa-clock"></i></button></div>';
            })
            ->rawColumns(['status_ba', 'aksi'])->make(true);
    }

    public function create(?string $id = null)
    {
        $pengajuan = PengajuanPraktikum::with(['user', 'lab.admin', 'makul', 'alat', 'beritaAcara'])->findOrFail($id);
        $userLogin = Auth::user();
        $role = $userLogin->role; // Variabel role untuk dikirim ke view

        // Security Check untuk Admin Lab
        if ($role === 'Admin') {
            $labMilikAdmin = Laboratorium::where('id_admin', $userLogin->id)->pluck('id_lab')->toArray();
            if (!in_array($pengajuan->id_lab, $labMilikAdmin)) abort(403, 'Akses Ditolak.');
        }

        $hari = Carbon::parse($pengajuan->tanggal)->translatedFormat('l');
        $tanggal = Carbon::parse($pengajuan->tanggal)->translatedFormat('d F Y');
        $waktu = substr($pengajuan->jam_mulai, 0, 5) . ' - ' . substr($pengajuan->jam_selesai, 0, 5) . ' WIB';

        // Merangkai Nama Dosen Pendamping
        $u = $pengajuan->user;
        $dosen = ($u->gelar_depan ? $u->gelar_depan . ' ' : '') . ($u->nama ?? $u->username) . ($u->gelar_belakang ? ', ' . $u->gelar_belakang : '');

        // Merangkai Nama Teknisi Default (Berdasarkan Admin Lab Terkait)
        $teknisiLogin = '';
        if ($pengajuan->lab && $pengajuan->lab->admin) {
            $adminLab = $pengajuan->lab->admin;
            $teknisiLogin = ($adminLab->gelar_depan ? $adminLab->gelar_depan . ' ' : '') . ($adminLab->nama ?? $adminLab->username) . ($adminLab->gelar_belakang ? ', ' . $adminLab->gelar_belakang : '');
        }

        // Filter Daftar Teknisi MURNI hanya untuk Admin Lab tersebut
        $idAdminLab = $pengajuan->lab->id_admin ?? null;

        if ($idAdminLab) {
            $listTeknisi = User::where('status', 'Aktif')
                ->where('id', $idAdminLab)
                ->get();

            foreach ($listTeknisi as $t) {
                $t->nama_lengkap = ($t->gelar_depan ? $t->gelar_depan . ' ' : '') . ($t->nama ?? $t->username) . ($t->gelar_belakang ? ', ' . $t->gelar_belakang : '');
            }
        } else {
            $listTeknisi = collect();
        }

        $draft = $pengajuan->beritaAcara ? json_decode($pengajuan->beritaAcara->form_data, true) : [];

        // LOGIKA ARRAY
        $alats = $draft['alat'] ?? ($pengajuan->alat->pluck('nama_alat')->toArray() ?? []);
        $jmlAlats = $draft['jml_alat'] ?? ($pengajuan->alat->pluck('pivot.jumlah_pinjam')->toArray() ?? []);
        $bahans = $draft['bahan'] ?? [];
        $jmlBahans = $draft['jml_bahan'] ?? [];
        $satuans = $draft['satuan_bahan'] ?? [];

        $countAlats = is_array($alats) ? count($alats) : 0;
        $countBahans = is_array($bahans) ? count($bahans) : 0;
        $maxRows = max($countAlats, $countBahans, 3);

        return view('admin.berita_acara.create', compact(
            'pengajuan',
            'hari',
            'tanggal',
            'waktu',
            'dosen',
            'teknisiLogin',
            'draft',
            'listTeknisi',
            'role',
            'alats',
            'jmlAlats',
            'bahans',
            'jmlBahans',
            'satuans',
            'maxRows'
        ));
    }

    public function storeDraft(Request $request)
    {
        $formData = $request->except(['_token', 'id_pengajuan', 'semester', 'judul', 'kejadian']);
        BeritaAcara::updateOrCreate(
            ['id_pengajuan' => $request->id_pengajuan],
            ['semester' => $request->semester, 'judul_praktikum' => $request->judul, 'kejadian' => $request->kejadian, 'form_data' => json_encode($formData)]
        );
        return redirect()->route('berita-acara.index')->with('success', 'Draft Berita Acara berhasil disimpan.');
    }

    public function print(Request $request)
    {
        if ($request->filled('id_pengajuan')) {
            $formData = $request->except(['_token', 'id_pengajuan', 'semester', 'judul', 'kejadian']);
            $formData['is_printed'] = true;
            BeritaAcara::updateOrCreate(
                ['id_pengajuan' => $request->id_pengajuan],
                ['semester' => $request->semester, 'judul_praktikum' => $request->judul, 'kejadian' => $request->kejadian, 'teknisi' => $request->teknisi, 'form_data' => json_encode($formData)]
            );
        }
        $data = $request->all();
        return view('admin.berita_acara.print', compact('data'));
    }
}
