<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
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
            ->rawColumns(['foto', 'aksi'])
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
            'spesifikasi_alat' => 'required|string|max:255',
            'instruksi_kerja'  => 'required|string|max:255',
            'tahun_pengadaan'  => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah'           => 'required|integer|min:1',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Validasi foto alat
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            // Upload Foto Alat
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
        // Panggil data alat beserta relasi nama laboratoriumnya
        $alat = Alat::with('lab')->findOrFail($id);

        $urlFoto = $alat->foto && Storage::disk('public')->exists($alat->foto)
            ? asset('storage/' . $alat->foto)
            : 'https://placehold.co/600x400/eeeeee/999999?text=Tidak+Ada+Foto';

        // Kirim variabel $alat dan $urlFoto ke view
        return view('admin.alat.show', compact('alat', 'urlFoto'));
    }

    public function edit($id)
    {
        $alat = Alat::findOrFail($id);
        $lab = Laboratorium::orderBy('nama', 'asc')->get();

        $hasFoto = $alat->foto && Storage::disk('public')->exists($alat->foto);
        $urlFoto = $hasFoto ? asset('storage/' . $alat->foto) : '';

        // Kirim variabel $alat, $lab, $hasFoto, dan $urlFoto ke view
        return view('admin.alat.edit', compact('alat', 'lab', 'hasFoto', 'urlFoto'));
    }

    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $rules = [
            'id_lab'           => 'required|exists:laboratorium,id_lab',
            'nama_alat'        => 'required|string|max:100',
            'spesifikasi_alat' => 'required|string|max:255',
            'instruksi_kerja'  => 'required|string|max:255',
            'tahun_pengadaan'  => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah'           => 'required|integer|min:1',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Validasi foto alat
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            // Proses Upload / Timpa Foto
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
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

            // Hapus file fisik foto jika ada
            if ($alat->foto && Storage::disk('public')->exists($alat->foto)) {
                Storage::disk('public')->delete($alat->foto);
            }

            $alat->delete();
            return response()->json(['message' => 'Data Alat berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
