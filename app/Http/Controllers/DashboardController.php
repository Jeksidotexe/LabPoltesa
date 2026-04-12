<?php

namespace App\Http\Controllers;

use App\Models\Alat;
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

        // Ambil nama tampil (dengan gelar jika ada, default username jika nama kosong)
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

            return view('super_admin.index', compact(
                'total_pengguna',
                'total_dosen',
                'lab_aktif',
                'antrean_verif'
            ));
        } elseif ($role == 'Admin') {
            // --- STATISTIK KHUSUS ADMIN ---
            $total     = PengajuanPraktikum::count();
            $proses    = PengajuanPraktikum::whereIn('status', ['Menunggu Kaprodi', 'Menunggu Super Admin'])->count();
            // Disetujui digabungkan dengan Selesai sebagai indikator sukses
            $disetujui = PengajuanPraktikum::whereIn('status', ['Disetujui', 'Selesai'])->count();
            $ditolak   = PengajuanPraktikum::where('status', 'like', '%Ditolak%')->count();

            $total_jenis_alat  = Alat::count() ?? 0;
            $total_unit_barang = Alat::sum('jumlah') ?? 0;
            $berita_acara      = 0;

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
            $total     = PengajuanPraktikum::count();
            // Disetujui digabungkan dengan Selesai sebagai indikator sukses
            $disetujui = PengajuanPraktikum::whereIn('status', ['Disetujui', 'Selesai'])->count();
            $ditolak   = PengajuanPraktikum::where('status', 'like', '%Ditolak%')->count();

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
            // Disetujui digabungkan dengan Selesai sebagai indikator sukses
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
            // Disetujui digabungkan dengan Selesai sebagai indikator sukses
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

    public function dataPengajuan()
    {
        $user = Auth::user();

        $query = PengajuanPraktikum::with(['user', 'lab', 'makul'])->orderBy('created_at', 'desc');

        // Logic Filter Berdasarkan Role
        if ($user->role === 'Dosen') {
            $query->where('id_users', $user->id);
        } elseif ($user->role === 'Kaprodi') {
            // KAPRODI HANYA MELIHAT DATA DARI PRODI YANG SAMA
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_prodi', $user->id_prodi);
            });
        } elseif ($user->role === 'Super Admin' || $user->role === 'Admin') {
            // TAMBAHAN: Menambahkan status 'Selesai' dan role 'Admin' agar bisa melihat list
            $query->whereIn('status', ['Menunggu Super Admin', 'Disetujui', 'Ditolak Super Admin', 'Selesai']);
        }

        return datatables()
            ->of($query)
            ->addIndexColumn()
            ->addColumn('dosen_nama', function ($row) {
                if ($row->user) {
                    if ($row->user->nama) {
                        $namaLengkap = $row->user->nama;
                        if ($row->user->gelar_depan) {
                            $namaLengkap = $row->user->gelar_depan . ' ' . $namaLengkap;
                        }
                        if ($row->user->gelar_belakang) {
                            $namaLengkap .= ', ' . $row->user->gelar_belakang;
                        }
                        return $namaLengkap;
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
                return \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y');
            })
            ->editColumn('jam_mulai', function ($row) {
                return substr($row->jam_mulai, 0, 5);
            })
            ->editColumn('jam_selesai', function ($row) {
                return substr($row->jam_selesai, 0, 5);
            })
            ->addColumn('status_badge', function ($row) use ($user) {
                $status = $row->status;

                // --- TAMPILAN BADGE KHUSUS KAJUR ---
                if ($user->role === 'Kajur') {
                    if ($status == 'Menunggu Kaprodi') {
                        return '<span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-clock mr-1"></i> Verifikasi Kaprodi</span>';
                    } elseif ($status == 'Menunggu Super Admin') {
                        return '<span class="badge bg-primary text-white px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i> Verifikasi Super Admin</span>';
                    } elseif ($status == 'Disetujui') {
                        return '<span class="badge bg-success text-white px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Telah Disetujui</span>';
                    } elseif ($status == 'Selesai') {
                        // TAMBAHAN UNTUK KAJUR
                        return '<span class="badge bg-info text-white px-2 py-1"><i class="fas fa-flag-checkered mr-1"></i> Selesai</span>';
                    } elseif (str_contains($status, 'Ditolak')) {
                        return '<span class="badge bg-danger text-white px-2 py-1"><i class="fas fa-ban mr-1"></i> ' . $status . '</span>';
                    }
                }

                // --- TAMPILAN BADGE STANDAR UNTUK ROLE LAIN ---
                if ($status == 'Disetujui') {
                    return '<span class="badge bg-success text-white px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Telah Disetujui</span>';
                } elseif ($status == 'Selesai') {
                    // TAMBAHAN BADGE SELESAI
                    return '<span class="badge bg-primary text-white px-2 py-1"><i class="fas fa-flag-checkered mr-1"></i> Selesai</span>';
                } elseif (str_contains($status, 'Ditolak')) {
                    return '<span class="badge bg-danger text-white px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>';
                } else {
                    return '<span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-sync-alt fa-spin mr-1"></i> Diproses</span>';
                }
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<a href="' . route('pengajuan.show', $row->id_pengajuan) . '" class="btn btn-rounded btn-sm btn-info" title="Detail"><i class="fa fa-eye"></i></a>';
                return '<div class="d-flex justify-content-center">' . $btn . '</div>';
            })
            ->rawColumns(['jam_mulai', 'jam_selesai', 'status_badge', 'aksi'])
            ->make(true);
    }
}
