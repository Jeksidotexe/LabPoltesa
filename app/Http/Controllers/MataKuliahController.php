<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    public function index()
    {
        return view('super_admin.makul.index');
    }

    public function data()
    {
        // Mengambil data makul beserta relasinya dengan prodi
        $makul = MataKuliah::with('prodi')->orderBy('id_makul', 'desc')->get();

        return datatables()
            ->of($makul)
            ->addIndexColumn()
            ->addColumn('sks', function ($makul) {
                // Pemanis UI: Tampilkan SKS dengan badge
                return '<span class="badge bg-primary text-white px-2 py-1 custom-shadow">' . $makul->sks . ' SKS</span>';
            })
            ->addColumn('nama_prodi', function ($makul) {
                return $makul->prodi ? $makul->prodi->nama_prodi : '-';
            })
            ->addColumn('aksi', function ($makul) {
                return '
                <div class="d-flex justify-content-center">
                    <a href="' . route('makul.edit', $makul->id_makul) . '" class="btn btn-rounded btn-sm btn-dark me-1 mr-1" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button onclick="deleteData(`' . route('makul.destroy', $makul->id_makul) . '`)" class="btn btn-rounded btn-sm btn-danger" title="Hapus">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                ';
            })
            ->rawColumns(['sks', 'aksi']) // Izinkan HTML dirender
            ->make(true);
    }

    public function create()
    {
        // Ambil data prodi untuk dropdown form
        $prodi = ProgramStudi::orderBy('nama_prodi', 'asc')->get();
        return view('super_admin.makul.create', compact('prodi'));
    }

    public function store(Request $request)
    {
        $rules = [
            'kode'      => 'required|string|max:20|unique:mata_kuliah,kode',
            'nama'      => 'required|string|max:100',
            'sks'       => 'required|integer|min:1|max:6',
            'id_prodi'  => 'required|exists:program_studi,id_prodi',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            MataKuliah::create($validator->validated());
            return redirect()->route('makul.index')->with('success', 'Data Mata Kuliah berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating makul: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    public function edit(string $id)
    {
        $makul = MataKuliah::findOrFail($id);
        $prodi = ProgramStudi::orderBy('nama_prodi', 'asc')->get();
        return view('super_admin.makul.edit', compact('makul', 'prodi'));
    }

    public function update(Request $request, string $id)
    {
        $makul = MataKuliah::findOrFail($id);

        $rules = [
            'kode'      => ['required', 'string', 'max:20', Rule::unique('mata_kuliah', 'kode')->ignore($makul->id_makul, 'id_makul')],
            'nama'      => 'required|string|max:100',
            'sks'       => 'required|integer|min:1|max:6',
            'id_prodi'  => 'required|exists:program_studi,id_prodi',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $makul->update($validator->validated());
            return redirect()->route('makul.index')->with('success', 'Data Mata Kuliah berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating makul: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.')->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $makul = MataKuliah::findOrFail($id);
            $makul->delete();
            return response()->json(['message' => 'Data Mata Kuliah berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting makul: ' . $e->getMessage());
            return response()->json(['message' => 'Tidak dapat menghapus data ini karena masih terkait dengan data Pengajuan Praktikum.'], 500);
        }
    }
}
