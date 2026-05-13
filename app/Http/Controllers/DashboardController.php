<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\BeritaAcara;
use App\Models\Laboratorium;
use App\Models\PengajuanPraktikum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;
        if ($user->nama) {
            $namaTampil = $user->nama;
            if ($user->gelar_depan) {
                $namaTampil = $user->gelar_depan . ' ' . $namaTampil;
            }
            if ($user->gelar_belakang) {
                $namaTampil .= ', ' . $user->gelar_belakang;
            }
        } else {
            $namaTampil = $user->username;
        }

        if ($role == 'Super Admin') {
            // --- STATISTIK KHUSUS SUPER ADMIN ---
            $total_pengguna = User::count() ?? 0;
            $total_dosen = User::where('role', 'Dosen')->count() ?? 0;
            $lab_aktif = Laboratorium::where('status', 'Aktif')->count() ?? 0;
            $antrean_verif = PengajuanPraktikum::where('status', 'Menunggu Super Admin')->count() ?? 0;

            // Hitungan antrean aktivasi akun (Hanya yang statusnya Nonaktif dan belum pernah diverifikasi)
            $antrean_akun = User::where('status', 'Nonaktif')->whereNull('email_verified_at')->count() ?? 0;

            return view('super_admin.index', compact(
                'total_pengguna',
                'total_dosen',
                'lab_aktif',
                'antrean_verif',
                'antrean_akun'
            ));
        } elseif ($role == 'Admin') {
            // --- STATISTIK KHUSUS ADMIN (HANYA LAB MILIKNYA) ---
            $labMilikAdmin = Laboratorium::where('id_admin', $user->id)->pluck('id_lab')->toArray();

            $total     = PengajuanPraktikum::whereIn('id_lab', $labMilikAdmin)->count();
            $proses    = PengajuanPraktikum::whereIn('id_lab', $labMilikAdmin)->whereIn('status', ['Menunggu Kaprodi', 'Menunggu Super Admin'])->count();
            $disetujui = PengajuanPraktikum::whereIn('id_lab', $labMilikAdmin)->whereIn('status', ['Disetujui', 'Selesai'])->count();
            $ditolak   = PengajuanPraktikum::whereIn('id_lab', $labMilikAdmin)->where('status', 'like', '%Ditolak%')->count();

            $total_jenis_alat  = Alat::whereIn('id_lab', $labMilikAdmin)->count() ?? 0;
            $total_unit_barang = Alat::whereIn('id_lab', $labMilikAdmin)->sum('jumlah') ?? 0;

            $berita_acara = BeritaAcara::whereHas('pengajuan', function ($q) use ($labMilikAdmin) {
                $q->whereIn('id_lab', $labMilikAdmin);
            })->count();

            return view('admin.index', compact(
                'total',
                'proses',
                'disetujui',
                'ditolak',
                'total_jenis_alat',
                'total_unit_barang',
                'berita_acara'
            ));
        } elseif ($role == 'Dosen') {
            // --- STATISTIK KHUSUS DOSEN ---
            $total     = PengajuanPraktikum::where('id_users', $user->id)->count();
            $disetujui = PengajuanPraktikum::where('id_users', $user->id)->whereIn('status', ['Disetujui', 'Selesai'])->count();
            $ditolak   = PengajuanPraktikum::where('id_users', $user->id)->where('status', 'like', '%Ditolak%')->count();

            return view('dosen.index', compact(
                'namaTampil',
                'total',
                'disetujui',
                'ditolak'
            ));
        } elseif ($role == 'Kaprodi') {
            // --- STATISTIK KHUSUS KAPRODI (HANYA PRODI YANG SAMA) ---
            $idProdi = $user->id_prodi;

            $queryProdi = PengajuanPraktikum::whereHas('user', function ($q) use ($idProdi) {
                $q->where('id_prodi', $idProdi);
            });

            $total           = (clone $queryProdi)->count() ?? 0;
            $disetujui       = (clone $queryProdi)->whereIn('status', ['Disetujui', 'Selesai'])->count() ?? 0;
            $ditolak         = (clone $queryProdi)->where('status', 'like', '%Ditolak%')->count() ?? 0;
            $menunggu        = (clone $queryProdi)->where('status', 'Menunggu Kaprodi')->count() ?? 0;

            return view('kaprodi.index', compact(
                'namaTampil',
                'total',
                'disetujui',
                'ditolak',
                'menunggu'
            ));
        } elseif ($role == 'Kajur') {
            // --- STATISTIK KHUSUS KAJUR ---
            $total     = PengajuanPraktikum::count();
            $proses    = PengajuanPraktikum::whereIn('status', ['Menunggu Kaprodi', 'Menunggu Super Admin'])->count();
            $disetujui = PengajuanPraktikum::whereIn('status', ['Disetujui', 'Selesai'])->count();
            $ditolak   = PengajuanPraktikum::where('status', 'like', '%Ditolak%')->count();

            return view('kajur.index', compact(
                'total',
                'proses',
                'disetujui',
                'ditolak'
            ));
        } else {
            abort(403, 'Role tidak dikenali.');
        }
    }

    // ====================================================================
    // DATATABLES: ANTREAN AKTIVASI AKUN BARU (KHUSUS SUPER ADMIN)
    // ====================================================================
    public function dataAktivasi()
    {
        $query = User::with('prodi')
            ->where('status', 'Nonaktif')
            ->whereNull('email_verified_at')
            ->orderBy('created_at', 'desc');

        return datatables()
            ->of($query)
            ->addIndexColumn()
            ->addColumn('nama_lengkap', function ($row) {
                $nama = $row->nama;
                if ($row->gelar_depan) $nama = $row->gelar_depan . ' ' . $nama;
                if ($row->gelar_belakang) $nama .= ', ' . $row->gelar_belakang;
                return $nama;
            })
            ->addColumn('prodi_nama', function ($row) {
                return $row->prodi ? $row->prodi->nama_prodi : '-';
            })
            ->addColumn('waktu_daftar', function ($row) {
                return Carbon::parse($row->created_at)->diffForHumans();
            })
            ->addColumn('aksi', function ($row) {
                $urlShow = route('pengguna.show', $row->id);
                $urlToggle = route('pengguna.toggleStatus', $row->id);
                $csrf = csrf_field();

                // Mengamankan string nama jika mengandung tanda kutip (contoh: Jum'at, Ma'ruf) agar tidak merusak JS
                $namaAman = htmlspecialchars($row->nama, ENT_QUOTES);

                return '
                <div class="d-flex justify-content-center">
                    <a href="' . $urlShow . '" class="btn btn-sm btn-info rounded-pill mr-2" title="Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <form action="' . $urlToggle . '" method="POST" id="form-activate-' . $row->id . '">
                        ' . $csrf . '
                        <button type="button" class="btn btn-sm btn-success rounded-pill" title="Aktivasi" onclick="confirmActivation(' . $row->id . ', \'' . $namaAman . '\')">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </form>
                </div>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // ====================================================================
    // DATATABLES: RIWAYAT / PENGAJUAN PRAKTIKUM
    // ====================================================================
    public function dataPengajuan()
    {
        $user = Auth::user();
        $query = PengajuanPraktikum::with(['user', 'lab', 'makul'])->orderBy('created_at', 'desc');

        if ($user->role === 'Dosen') {
            $query->where('id_users', $user->id);
        } elseif ($user->role === 'Kaprodi') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_prodi', $user->id_prodi);
            });
        } elseif ($user->role === 'Super Admin') {
            $query->whereIn('status', ['Menunggu Super Admin', 'Disetujui', 'Ditolak Super Admin', 'Selesai']);
        } elseif ($user->role === 'Admin') {
            // FILTER KHUSUS ADMIN LAB (HANYA LABNYA SENDIRI)
            $labMilikAdmin = Laboratorium::where('id_admin', $user->id)->pluck('id_lab')->toArray();
            $query->whereIn('id_lab', $labMilikAdmin)
                ->whereIn('status', ['Menunggu Super Admin', 'Disetujui', 'Ditolak Super Admin', 'Selesai']);
        }

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('dosen_nama', function ($row) {
                if (!$row->user) return '-';
                if ($row->user->nama) {
                    $namaLengkap = $row->user->nama;
                    if ($row->user->gelar_depan) $namaLengkap = $row->user->gelar_depan . ' ' . $namaLengkap;
                    if ($row->user->gelar_belakang) $namaLengkap .= ', ' . $row->user->gelar_belakang;
                    return $namaLengkap;
                }
                return $row->user->username;
            })
            ->addColumn('lab_nama', fn($row) => $row->lab ? $row->lab->nama : '-')
            ->addColumn('makul_nama', fn($row) => $row->makul ? $row->makul->nama : '-')
            ->editColumn('tanggal', fn($row) => Carbon::parse($row->tanggal)->translatedFormat('d F Y'))
            ->editColumn('jam_mulai', fn($row) => substr($row->jam_mulai, 0, 5))
            ->editColumn('jam_selesai', fn($row) => substr($row->jam_selesai, 0, 5))
            ->addColumn('status_badge', function ($row) use ($user) {
                $status = $row->status;
                if ($user->role === 'Kajur') {
                    if ($status == 'Menunggu Kaprodi') return '<span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-clock mr-1"></i> Verifikasi Kaprodi</span>';
                    elseif ($status == 'Menunggu Super Admin') return '<span class="badge bg-primary text-white px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i> Verifikasi Super Admin</span>';
                    elseif ($status == 'Disetujui') return '<span class="badge bg-success text-white px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Telah Disetujui</span>';
                    elseif ($status == 'Selesai') return '<span class="badge bg-info text-white px-2 py-1"><i class="fas fa-flag-checkered mr-1"></i> Selesai</span>';
                    elseif (str_contains($status, 'Ditolak')) return '<span class="badge bg-danger text-white px-2 py-1"><i class="fas fa-ban mr-1"></i> ' . $status . '</span>';
                }

                if ($status == 'Disetujui') return '<span class="badge bg-success text-white px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Telah Disetujui</span>';
                elseif ($status == 'Selesai') return '<span class="badge bg-primary text-white px-2 py-1"><i class="fas fa-flag-checkered mr-1"></i> Selesai</span>';
                elseif (str_contains($status, 'Ditolak')) return '<span class="badge bg-danger text-white px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>';
                else return '<span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-sync-alt fa-spin mr-1"></i> Diproses</span>';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<a href="' . route('pengajuan.show', $row->id_pengajuan) . '" class="btn btn-rounded btn-sm btn-info" title="Detail"><i class="fa fa-eye"></i></a>';
                return '<div class="d-flex justify-content-center">' . $btn . '</div>';
            })
            ->rawColumns(['jam_mulai', 'jam_selesai', 'status_badge', 'aksi'])
            ->make(true);
    }
}
