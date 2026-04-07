<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\MataKuliah;
use App\Models\PengajuanPraktikum;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PengajuanPraktikumController extends Controller
{
    private function generateSmartId($kategori_nama, $created_at, $lab_kode, $id_pengajuan)
    {
        $namaKategori = $kategori_nama ?: 'UMUM';
        $words = explode(' ', $namaKategori);
        $prefix = '';

        if (count($words) > 1) {
            foreach ($words as $word) {
                if (!in_array(strtolower($word), ['ke', 'dan', 'di', 'pada', 'kepada', 'untuk'])) {
                    $prefix .= strtoupper(substr($word, 0, 1));
                }
            }
            $prefix = substr($prefix, 0, 4);
        } else {
            $prefix = strtoupper(substr($namaKategori, 0, 3));
        }

        if (empty($prefix)) $prefix = 'REQ';

        $tahunBulan = Carbon::parse($created_at)->format('Ym');
        $kodeLab    = $lab_kode ?: 'NOLAB';
        $idPad      = str_pad($id_pengajuan, 4, '0', STR_PAD_LEFT);

        return "{$prefix}/{$tahunBulan}/{$kodeLab}/{$idPad}";
    }

    public function index()
    {
        return view('dosen.pengajuan.index');
    }

    public function data()
    {
        $id_users = Auth::id();

        $query = PengajuanPraktikum::with(['lab', 'makul', 'kategori', 'user.dosen'])
            ->where('id_users', $id_users)
            ->orderBy('created_at', 'desc');

        return datatables()
            ->of($query)
            ->addColumn('nomor_registrasi', function ($row) {
                return $this->generateSmartId(
                    $row->kategori->nama_kategori ?? null,
                    $row->created_at,
                    $row->lab->kode ?? null,
                    $row->id_pengajuan
                );
            })
            ->addColumn('dosen_nama', function ($row) {
                return $row->user && $row->user->dosen ? $row->user->dosen->nama : ($row->user ? $row->user->username : '-');
            })
            ->addColumn('lab_nama', function ($row) {
                return $row->lab ? $row->lab->nama : '-';
            })
            ->addColumn('makul_nama', function ($row) {
                return $row->makul ? $row->makul->kode . ' - ' . $row->makul->nama : '-';
            })
            ->editColumn('tanggal', function ($row) {
                return Carbon::parse($row->tanggal)->translatedFormat('d F Y');
            })
            ->editColumn('jam_mulai', function ($row) {
                return substr($row->jam_mulai, 0, 5) . ' WIB';
            })
            ->editColumn('jam_selesai', function ($row) {
                return substr($row->jam_selesai, 0, 5) . ' WIB';
            })
            ->addColumn('status_badge', function ($row) {
                $status = $row->status;

                if ($status == 'Disetujui') {
                    return '<span class="badge bg-success text-white px-2 py-1">Telah Disetujui</span>';
                } elseif (str_contains($status, 'Ditolak')) {
                    return '<span class="badge bg-danger text-white px-2 py-1">Ditolak</span>';
                } else {
                    return '<span class="badge bg-warning text-dark px-2 py-1">Diproses</span>';
                }
            })
            ->addColumn('aksi', function ($row) {
                $btnDetail = '<a href="' . route('pengajuan.show', $row->id_pengajuan) . '" class="btn btn-rounded btn-sm btn-info mr-2" title="Detail"><i class="fa fa-eye"></i></a>';

                $fileUrl = $row->jobsheet ? Storage::url($row->jobsheet) : '#';
                $btnFile = '<a href="' . $fileUrl . '" target="_blank" class="btn btn-rounded btn-sm btn-outline-dark" title="Lihat Jobsheet"><i class="fas fa-file-pdf"></i></a>';

                return '<div class="d-flex justify-content-center">' . $btnDetail . $btnFile . '</div>';
            })
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        $lab = Laboratorium::where('status', 'Aktif')->orderBy('nama', 'asc')->get();

        $dosen = Auth::user()->dosen;
        $idProdi = null;

        if ($dosen) {
            $idProdi = $dosen instanceof \Illuminate\Support\Collection
                ? ($dosen->first()->id_prodi ?? null)
                : ($dosen->id_prodi ?? null);
        }

        $makul = $idProdi
            ? MataKuliah::where('id_prodi', $idProdi)->orderBy('nama', 'asc')->get()
            : MataKuliah::orderBy('nama', 'asc')->get();

        $minDate = Carbon::now()->format('Y-m-d');
        $maxDate = Carbon::now()->addDays(6)->format('Y-m-d');

        return view('dosen.pengajuan.create', compact('kategori', 'lab', 'makul', 'minDate', 'maxDate'));
    }

    public function store(Request $request)
    {
        $maxDate = Carbon::now()->addDays(6)->format('Y-m-d');

        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_lab'      => 'required|exists:laboratorium,id_lab',
            'id_makul'    => 'required|exists:mata_kuliah,id_makul',
            'tanggal'     => 'required|date|after_or_equal:today|before_or_equal:' . $maxDate,
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jobsheet'    => 'required|mimes:pdf|max:5120',
        ], [
            'tanggal.before_or_equal' => 'Maksimal tanggal peminjaman adalah 1 minggu dari hari ini.',
            'jam_selesai.after'       => 'Jam selesai harus lebih besar dari jam mulai.'
        ]);

        $tanggal = $request->tanggal;
        $jamMulai = $request->jam_mulai;
        $jamSelesai = $request->jam_selesai;
        $idLab = $request->id_lab;
        $userId = Auth::id();

        // =========================================================================
        // LOGIKA 1: Cek apakah Dosen ini sudah ada pengajuan di waktu yang sama
        // =========================================================================
        $cekDiriSendiri = PengajuanPraktikum::where('id_users', $userId)
            ->where('tanggal', $tanggal)
            ->whereNotIn('status', ['Ditolak Kaprodi', 'Ditolak Super Admin'])
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                // Rumus Overlap Waktu: (StartA < EndB) AND (EndA > StartB)
                $query->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            })->first();

        if ($cekDiriSendiri) {
            return redirect()->back()->with('error', 'Gagal! Anda sudah memiliki jadwal pengajuan lain pada tanggal dan rentang waktu yang berbenturan dengan ini.')->withInput();
        }

        // =========================================================================
        // LOGIKA 2: Cek apakah Lab sudah dibooking/diajukan orang lain di waktu tsb
        // =========================================================================
        $cekLabBentrok = PengajuanPraktikum::where('id_lab', $idLab)
            ->where('tanggal', $tanggal)
            ->whereNotIn('status', ['Ditolak Kaprodi', 'Ditolak Super Admin'])
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            })->first();

        if ($cekLabBentrok) {
            $statusBentrok = $cekLabBentrok->status == 'Disetujui' ? 'sudah disetujui (dibooking)' : 'sedang dalam proses pengajuan';
            return redirect()->back()->with('error', "Gagal! Laboratorium tersebut {$statusBentrok} oleh dosen lain pada rentang waktu yang Anda pilih. Silakan pilih jam atau lab lain.")->withInput();
        }

        try {
            $path = null;
            if ($request->hasFile('jobsheet')) {
                $file = $request->file('jobsheet');
                $makul = MataKuliah::find($request->id_makul);
                $kodeMakul = $makul ? strtoupper($makul->kode) : 'UMUM';
                $dateStr = Carbon::now()->format('Ymd');
                $fileName = "Jobsheet_{$kodeMakul}_{$dateStr}_" . time() . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs('pengajuan/jobsheets', $fileName, 'public');
            }

            PengajuanPraktikum::create([
                'id_users'    => $userId,
                'id_kategori' => $request->id_kategori,
                'id_lab'      => $idLab,
                'id_makul'    => $request->id_makul,
                'tanggal'     => $tanggal,
                'jam_mulai'   => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'jobsheet'    => $path,
                'status'      => 'Menunggu Kaprodi',
                'catatan'     => null
            ]);

            return redirect()->route('dashboard')->with('success', 'Pengajuan berhasil dikirim. Menunggu verifikasi Kaprodi.');
        } catch (\Exception $e) {
            Log::error('Error submit pengajuan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data.')->withInput();
        }
    }

    public function show($id)
    {
        $pengajuan = PengajuanPraktikum::with(['user.dosen', 'lab', 'makul', 'kategori'])->findOrFail($id);
        $user = Auth::user();

        if ($user->role == 'Dosen' && $pengajuan->id_users != $user->id) {
            abort(403, 'Anda tidak diizinkan melihat detail pengajuan dosen lain.');
        }

        $nomorReg = $this->generateSmartId(
            $pengajuan->kategori->nama_kategori ?? null,
            $pengajuan->created_at,
            $pengajuan->lab->kode ?? null,
            $pengajuan->id_pengajuan
        );

        $isOwner   = $user->id == $pengajuan->id_users;
        $statusDB  = $pengajuan->status;
        $namaDosen = $pengajuan->user->dosen->nama ?? $pengajuan->user->username ?? 'Unknown';
        $namaMakul = $pengajuan->makul ? "{$pengajuan->makul->nama} ({$pengajuan->makul->kode})" : '-';
        $namaLab   = $pengajuan->lab->nama ?? 'Laboratorium Tidak Diketahui';
        $tglPrak   = Carbon::parse($pengajuan->tanggal)->translatedFormat('l, d F Y');
        $waktuPrak = substr($pengajuan->jam_mulai, 0, 5) . ' - ' . substr($pengajuan->jam_selesai, 0, 5) . ' WIB';
        $tglDibuat = Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y, H:i');

        $canReviewKaprodi = $user->role == 'Kaprodi' && $statusDB == 'Menunggu Kaprodi';
        $canReviewAdmin   = $user->role == 'Super Admin' && $statusDB == 'Menunggu Super Admin';
        $canReview        = $canReviewKaprodi || $canReviewAdmin;
        $roleTipe         = $canReviewKaprodi ? 'kaprodi' : 'admin';

        $statusConfig = [
            'Menunggu Kaprodi'     => ['label' => 'Menunggu Verifikasi Kaprodi', 'color' => 'warning', 'icon' => 'clock'],
            'Menunggu Super Admin' => ['label' => 'Menunggu Finalisasi Super Admin', 'color' => 'primary', 'icon' => 'loader'],
            'Disetujui'            => ['label' => 'Telah Disetujui', 'color' => 'success', 'icon' => 'check'],
        ];
        $uiStatus = $statusConfig[$statusDB] ?? ['label' => 'Ditolak', 'color' => 'danger', 'icon' => 'x'];

        $tl_1 = 'done';
        $tl_2 = 'pending';
        if (in_array($statusDB, ['Menunggu Super Admin', 'Disetujui', 'Ditolak Super Admin'])) {
            $tl_2 = 'done';
        } elseif ($statusDB == 'Ditolak Kaprodi') {
            $tl_2 = 'rejected';
        } elseif ($statusDB == 'Menunggu Kaprodi') {
            $tl_2 = 'active';
        }

        $tl_3 = 'pending';
        if ($statusDB == 'Disetujui') {
            $tl_3 = 'done';
        } elseif ($statusDB == 'Ditolak Super Admin') {
            $tl_3 = 'rejected';
        } elseif ($statusDB == 'Menunggu Super Admin') {
            $tl_3 = 'active';
        }

        return view('pengajuan.show', compact(
            'pengajuan',
            'isOwner',
            'statusDB',
            'nomorReg',
            'namaDosen',
            'namaMakul',
            'namaLab',
            'tglPrak',
            'waktuPrak',
            'tglDibuat',
            'canReviewKaprodi',
            'canReviewAdmin',
            'canReview',
            'roleTipe',
            'uiStatus',
            'tl_1',
            'tl_2',
            'tl_3'
        ));
    }

    public function verifyKaprodi(Request $request, $id)
    {
        $request->validate([
            'status'  => 'required|in:Terima,Tolak',
            'catatan' => 'nullable|string'
        ]);

        try {
            $pengajuan = PengajuanPraktikum::findOrFail($id);
            $statusBaru = $request->status == 'Terima' ? 'Menunggu Super Admin' : 'Ditolak Kaprodi';

            $pengajuan->update([
                'status'  => $statusBaru,
                'catatan' => $request->catatan
            ]);

            return response()->json(['message' => 'Review Kaprodi berhasil disimpan.'], 200);
        } catch (\Exception $e) {
            Log::error('Error verify Kaprodi: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memproses verifikasi.'], 500);
        }
    }

    public function verifySuperAdmin(Request $request, $id)
    {
        $request->validate([
            'status'  => 'required|in:Terima,Tolak',
            'catatan' => 'nullable|string'
        ]);

        try {
            $pengajuan = PengajuanPraktikum::findOrFail($id);
            $statusBaru = $request->status == 'Terima' ? 'Disetujui' : 'Ditolak Super Admin';

            $pengajuan->update([
                'status'  => $statusBaru,
                'catatan' => $request->catatan
            ]);

            return response()->json(['message' => 'Finalisasi selesai. Jadwal telah diperbarui.'], 200);
        } catch (\Exception $e) {
            Log::error('Error verify Admin: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memproses finalisasi.'], 500);
        }
    }
}
