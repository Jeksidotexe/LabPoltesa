<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'password.required' => 'Password tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('swal_error', $validator->errors()->first());
        }

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // CEK APAKAH AKUN NONAKTIF
            if (Auth::user()->status === 'Nonaktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withInput()
                    ->with('swal_warning', 'Akun Anda sedang menunggu persetujuan Aktivasi.');
            }

            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()
            ->withInput()
            ->with('swal_error', 'Username atau Password salah!');
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
