<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\PengajuanPraktikum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapController extends Controller
{
    public function kegiatanLab()
    {
        $role = Auth::user()->role;
        $allowedRoles = ['Super Admin', 'Admin', 'Kajur', 'Kaprodi'];

        if (!in_array($role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman Rekap Kegiatan.');
        }

        $totalKegiatan = PengajuanPraktikum::where('status', 'Disetujui')->count();
        $totalLab = Laboratorium::count();
        $menungguVerifikasi = PengajuanPraktikum::whereIn('status', ['Menunggu Kaprodi', 'Menunggu Super Admin'])->count();

        return view('rekap.index', compact('totalKegiatan', 'totalLab', 'menungguVerifikasi'));
    }

    public function dataKegiatan(Request $request)
    {
        $query = PengajuanPraktikum::with(['user.dosen', 'lab', 'makul'])->orderBy('tanggal', 'desc');

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
            ->addColumn('waktu', function ($row) {
                return substr($row->jam_mulai, 0, 5) . ' - ' . substr($row->jam_selesai, 0, 5) . ' WIB';
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
                return '<a href="' . route('pengajuan.show', $row->id_pengajuan) . '" class="btn btn-sm btn-info btn-rounded" title="Detail"><i class="fas fa-eye"></i></a>';
            })
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    public function cetak()
    {
        $dataRekap = PengajuanPraktikum::with(['user.dosen', 'lab', 'makul'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('rekap.cetak', compact('dataRekap'));
    }
}
