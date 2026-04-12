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
        $user = Auth::user();

        $query = PengajuanPraktikum::with(['lab', 'makul', 'kategori', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter Berdasarkan Role
        if ($user->role === 'Dosen') {
            $query->where('id_users', $user->id);
        } elseif ($user->role === 'Kaprodi') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_prodi', $user->id_prodi);
            });
        } elseif ($user->role === 'Super Admin') {
            $query->whereIn('status', ['Menunggu Super Admin', 'Disetujui', 'Ditolak Super Admin']);
        }

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
                if ($row->user) {
                    if ($row->user->nama) {
                        $nama = $row->user->nama;
                        if ($row->user->gelar_depan) $nama = $row->user->gelar_depan . ' ' . $nama;
                        if ($row->user->gelar_belakang) $nama .= ', ' . $row->user->gelar_belakang;
                        return $nama;
                    }
                    return $row->user->username;
                }
                return '-';
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
                $btnFile = '<a href="' . $fileUrl . '" target="_blank" class="btn btn-rounded btn-sm btn-dark" title="Lihat Jobsheet"><i class="fas fa-file-pdf"></i></a>';

                return '<div class="d-flex justify-content-center">' . $btnDetail . $btnFile . '</div>';
            })
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        $lab = Laboratorium::where('status', 'Aktif')->orderBy('nama', 'asc')->get();

        $idProdi = Auth::user()->id_prodi;

        $makul = $idProdi
            ? MataKuliah::where('id_prodi', $idProdi)->orderBy('nama', 'asc')->get()
            : MataKuliah::orderBy('nama', 'asc')->get();

        $minDate = Carbon::now('Asia/Jakarta')->addDays(7)->format('Y-m-d');

        return view('dosen.pengajuan.create', compact('kategori', 'lab', 'makul', 'minDate'));
    }

    public function store(Request $request)
    {
        $minDate = Carbon::now('Asia/Jakarta')->addDays(7)->format('Y-m-d');

        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_lab'      => 'required|exists:laboratorium,id_lab',
            'id_makul'    => 'required|exists:mata_kuliah,id_makul',
            'tanggal'     => 'required|date|after_or_equal:' . $minDate,
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jobsheet'    => 'required|mimes:pdf|max:5120',
        ], [
            'tanggal.after_or_equal'  => 'Tanggal praktikum minimal harus 7 hari (seminggu) setelah tanggal pengajuan.',
            'jam_selesai.after'       => 'Jam selesai harus lebih besar dari jam mulai.'
        ]);

        $tanggal = $request->tanggal;
        $jamMulai = substr($request->jam_mulai, 0, 5);
        $jamSelesai = substr($request->jam_selesai, 0, 5);
        $idLab = $request->id_lab;
        $userId = Auth::id();

        // LOGIKA 1: Cek Diri Sendiri Bentrok
        $cekDiriSendiri = PengajuanPraktikum::where('id_users', $userId)
            ->whereDate('tanggal', $tanggal)
            ->whereNotIn('status', ['Ditolak Kaprodi', 'Ditolak Super Admin'])
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->whereTime('jam_mulai', '<', $jamSelesai)
                    ->whereTime('jam_selesai', '>', $jamMulai);
            })->first();

        if ($cekDiriSendiri) {
            return redirect()->back()->with('error', 'Gagal! Anda sudah memiliki jadwal pengajuan lain pada tanggal dan rentang waktu yang berbenturan dengan ini.')->withInput();
        }

        // LOGIKA 2: Cek Lab Bentrok dengan orang lain
        $cekLabBentrok = PengajuanPraktikum::where('id_lab', $idLab)
            ->whereDate('tanggal', $tanggal)
            ->whereNotIn('status', ['Ditolak Kaprodi', 'Ditolak Super Admin'])
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->whereTime('jam_mulai', '<', $jamSelesai)
                    ->whereTime('jam_selesai', '>', $jamMulai);
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
        $pengajuan = PengajuanPraktikum::with(['user', 'lab', 'makul', 'kategori'])->findOrFail($id);
        $user = Auth::user();

        // Security: Hanya Dosen Pemilik yang bisa lihat detailnya sendiri
        if ($user->role == 'Dosen' && $pengajuan->id_users != $user->id) {
            abort(403, 'Anda tidak diizinkan melihat detail pengajuan dosen lain.');
        }

        // Security: Hanya Kaprodi dari PRODI YANG SAMA yang bisa lihat detail dosen tersebut
        if ($user->role == 'Kaprodi' && $pengajuan->user->id_prodi != $user->id_prodi) {
            abort(403, 'Anda tidak diizinkan melihat atau memverifikasi pengajuan dari Program Studi lain.');
        }

        $nomorReg = $this->generateSmartId(
            $pengajuan->kategori->nama_kategori ?? null,
            $pengajuan->created_at,
            $pengajuan->lab->kode ?? null,
            $pengajuan->id_pengajuan
        );

        $isOwner   = $user->id == $pengajuan->id_users;
        $statusDB  = $pengajuan->status;

        $namaDosen = $pengajuan->user->username;
        if ($pengajuan->user->nama) {
            $namaDosen = $pengajuan->user->nama;
            if ($pengajuan->user->gelar_depan) $namaDosen = $pengajuan->user->gelar_depan . ' ' . $namaDosen;
            if ($pengajuan->user->gelar_belakang) $namaDosen .= ', ' . $pengajuan->user->gelar_belakang;
        }

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
            $pengajuan = PengajuanPraktikum::with('user')->findOrFail($id);
            $user = Auth::user();

            // Cek Keamanan Backend Jika Ditembak API secara Paksa
            if ($user->role == 'Kaprodi' && $pengajuan->user->id_prodi != $user->id_prodi) {
                return response()->json(['message' => 'Akses ditolak. Pengajuan berasal dari Program Studi yang berbeda.'], 403);
            }

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
