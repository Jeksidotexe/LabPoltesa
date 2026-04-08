<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    public function index()
    {
        return view('super_admin.dosen.index');
    }

    public function data()
    {
        $dosen = Dosen::with('prodi')->orderBy('id_dosen', 'desc')->get();

        return datatables()
            ->of($dosen)
            ->addIndexColumn()
            ->addColumn('foto', function ($dosen) {
                // Logika pemanggilan foto persis seperti PenggunaController
                $url = $dosen->foto ? Storage::url($dosen->foto) : asset('images/default.jpg');
                return '<img src="' . $url . '" alt="foto" class="rounded-circle" width="40" height="40" style="object-fit: cover;">';
            })
            ->addColumn('nama_prodi', function ($dosen) {
                return $dosen->prodi ? $dosen->prodi->nama_prodi : '-';
            })
            ->addColumn('aksi', function ($dosen) {
                return '
                <div class="d-flex justify-content-center">
                    <a href="' . route('dosen.show', $dosen->id_dosen) . '" class="btn btn-rounded btn-sm btn-info me-1 mr-1" title="Detail">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="' . route('dosen.edit', $dosen->id_dosen) . '" class="btn btn-rounded btn-sm btn-dark me-1 mr-1" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button onclick="deleteData(`' . route('dosen.destroy', $dosen->id_dosen) . '`)" class="btn btn-rounded btn-sm btn-danger" title="Hapus">
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
        $prodi = ProgramStudi::orderBy('nama_prodi', 'asc')->get();
        return view('super_admin.dosen.create', compact('prodi'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nip'               => 'required|string|unique:dosen,nip',
            'nama'              => 'required|string|max:100',
            'gelar_depan'       => 'nullable|string|max:20',
            'gelar_belakang'    => 'nullable|string|max:20',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:L,P',
            'id_prodi'          => 'required|integer',
            'jabatan'           => 'required|string|max:50',
            'email'             => 'required|email|max:100|unique:dosen,email',
            'telepon'           => 'required|string|max:20',
            'tanggal_bergabung' => 'required|date',
            'status'            => 'required|in:Aktif,Nonaktif',
            'foto'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            DB::transaction(function () use ($validatedData, $request) {
                // 1. Auto-Generate Akun User
                $user = User::create([
                    'username' => $validatedData['nip'],
                    'password' => Hash::make($validatedData['nip']),
                    'role'     => 'Dosen',
                ]);

                // 2. Upload Foto Dosen
                $fotoPath = null;
                if ($request->hasFile('foto')) {
                    $fotoPath = $request->file('foto')->store('dosen/images', 'public');
                }

                // 3. Simpan Profil Dosen
                Dosen::create([
                    'id_users'          => $user->id,
                    'nip'               => $validatedData['nip'],
                    'nama'              => $validatedData['nama'],
                    'gelar_depan'       => $validatedData['gelar_depan'] ?? '',
                    'gelar_belakang'    => $validatedData['gelar_belakang'] ?? '',
                    'tanggal_lahir'     => $validatedData['tanggal_lahir'],
                    'jenis_kelamin'     => $validatedData['jenis_kelamin'],
                    'id_prodi'          => $validatedData['id_prodi'],
                    'jabatan'           => $validatedData['jabatan'],
                    'email'             => $validatedData['email'],
                    'telepon'           => $validatedData['telepon'],
                    'foto'              => $fotoPath, // Langsung simpan null jika tidak ada
                    'tanggal_bergabung' => $validatedData['tanggal_bergabung'],
                    'status'            => $validatedData['status'],
                ]);
            });

            return redirect()->route('dosen.index')->with('success', 'Data Dosen berhasil ditambahkan. Akun login menggunakan NIP.');
        } catch (\Exception $e) {
            Log::error('Error creating dosen: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    public function show(string $id_dosen)
    {
        $dosen = Dosen::with(['prodi', 'users'])->findOrFail($id_dosen);
        return view('super_admin.dosen.show', compact('dosen'));
    }

    public function edit(string $id_dosen)
    {
        $dosen = Dosen::findOrFail($id_dosen);
        $prodi = ProgramStudi::orderBy('nama_prodi', 'asc')->get();

        return view('super_admin.dosen.edit', compact('dosen', 'prodi'));
    }

    public function update(Request $request, string $id_dosen)
    {
        $dosen = Dosen::findOrFail($id_dosen);

        $rules = [
            'nip'               => ['required', 'string', Rule::unique('dosen', 'nip')->ignore($dosen->id_dosen, 'id_dosen')],
            'nama'              => 'required|string|max:100',
            'gelar_depan'       => 'nullable|string|max:20',
            'gelar_belakang'    => 'nullable|string|max:20',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:L,P',
            'id_prodi'          => 'required|integer',
            'jabatan'           => 'required|string|max:50',
            'email'             => ['required', 'email', 'max:100', Rule::unique('dosen', 'email')->ignore($dosen->id_dosen, 'id_dosen')],
            'telepon'           => 'required|string|max:20',
            'tanggal_bergabung' => 'required|date',
            'status'            => 'required|in:Aktif,Nonaktif',
            'foto'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            DB::transaction(function () use ($validatedData, $request, $dosen) {
                if ($dosen->nip !== $validatedData['nip']) {
                    User::where('id', $dosen->id_users)->update([
                        'username' => $validatedData['nip']
                    ]);
                }

                // Logika Upload/Hapus Foto persis PenggunaController
                $fotoPath = $dosen->foto;
                if ($request->hasFile('foto')) {
                    if ($dosen->foto && Storage::disk('public')->exists($dosen->foto)) {
                        Storage::disk('public')->delete($dosen->foto);
                    }
                    $fotoPath = $request->file('foto')->store('dosen/images', 'public');
                }

                $dosen->update([
                    'nip'               => $validatedData['nip'],
                    'nama'              => $validatedData['nama'],
                    'gelar_depan'       => $validatedData['gelar_depan'] ?? '',
                    'gelar_belakang'    => $validatedData['gelar_belakang'] ?? '',
                    'tanggal_lahir'     => $validatedData['tanggal_lahir'],
                    'jenis_kelamin'     => $validatedData['jenis_kelamin'],
                    'id_prodi'          => $validatedData['id_prodi'],
                    'jabatan'           => $validatedData['jabatan'],
                    'email'             => $validatedData['email'],
                    'telepon'           => $validatedData['telepon'],
                    'foto'              => $fotoPath,
                    'tanggal_bergabung' => $validatedData['tanggal_bergabung'],
                    'status'            => $validatedData['status'],
                ]);
            });

            return redirect()->route('dosen.index')->with('success', 'Data Dosen berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating dosen: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.')->withInput();
        }
    }

    public function destroy(string $id_dosen)
    {
        try {
            DB::transaction(function () use ($id_dosen) {
                $dosen = Dosen::findOrFail($id_dosen);
                $userId = $dosen->id_users;

                // Logika Hapus Foto persis PenggunaController
                if ($dosen->foto && Storage::disk('public')->exists($dosen->foto)) {
                    Storage::disk('public')->delete($dosen->foto);
                }

                $dosen->delete();
                User::where('id', $userId)->delete();
            });

            return response()->json(['message' => 'Data Dosen beserta akunnya berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting dosen: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
