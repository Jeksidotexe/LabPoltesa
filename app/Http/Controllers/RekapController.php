<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\PengajuanPraktikum;
use App\Models\ProgramStudi;
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

        // Mengambil data prodi untuk dropdown filter
        $prodi = ProgramStudi::orderBy('nama_prodi', 'asc')->get();

        return view('rekap.index', compact('totalKegiatan', 'totalLab', 'menungguVerifikasi', 'prodi'));
    }

    public function dataKegiatan(Request $request)
    {
        $query = PengajuanPraktikum::with(['user.dosen', 'lab', 'makul.prodi'])->orderBy('tanggal', 'desc');

        // LOGIKA FILTER PROGRAM STUDI
        if ($request->has('id_prodi') && $request->id_prodi != '') {
            $query->whereHas('makul', function ($q) use ($request) {
                $q->where('id_prodi', $request->id_prodi);
            });
        }

        return datatables()
            ->of($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($row) { // Tambahan Kolom Checkbox
                return '<input type="checkbox" class="row-checkbox" value="' . $row->id_pengajuan . '" style="cursor: pointer; width: 16px; height: 16px;">';
            })
            ->addColumn('dosen_nama', function ($row) {
                return $row->user && $row->user->dosen ? $row->user->dosen->nama : ($row->user ? $row->user->username : '-');
            })
            ->addColumn('lab_nama', function ($row) {
                return $row->lab ? $row->lab->nama : '-';
            })
            ->addColumn('makul_nama', function ($row) {
                return $row->makul ? $row->makul->nama : '-';
            })
            ->addColumn('prodi_nama', function ($row) {
                return $row->makul && $row->makul->prodi ? $row->makul->prodi->nama_prodi : '-';
            })
            ->editColumn('tanggal', function ($row) {
                return \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y');
            })
            ->addColumn('waktu', function ($row) {
                return substr($row->jam_mulai, 0, 5) . ' - ' . substr($row->jam_selesai, 0, 5) . ' WIB';
            })
            ->addColumn('status_badge', function ($row) {
                $status = $row->status;
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
            ->rawColumns(['checkbox', 'status_badge', 'aksi']) // Daftarkan checkbox agar HTML-nya terender
            ->make(true);
    }

    public function cetak(Request $request)
    {
        $query = PengajuanPraktikum::with(['user.dosen', 'lab', 'makul.prodi'])->orderBy('tanggal', 'desc');

        // Jika ada spesifik baris yang dicentang
        if ($request->has('ids') && $request->ids != '') {
            $ids = explode(',', $request->ids);
            $query->whereIn('id_pengajuan', $ids);
        }
        // Jika tidak dicentang tapi tabel sedang difilter berdasarkan Prodi
        elseif ($request->has('id_prodi') && $request->id_prodi != '') {
            $query->whereHas('makul', function ($q) use ($request) {
                $q->where('id_prodi', $request->id_prodi);
            });
        }

        $dataRekap = $query->get();

        return view('rekap.cetak', compact('dataRekap'));
    }
}
