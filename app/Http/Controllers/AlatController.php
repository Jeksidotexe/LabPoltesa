<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AlatController extends Controller
{
    public function index()
    {
        // Panggil data lab
        $lab = Laboratorium::orderBy('nama', 'asc')->get();
        return view('admin.alat.index', compact('lab'));
    }

    public function data(Request $request)
    {
        $query = Alat::with('lab')->orderBy('id_alat', 'desc');

        // Logika Filter Laboratorium
        if ($request->has('id_lab') && $request->id_lab != '') {
            $query->where('id_lab', $request->id_lab);
        }

        $alat = $query->get();

        return datatables()
            ->of($alat)
            ->addIndexColumn()
            ->addColumn('foto', function ($alat) {
                $url = $alat->foto && Storage::disk('public')->exists($alat->foto)
                    ? asset('storage/' . $alat->foto)
                    : 'https://placehold.co/200x150/eeeeee/999999?text=No+Foto';

                return '<img src="' . $url . '" alt="foto alat" class="rounded shadow-sm border" width="60" height="40" style="object-fit: cover;">';
            })
            ->addColumn('nama_lab', function ($alat) {
                return $alat->lab ? $alat->lab->nama : '-';
            })
            ->addColumn('kondisi_badge', function ($alat) {
                if ($alat->kondisi == 'Baik') {
                    return '<span class="badge bg-success text-white px-2 py-1">Baik</span>';
                } elseif ($alat->kondisi == 'Rusak Ringan') {
                    return '<span class="badge bg-warning text-dark px-2 py-1">Rusak Ringan</span>';
                } else {
                    return '<span class="badge bg-danger text-white px-2 py-1">Rusak Berat</span>';
                }
            })
            ->addColumn('aksi', function ($alat) {
                return '
                <div class="d-flex justify-content-center">
                    <a href="' . route('alat.show', $alat->id_alat) . '" class="btn btn-rounded btn-sm btn-info me-1 mr-1" title="Detail">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="' . route('alat.edit', $alat->id_alat) . '" class="btn btn-rounded btn-sm btn-dark me-1 mr-1" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button onclick="deleteData(`' . route('alat.destroy', $alat->id_alat) . '`)" class="btn btn-rounded btn-sm btn-danger" title="Hapus">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                ';
            })
            ->rawColumns(['foto', 'kondisi_badge', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $lab = Laboratorium::orderBy('nama', 'asc')->get();
        return view('admin.alat.create', compact('lab'));
    }

    public function store(Request $request)
    {
        $rules = [
            'id_lab'           => 'required|exists:laboratorium,id_lab',
            'nama_alat'        => 'required|string|max:100',
            'spesifikasi_alat' => 'required|string',
            'instruksi_kerja'  => 'required|string',
            'tahun_pengadaan'  => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah'           => 'required|integer|min:0',
            'kondisi'          => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            if ($request->hasFile('foto')) {
                $validatedData['foto'] = $request->file('foto')->store('alat/images', 'public');
            }

            Alat::create($validatedData);
            return redirect()->route('alat.index')->with('success', 'Data Alat berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating alat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }

    public function show($id)
    {
        $alat = Alat::with('lab')->findOrFail($id);

        $urlFoto = $alat->foto && Storage::disk('public')->exists($alat->foto)
            ? asset('storage/' . $alat->foto)
            : 'https://placehold.co/600x400/eeeeee/999999?text=Tidak+Ada+Foto';

        // LOGIKA PERHITUNGAN STOK
        $dipinjam = DB::table('pengajuan_alat')
            ->where('id_alat', $alat->id_alat)
            ->where('status_kembali', 'Belum')
            ->sum('jumlah_pinjam');

        // Total Unit Keseluruhan (Tersedia + Dipinjam + Rusak Ringan + Rusak Berat)
        $totalFisik = $alat->jumlah + $dipinjam + $alat->jumlah_rusak_ringan + $alat->jumlah_rusak_berat;

        return view('admin.alat.show', compact('alat', 'urlFoto', 'dipinjam', 'totalFisik'));
    }

    public function edit($id)
    {
        $alat = Alat::findOrFail($id);
        $lab = Laboratorium::orderBy('nama', 'asc')->get();

        $hasFoto = $alat->foto && Storage::disk('public')->exists($alat->foto);
        $urlFoto = $hasFoto ? asset('storage/' . $alat->foto) : '';

        return view('admin.alat.edit', compact('alat', 'lab', 'hasFoto', 'urlFoto'));
    }

    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $rules = [
            'id_lab'           => 'required|exists:laboratorium,id_lab',
            'nama_alat'        => 'required|string|max:100',
            'spesifikasi_alat' => 'required|string',
            'instruksi_kerja'  => 'required|string',
            'tahun_pengadaan'  => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah'           => 'required|integer|min:0',
            'kondisi'          => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            if ($request->hasFile('foto')) {
                if ($alat->foto && Storage::disk('public')->exists($alat->foto)) {
                    Storage::disk('public')->delete($alat->foto);
                }
                $validatedData['foto'] = $request->file('foto')->store('alat/images', 'public');
            }

            $alat->update($validatedData);
            return redirect()->route('alat.index')->with('success', 'Data Alat berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating alat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $alat = Alat::findOrFail($id);
            if ($alat->foto && Storage::disk('public')->exists($alat->foto)) {
                Storage::disk('public')->delete($alat->foto);
            }
            $alat->delete();
            return response()->json(['message' => 'Data Alat berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }

    public function repair(Request $request, $id)
    {
        $request->validate([
            'jenis_rusak' => 'required|in:ringan,berat',
            'jumlah_diperbaiki' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $alat = Alat::findOrFail($id);
            $jumlah = (int) $request->jumlah_diperbaiki;

            // Logika Pemindahan Stok
            if ($request->jenis_rusak == 'ringan') {
                if ($alat->jumlah_rusak_ringan < $jumlah) {
                    return redirect()->back()->with('error', 'Jumlah perbaikan melebihi total stok yang rusak ringan.');
                }
                $alat->decrement('jumlah_rusak_ringan', $jumlah);
            } else {
                if ($alat->jumlah_rusak_berat < $jumlah) {
                    return redirect()->back()->with('error', 'Jumlah perbaikan melebihi total stok yang rusak berat.');
                }
                $alat->decrement('jumlah_rusak_berat', $jumlah);
            }

            // Kembalikan alat ke stok kondisi Baik
            $alat->increment('jumlah', $jumlah);

            DB::commit();
            return redirect()->back()->with('success', 'Status alat berhasil diperbarui menjadi Kondisi Baik.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error repair alat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses data perbaikan.');
        }
    }
}
