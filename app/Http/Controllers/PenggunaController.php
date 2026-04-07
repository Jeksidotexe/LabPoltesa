<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class PenggunaController extends Controller
{
    public function index()
    {
        return view('super_admin.pengguna.index');
    }

    public function data()
    {
        // Menyembunyikan Dosen, karena Dosen dikelola di menu Data Dosen
        // $pengguna = User::where('role', '!=', 'Dosen')->orderBy('id', 'desc')->get();
        // Menampilkan semua user termasuk Dosen
        $pengguna = User::orderBy('id', 'desc')->get();

        return datatables()
            ->of($pengguna)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pengguna) {
                return '
                <div class="d-flex justify-content-center">
                    <a href="' . route('pengguna.edit', $pengguna->id) . '" class="btn btn-rounded btn-sm btn-dark me-1 mr-1" title="Detail">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button onclick="deleteData(`' . route('pengguna.destroy', $pengguna->id) . '`)" class="btn btn-rounded btn-sm btn-danger" title="Hapus">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('super_admin.pengguna.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'username' => 'required|string|max:255|unique:users,username',
            'role'     => 'required|in:Super Admin,Admin,Kaprodi,Kajur',
            'password' => 'required|string|min:8|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            User::create([
                'username' => $validatedData['username'],
                'role'     => $validatedData['role'],
                'password' => Hash::make($validatedData['password']),
            ]);
            return redirect()->route('pengguna.index')->with('success', 'Akun pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    public function edit(string $id)
    {
        $pengguna = User::findOrFail($id);
        return view('super_admin.pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $pengguna = User::findOrFail($id);

        $rules = [
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($pengguna->id)],
            'role'     => 'required|in:Super Admin,Admin,Kaprodi,Kajur',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        $data = [
            'username' => $validatedData['username'],
            'role'     => $validatedData['role'],
        ];

        if (!empty($validatedData['password'])) {
            $data['password'] = Hash::make($validatedData['password']);
        }

        try {
            $pengguna->update($data);
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
            $pengguna->delete();
            return response()->json(['message' => 'Data berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
