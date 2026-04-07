<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    // Halaman Detail Profil
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Jika user adalah Dosen, ambil juga data relasi dosen-nya
        if ($user->role == 'Dosen') {
            $user->load('dosen.prodi');
        }

        return view('profile.show', compact('user'));
    }

    // Halaman Form Edit Profil
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role == 'Dosen') {
            $user->load('dosen');
        }

        return view('profile.edit', compact('user'));
    }

    // Proses Simpan Update Profil
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Aturan Validasi Dasar (Untuk Semua User)
        $rules = [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // Password opsional
        ];

        // 2. Tambahan Aturan Validasi jika user adalah Dosen
        if ($user->role == 'Dosen') {
            $rules['email']   = ['required', 'email', 'max:100', Rule::unique('dosen')->ignore($user->dosen->id_dosen, 'id_dosen')];
            $rules['telepon'] = ['required', 'string', 'max:20'];
            $rules['foto']    = ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'];
        }

        $validatedData = $request->validate($rules);

        try {
            // 3. Update Akun User (Username & Password)
            $userData = ['username' => $validatedData['username']];
            if (!empty($validatedData['password'])) {
                $userData['password'] = Hash::make($validatedData['password']);
            }
            User::where('id', $user->id)->update($userData);

            // 4. Update Data Tambahan Khusus Dosen (Foto, Kontak)
            if ($user->role == 'Dosen' && $user->dosen) {
                $dosen = $user->dosen;
                $fotoPath = $dosen->foto;

                // Logika ganti foto
                if ($request->hasFile('foto')) {
                    if ($dosen->foto && Storage::disk('public')->exists($dosen->foto)) {
                        Storage::disk('public')->delete($dosen->foto);
                    }
                    $fotoPath = $request->file('foto')->store('dosen/images', 'public');
                }

                $dosen->update([
                    'email'   => $validatedData['email'],
                    'telepon' => $validatedData['telepon'],
                    'foto'    => $fotoPath,
                ]);
            }

            return redirect()->route('profile.show')->with('success', 'Profil Anda berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }
}
