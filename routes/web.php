<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\PengajuanPraktikumController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\RekapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/pengajuan-data', [DashboardController::class, 'dataPengajuan'])->name('dashboard.pengajuan.data');
    Route::get('/pengajuan/detail/{id}', [PengajuanPraktikumController::class, 'show'])->name('pengajuan.show');

    Route::get('/profil-saya', [ProfilController::class, 'index'])->name('profil.show');
    Route::get('/profil-saya/edit', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil-saya', [ProfilController::class, 'update'])->name('profil.update');

    Route::get('/rekap/kegiatan', [RekapController::class, 'kegiatanLab'])->name('rekap.index');
    Route::get('/rekap/kegiatan/data', [RekapController::class, 'dataKegiatan'])->name('rekap.kegiatan.data');
    Route::get('/rekap/kegiatan/cetak', [RekapController::class, 'cetak'])->name('rekap.cetak');

    // === KHUSUS SUPER ADMIN ===
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::get('/pengguna/data', [PenggunaController::class, 'data'])->name('pengguna.data');
        Route::resource('/pengguna', PenggunaController::class);

        Route::get('/prodi/data', [ProgramStudiController::class, 'data'])->name('prodi.data');
        Route::resource('/prodi', ProgramStudiController::class);

        Route::get('/makul/data', [MataKuliahController::class, 'data'])->name('makul.data');
        Route::resource('/makul', MataKuliahController::class);

        Route::get('/lab/data', [LaboratoriumController::class, 'data'])->name('lab.data');
        Route::resource('/lab', LaboratoriumController::class);

        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);

        Route::post('/pengajuan/verifikasi-akhir/{id}', [PengajuanPraktikumController::class, 'verifySuperAdmin'])->name('pengajuan.verify.admin');
    });

    // === KHUSUS ADMIN ===
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/alat/data', [AlatController::class, 'data'])->name('alat.data');
        Route::resource('/alat', AlatController::class);
    });

    // === KHUSUS DOSEN ===
    Route::middleware(['role:Dosen'])->group(function () {
        Route::get('/pengajuan', [PengajuanPraktikumController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/data', [PengajuanPraktikumController::class, 'data'])->name('pengajuan.data');
        Route::get('/pengajuan/create', [PengajuanPraktikumController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanPraktikumController::class, 'store'])->name('pengajuan.store');
    });

    // === KHUSUS KAPRODI ===
    Route::middleware(['role:Kaprodi'])->group(function () {
        Route::post('/pengajuan/verifikasi-kaprodi/{id}', [PengajuanPraktikumController::class, 'verifyKaprodi'])->name('pengajuan.verify.kaprodi');
    });
});
