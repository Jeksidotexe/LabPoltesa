<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPraktikum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        if ($role == 'Super Admin') {
            return view('super_admin.index');
        } elseif ($role == 'Admin') {
            return view('admin.index');
        } elseif ($role == 'Dosen') {
            return view('dosen.index');
        } elseif ($role == 'Kaprodi') {
            return view('kaprodi.index');
        } elseif ($role == 'Kajur') {
            return view('kajur.index');
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
            // Format Tanggal Teks Biasa (Bahasa Indonesia)
            ->editColumn('tanggal', function ($row) {
                return \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y');
            })
            ->editColumn('jam_mulai', function ($row) {
                return substr($row->jam_mulai, 0, 5);
            })
            ->editColumn('jam_selesai', function ($row) {
                return substr($row->jam_selesai, 0, 5);
            })
            ->addColumn('status_badge', function ($row) {
                $status = $row->status;

                // PENYEDERHANAAN STATUS UNTUK TAMPILAN DATATABLES
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
