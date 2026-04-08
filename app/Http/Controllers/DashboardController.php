<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\PengajuanPraktikum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

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
            return view('admin.index');
        } elseif ($role == 'Dosen') {
            return view('dosen.index');
        } elseif ($role == 'Kaprodi') {
            return view('kaprodi.index');
        } elseif ($role == 'Kajur') {
            // --- STATISTIK KHUSUS KAJUR ---
            $total     = PengajuanPraktikum::count();
            $proses    = PengajuanPraktikum::whereIn('status', ['Menunggu Kaprodi', 'Menunggu Super Admin'])->count();
            $disetujui = PengajuanPraktikum::where('status', 'Disetujui')->count();
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
        $query = PengajuanPraktikum::with(['user.dosen', 'lab', 'makul'])->orderBy('created_at', 'desc');

        // Logic Filter Berdasarkan Role
        if ($user->role === 'Dosen') {
            $query->where('id_users', $user->id);
        } elseif ($user->role === 'Super Admin') {
            $query->whereIn('status', ['Menunggu Super Admin', 'Disetujui', 'Ditolak Super Admin']);
        }

        return datatables()
            ->of($query)
            ->addIndexColumn()
            ->addColumn('dosen_nama', function ($row) {
                return $row->user && $row->user->dosen ? $row->user->dosen->nama : ($row->user ? $row->user->username : '-');
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
                    } elseif (str_contains($status, 'Ditolak')) {
                        return '<span class="badge bg-danger text-white px-2 py-1"><i class="fas fa-ban mr-1"></i> ' . $status . '</span>';
                    }
                }

                // --- TAMPILAN BADGE STANDAR UNTUK ROLE LAIN ---
                if ($status == 'Disetujui') {
                    return '<span class="badge bg-success text-white px-2 py-1"><i class="fas fa-check-circle"></i> Telah Disetujui</span>';
                } elseif (str_contains($status, 'Ditolak')) {
                    return '<span class="badge bg-danger text-white px-2 py-1"><i class="fas fa-times-circle"></i> Ditolak</span>';
                } else {
                    return '<span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-sync-alt fa-spin"></i> Diproses</span>';
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
