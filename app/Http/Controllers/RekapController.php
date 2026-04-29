<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\PengajuanPraktikum;
use App\Models\ProgramStudi;
use Carbon\Carbon;
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

        $totalKegiatan = PengajuanPraktikum::has('beritaAcara')->count();
        $totalLab = Laboratorium::count();
        $menungguVerifikasi = PengajuanPraktikum::whereIn('status', ['Menunggu Kaprodi', 'Menunggu Super Admin'])->count();

        $prodi = ProgramStudi::orderBy('nama_prodi', 'asc')->get();

        return view('rekap.index', compact('totalKegiatan', 'totalLab', 'menungguVerifikasi', 'prodi'));
    }

    public function dataKegiatan(Request $request)
    {
        $query = PengajuanPraktikum::has('beritaAcara')->with(['user', 'lab', 'makul.prodi', 'beritaAcara'])->orderBy('tanggal', 'desc');

        if ($request->has('id_prodi') && $request->id_prodi != '') {
            $query->whereHas('makul', function ($q) use ($request) {
                $q->where('id_prodi', $request->id_prodi);
            });
        }

        return datatables()
            ->of($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="row-checkbox" value="' . $row->id_pengajuan . '" style="cursor: pointer; width: 16px; height: 16px;">';
            })
            ->editColumn('tanggal', function ($row) {
                return Carbon::parse($row->tanggal)->translatedFormat('l, d F Y');
            })
            ->addColumn('makul_nama', function ($row) {
                return $row->makul ? $row->makul->nama : '-';
            })
            ->addColumn('kelas', function ($row) {
                $semester = $row->beritaAcara->semester ?? '-';
                $prodi = $row->makul && $row->makul->prodi ? $row->makul->prodi->nama_prodi : '-';
                return $semester . ' / ' . $prodi;
            })
            ->addColumn('dosen_nama', function ($row) {
                if ($row->user) {
                    $nama = $row->user->nama ?? $row->user->username;
                    if ($row->user->gelar_depan) $nama = $row->user->gelar_depan . ' ' . $nama;
                    if ($row->user->gelar_belakang) $nama .= ', ' . $row->user->gelar_belakang;
                    return $nama;
                }
                return '-';
            })
            ->addColumn('teknisi', function ($row) {
                return $row->beritaAcara->teknisi ?? '-';
            })
            ->addColumn('judul', function ($row) {
                return $row->beritaAcara->judul_praktikum ?? '-';
            })
            ->addColumn('waktu', function ($row) {
                return substr($row->jam_mulai, 0, 5) . ' - ' . substr($row->jam_selesai, 0, 5) . ' WIB';
            })
            ->addColumn('keterangan', function ($row) {
                return $row->beritaAcara->kejadian;
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    public function cetak(Request $request)
    {
        $query = PengajuanPraktikum::has('beritaAcara')->with(['user', 'lab', 'makul.prodi', 'beritaAcara'])->orderBy('tanggal', 'desc');

        if ($request->has('ids') && $request->ids != '') {
            $ids = explode(',', $request->ids);
            $query->whereIn('id_pengajuan', $ids);
        } elseif ($request->has('id_prodi') && $request->id_prodi != '') {
            $query->whereHas('makul', function ($q) use ($request) {
                $q->where('id_prodi', $request->id_prodi);
            });
        }

        $dataRekap = $query->get();
        return view('rekap.cetak', compact('dataRekap'));
    }
}
