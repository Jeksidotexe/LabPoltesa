<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ProfilController extends Controller
{
    // Halaman Detail Profil
    public function index()
    {
        $user = Auth::user();
        $user->load('prodi');

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            $urlFoto = Storage::url($user->foto);
        } else {
            $namaFallback = $user->nama ?? $user->username;
            $urlFoto = 'https://ui-avatars.com/api/?name=' . urlencode($namaFallback) . '&background=e9ecef&color=343a40&size=140';
        }

        if ($user->nama) {
            $namaLengkap = $user->nama;
            if ($user->gelar_depan) {
                $namaLengkap = $user->gelar_depan . ' ' . $namaLengkap;
            }
            if ($user->gelar_belakang) {
                $namaLengkap .= ', ' . $user->gelar_belakang;
            }
        } else {
            $namaLengkap = $user->username;
        }

        $roleColor = match ($user->role) {
            'Super Admin' => 'danger',
            'Admin'       => 'success',
            'Kaprodi'     => 'warning',
            'Dosen'       => 'info',
            default       => 'primary',
        };

        $masaKerjaText = '-';
        if ($user->tanggal_bergabung) {
            $masaKerja = \Carbon\Carbon::parse($user->tanggal_bergabung)->diff(\Carbon\Carbon::now());
            $masaKerjaText = "{$masaKerja->y} Tahun, {$masaKerja->m} Bulan";
        }

        // Ubah pemanggilan view menjadi profil.show
        return view('profil.show', compact('user', 'urlFoto', 'namaLengkap', 'roleColor', 'masaKerjaText'));
    }

    // Halaman Form Edit Profil
    public function edit()
    {
        $user = Auth::user();
        $user->load('prodi');

        $hasFoto = $user->foto && Storage::disk('public')->exists($user->foto);
        $urlFoto = $hasFoto ? Storage::url($user->foto) : '';

        // Ubah pemanggilan view menjadi profil.edit
        return view('profil.edit', compact('user', 'hasFoto', 'urlFoto'));
    }

    // Proses Simpan Update Profil
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'telepon'  => ['required', 'string', 'max:20'],
            'email'    => ['required', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'foto'     => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];

        if ($user->role == 'Super Admin') {
            $rules = array_merge($rules, [
                'nip'            => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
                'nama'           => ['required', 'string', 'max:100'],
                'gelar_depan'    => ['nullable', 'string', 'max:20'],
                'gelar_belakang' => ['nullable', 'string', 'max:20'],
                'jenis_kelamin'  => ['required', 'in:L,P'],
                'tanggal_lahir'  => ['required', 'date'],
                'jabatan'        => ['nullable', 'string', 'max:50'],
            ]);
        }

        $validatedData = $request->validate($rules);

        try {
            $fotoPath = $user->foto;
            if ($request->hasFile('foto')) {
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }
                $fotoPath = $request->file('foto')->store('users/images', 'public');
            }

            $userData = [
                'username' => $validatedData['username'],
                'telepon'  => $validatedData['telepon'],
                'email'    => $validatedData['email'],
                'foto'     => $fotoPath,
            ];

            if (!empty($validatedData['password'])) {
                $userData['password'] = Hash::make($validatedData['password']);
            }

            if ($user->role == 'Super Admin') {
                $userData = array_merge($userData, [
                    'nip'            => $validatedData['nip'] ?? null,
                    'nama'           => $validatedData['nama'],
                    'gelar_depan'    => $validatedData['gelar_depan'] ?? null,
                    'gelar_belakang' => $validatedData['gelar_belakang'] ?? null,
                    'jenis_kelamin'  => $validatedData['jenis_kelamin'],
                    'tanggal_lahir'  => $validatedData['tanggal_lahir'],
                    'jabatan'        => $validatedData['jabatan'] ?? null,
                ]);
            }

            User::where('id', $user->id)->update($userData);

            return redirect()->route('profil.show')->with('success', 'Profil Anda berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }
}
