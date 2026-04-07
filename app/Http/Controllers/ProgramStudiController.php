<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProgramStudiController extends Controller
{
    public function index()
    {
        return view('super_admin.prodi.index');
    }

    public function data()
    {
        $prodi = ProgramStudi::orderBy('id_prodi', 'desc')->get();

        return datatables()
            ->of($prodi)
            ->addIndexColumn()
            ->addColumn('tanggal_berdiri', function ($prodi) {
                return \Carbon\Carbon::parse($prodi->tanggal_berdiri)->translatedFormat('d F Y');
            })
            ->addColumn('akreditasi', function ($prodi) {
                // Memberikan warna badge berbeda sesuai nilai akreditasi
                $color = 'secondary';
                if (in_array($prodi->akreditasi, ['A', 'Unggul'])) $color = 'success';
                elseif (in_array($prodi->akreditasi, ['B', 'Baik Sekali'])) $color = 'primary';
                elseif (in_array($prodi->akreditasi, ['C', 'Baik'])) $color = 'warning';

                return '<span class="badge bg-' . $color . ' text-white px-2 py-1">' . $prodi->akreditasi . '</span>';
            })
            ->addColumn('aksi', function ($prodi) {
                return '
                <div class="d-flex justify-content-center">
                    <a href="' . route('prodi.edit', $prodi->id_prodi) . '" class="btn btn-rounded btn-sm btn-dark me-1 mr-1" title="Edit Data">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button onclick="deleteData(`' . route('prodi.destroy', $prodi->id_prodi) . '`)" class="btn btn-rounded btn-sm btn-danger" title="Hapus Data">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                ';
            })
            ->rawColumns(['akreditasi', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('super_admin.prodi.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'kode'            => 'required|string|max:20|unique:program_studi,kode',
            'nama_prodi'      => 'required|string|max:100',
            'akreditasi'      => 'required|string|max:15',
            'tanggal_berdiri' => 'required|date',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            ProgramStudi::create($validator->validated());
            return redirect()->route('prodi.index')->with('success', 'Data Program Studi berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating prodi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    public function edit(string $id)
    {
        $prodi = ProgramStudi::findOrFail($id);
        return view('super_admin.prodi.edit', compact('prodi'));
    }

    public function update(Request $request, string $id)
    {
        $prodi = ProgramStudi::findOrFail($id);

        $rules = [
            'kode'            => ['required', 'string', 'max:20', Rule::unique('program_studi', 'kode')->ignore($prodi->id_prodi, 'id_prodi')],
            'nama_prodi'      => 'required|string|max:100',
            'akreditasi'      => 'required|string|max:15',
            'tanggal_berdiri' => 'required|date',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $prodi->update($validator->validated());
            return redirect()->route('prodi.index')->with('success', 'Data Program Studi berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating prodi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.')->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $prodi = ProgramStudi::findOrFail($id);
            $prodi->delete();
            return response()->json(['message' => 'Data Program Studi berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting prodi: ' . $e->getMessage());
            return response()->json(['message' => 'Tidak dapat menghapus data ini karena sedang digunakan di data Dosen/Mahasiswa.'], 500);
        }
    }
}
