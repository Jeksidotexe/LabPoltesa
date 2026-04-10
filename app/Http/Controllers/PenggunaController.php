<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenggunaController extends Controller
{
    public function index()
    {
        return view('super_admin.pengguna.index');
    }

    public function data(Request $request)
    {
        $query = User::with('prodi')->orderBy('id', 'desc');

        // LOGIKA FILTER ROLE
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        $pengguna = $query->get();

        return datatables()
            ->of($pengguna)
            ->addIndexColumn()
            ->addColumn('foto', function ($pengguna) {
                if ($pengguna->foto && Storage::disk('public')->exists($pengguna->foto)) {
                    $url = Storage::url($pengguna->foto);
                    return '<img src="' . $url . '" alt="foto" class="rounded-circle" width="40" height="40" style="object-fit: cover;">';
                }
                return '<div class="rounded-circle bg-light d-inline-flex justify-content-center align-items-center text-secondary" style="width: 40px; height: 40px;"><i class="fas fa-user"></i></div>';
            })
            ->addColumn('nama_lengkap', function ($pengguna) {
                if ($pengguna->nama) {
                    return trim($pengguna->gelar_depan . ' ' . $pengguna->nama . ' ' . $pengguna->gelar_belakang);
                }
                return $pengguna->username; // Fallback ke username jika nama belum diisi
            })
            ->addColumn('nip', function ($pengguna) {
                return $pengguna->nip ? $pengguna->nip : '-';
            })
            ->addColumn('jenis_kelamin', function ($pengguna) {
                if ($pengguna->jenis_kelamin == 'L') return 'Laki-Laki';
                if ($pengguna->jenis_kelamin == 'P') return 'Perempuan';
                return '-';
            })
            ->addColumn('jabatan', function ($pengguna) {
                return $pengguna->jabatan ? $pengguna->jabatan : '-';
            })
            ->addColumn('status_badge', function ($pengguna) {
                $status = $pengguna->status ?? 'Aktif'; // Default aktif
                if ($status == 'Aktif') {
                    return '<span class="badge badge-success px-2 py-1">Aktif</span>';
                }
                return '<span class="badge badge-danger px-2 py-1">Nonaktif</span>';
            })
            ->addColumn('aksi', function ($pengguna) {
                return '
                <div class="d-flex justify-content-center">
                    <a href="' . route('pengguna.show', $pengguna->id) . '" class="btn btn-rounded btn-sm btn-info me-1 mr-1" title="Detail">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="' . route('pengguna.edit', $pengguna->id) . '" class="btn btn-rounded btn-sm btn-dark me-1 mr-1" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button onclick="deleteData(`' . route('pengguna.destroy', $pengguna->id) . '`)" class="btn btn-rounded btn-sm btn-danger" title="Hapus">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                ';
            })
            ->rawColumns(['foto', 'status_badge', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $prodi = ProgramStudi::orderBy('nama_prodi', 'asc')->get();
        return view('super_admin.pengguna.create', compact('prodi'));
    }

    public function store(Request $request): RedirectResponse
    {
        // LOGIKA AUTO-GENERATE USERNAME & PASSWORD
        // Gunakan NIP untuk mengisi username dan password.
        $generatedUsername = $request->nip;
        $generatedPassword = $request->nip;

        $request->merge([
            'username' => $generatedUsername,
            'password' => $generatedPassword,
            'password_confirmation' => $generatedPassword,
        ]);

        $rules = [
            'role'              => 'required|in:Super Admin,Admin,Dosen,Kaprodi,Kajur',
            'username'          => 'required|string|max:255|unique:users,username',
            'password'          => 'required|string|confirmed',
            'nip'               => 'nullable|string|unique:users,nip',
            'nama'              => 'required|string|max:100',
            'gelar_depan'       => 'nullable|string|max:20',
            'gelar_belakang'    => 'nullable|string|max:20',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:L,P',
            'id_prodi'          => 'nullable|integer',
            'jabatan'           => 'nullable|string|max:50',
            'email'             => 'required|email|max:100',
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
                $fotoPath = null;
                if ($request->hasFile('foto')) {
                    $fotoPath = $request->file('foto')->store('users/images', 'public');
                }

                User::create([
                    'username'          => $validatedData['username'],
                    'password'          => Hash::make($validatedData['password']),
                    'role'              => $validatedData['role'],
                    'nip'               => $validatedData['nip'] ?? null,
                    'nama'              => $validatedData['nama'],
                    'gelar_depan'       => $validatedData['gelar_depan'] ?? null,
                    'gelar_belakang'    => $validatedData['gelar_belakang'] ?? null,
                    'tanggal_lahir'     => $validatedData['tanggal_lahir'],
                    'jenis_kelamin'     => $validatedData['jenis_kelamin'],
                    'id_prodi'          => $validatedData['id_prodi'] ?? null,
                    'jabatan'           => $validatedData['jabatan'] ?? null,
                    'email'             => $validatedData['email'],
                    'telepon'           => $validatedData['telepon'],
                    'tanggal_bergabung' => $validatedData['tanggal_bergabung'],
                    'status'            => $validatedData['status'],
                    'foto'              => $fotoPath,
                ]);
            });

            return redirect()->route('pengguna.index')->with('success', 'Akun pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    public function show(string $id)
    {
        $pengguna = User::with('prodi')->findOrFail($id);

        if ($pengguna->foto && Storage::disk('public')->exists($pengguna->foto)) {
            $urlFoto = Storage::url($pengguna->foto);
        } else {
            $namaFallback = $pengguna->nama ?? $pengguna->username;
            $urlFoto = 'https://ui-avatars.com/api/?name=' . urlencode($namaFallback) . '&background=e9ecef&color=343a40&size=140';
        }

        if ($pengguna->nama) {
            $namaLengkap = trim($pengguna->gelar_depan . ' ' . $pengguna->nama . ' ' . $pengguna->gelar_belakang);
        } else {
            $namaLengkap = $pengguna->username;
        }

        $roleColor = match ($pengguna->role) {
            'Super Admin' => 'danger',
            'Admin'       => 'success',
            'Kaprodi'     => 'warning',
            'Dosen'       => 'info',
            default       => 'primary',
        };

        $masaKerjaText = '-';
        if ($pengguna->tanggal_bergabung) {
            $masaKerja = \Carbon\Carbon::parse($pengguna->tanggal_bergabung)->diff(\Carbon\Carbon::now());
            $masaKerjaText = "{$masaKerja->y} Tahun, {$masaKerja->m} Bulan";
        }

        return view('super_admin.pengguna.show', compact('pengguna', 'urlFoto', 'namaLengkap', 'roleColor', 'masaKerjaText'));
    }

    public function edit(string $id)
    {
        $pengguna = User::findOrFail($id);
        $prodi = ProgramStudi::orderBy('nama_prodi', 'asc')->get();
        return view('super_admin.pengguna.edit', compact('pengguna', 'prodi'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $pengguna = User::findOrFail($id);

        // LOGIKA AUTO-UPDATE USERNAME JIKA NIP DIUBAH
        $generatedUsername = $request->nip ?? explode('@', $request->email)[0];
        $request->merge(['username' => $generatedUsername]);

        $rules = [
            'role'              => 'required|in:Super Admin,Admin,Dosen,Kaprodi,Kajur',
            'username'          => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($pengguna->id)],
            'nip'               => ['nullable', 'string', Rule::unique('users', 'nip')->ignore($pengguna->id)],
            'nama'              => 'required|string|max:100',
            'gelar_depan'       => 'nullable|string|max:20',
            'gelar_belakang'    => 'nullable|string|max:20',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:L,P',
            'id_prodi'          => 'nullable|integer',
            'jabatan'           => 'nullable|string|max:50',
            'email'             => 'required|email|max:100',
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
            DB::transaction(function () use ($validatedData, $request, $pengguna) {
                $fotoPath = $pengguna->foto;
                if ($request->hasFile('foto')) {
                    if ($pengguna->foto && Storage::disk('public')->exists($pengguna->foto)) {
                        Storage::disk('public')->delete($pengguna->foto);
                    }
                    $fotoPath = $request->file('foto')->store('users/images', 'public');
                }

                $userData = [
                    'username'          => $validatedData['username'],
                    'role'              => $validatedData['role'],
                    'nip'               => $validatedData['nip'] ?? null,
                    'nama'              => $validatedData['nama'],
                    'gelar_depan'       => $validatedData['gelar_depan'] ?? null,
                    'gelar_belakang'    => $validatedData['gelar_belakang'] ?? null,
                    'tanggal_lahir'     => $validatedData['tanggal_lahir'],
                    'jenis_kelamin'     => $validatedData['jenis_kelamin'],
                    'id_prodi'          => $validatedData['id_prodi'] ?? null,
                    'jabatan'           => $validatedData['jabatan'] ?? null,
                    'email'             => $validatedData['email'],
                    'telepon'           => $validatedData['telepon'],
                    'tanggal_bergabung' => $validatedData['tanggal_bergabung'],
                    'status'            => $validatedData['status'],
                    'foto'              => $fotoPath,
                ];

                $pengguna->update($userData);
            });

            return redirect()->route('pengguna.index')->with('success', 'Akun berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.')->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $pengguna = User::findOrFail($id);
            if ($pengguna->foto && Storage::disk('public')->exists($pengguna->foto)) {
                Storage::disk('public')->delete($pengguna->foto);
            }
            $pengguna->delete();
            return response()->json(['message' => 'Data berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
