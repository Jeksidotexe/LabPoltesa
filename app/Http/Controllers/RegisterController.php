<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $prodi = ProgramStudi::orderBy('nama_prodi', 'asc')->get();
        return view('auth.register', compact('prodi'));
    }

    public function register(Request $request)
    {
        $rules = [
            'nip'               => 'required|string|unique:users,nip|unique:users,username',
            'gelar_depan'       => 'nullable|string|max:20',
            'nama'              => 'required|string|max:100',
            'gelar_belakang'    => 'nullable|string|max:20',
            'id_prodi'          => 'required|integer',
            'jabatan'           => 'required|string|max:50',
            'email'             => 'required|email|max:100|unique:users,email',
            'telepon'           => 'required|string|max:20',
            'jenis_kelamin'     => 'required|in:L,P',
            'tanggal_lahir'     => 'required|date',
            'tanggal_bergabung' => 'required|date',
            'password'          => 'required|string|min:6|confirmed',
        ];

        $messages = [
            'nip.unique'         => 'NIP ini sudah terdaftar.',
            'email.unique'       => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 6 karakter.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('swal_error', $validator->errors()->first());
        }

        try {
            DB::transaction(function () use ($request) {
                User::create([
                    'username'          => $request->nip,
                    'password'          => Hash::make($request->password),
                    'role'              => 'Dosen',
                    'nip'               => $request->nip,
                    'gelar_depan'       => $request->gelar_depan,
                    'nama'              => $request->nama,
                    'gelar_belakang'    => $request->gelar_belakang,
                    'id_prodi'          => $request->id_prodi,
                    'jabatan'           => $request->jabatan,
                    'tanggal_lahir'     => $request->tanggal_lahir,
                    'jenis_kelamin'     => $request->jenis_kelamin,
                    'email'             => $request->email,
                    'telepon'           => $request->telepon,
                    'tanggal_bergabung' => $request->tanggal_bergabung,
                    'status'            => 'Nonaktif',
                ]);
            });

            return redirect()->route('login')->with('swal_success', 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan Aktivasi.');
        } catch (\Exception $e) {
            Log::error('Error registrasi dosen: ' . $e->getMessage());
            return redirect()->back()->with('swal_error', 'Terjadi kesalahan sistem saat registrasi.')->withInput();
        }
    }
}
