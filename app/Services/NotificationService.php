<?php

namespace App\Services;

use App\Models\User;
use App\Models\PengajuanPraktikum;
use Carbon\Carbon;

class NotificationService
{
    public function getNotificationsForUser($user)
    {
        $methodName = 'get' . str_replace(' ', '', $user->role) . 'Notifications';

        if (method_exists($this, $methodName)) {
            return $this->$methodName($user);
        }

        return collect();
    }

    private function formatNotif($icon, $text, $link, $time)
    {
        return [
            'icon' => $icon,
            'text' => $text,
            'link' => $link,
            'time' => $time
        ];
    }

    // ==========================================
    // LOGIKA SUPER ADMIN
    // ==========================================
    private function getSuperAdminNotifications($user)
    {
        $notifs = collect();

        $userMenunggu = User::where('status', 'Nonaktif')->count();
        if ($userMenunggu > 0) {
            $notifs->push($this->formatNotif('fas fa-user-plus text-info', "{$userMenunggu} Pengguna butuh verifikasi", route('pengguna.index'), 'Memerlukan Tindakan'));
        }

        $pengajuanSuper = PengajuanPraktikum::where('status', 'Menunggu Super Admin')->get();
        $countSuper = $pengajuanSuper->count();

        if ($countSuper === 1) {
            $notifs->push($this->formatNotif('fas fa-file-signature text-primary', "1 Pengajuan butuh finalisasi", route('pengajuan.show', $pengajuanSuper->first()->id_pengajuan), 'Memerlukan Tindakan'));
        } elseif ($countSuper > 1) {
            $notifs->push($this->formatNotif('fas fa-file-signature text-primary', "{$countSuper} Pengajuan butuh finalisasi", route('dashboard'), 'Memerlukan Tindakan'));
        }

        return $notifs;
    }

    // ==========================================
    // LOGIKA ADMIN LAB (HANYA LAB MILIKNYA)
    // ==========================================
    private function getAdminNotifications($user)
    {
        $notifs = collect();
        $now = Carbon::now('Asia/Jakarta');
        $labMilikAdmin = \App\Models\Laboratorium::where('id_admin', $user->id)->pluck('id_lab')->toArray();

        // Notifikasi Peminjaman Selesai (Cek Alat / Pengembalian)
        $butuhKembali = PengajuanPraktikum::where('status', 'Disetujui')
            ->whereIn('id_lab', $labMilikAdmin)
            ->whereRaw("CONCAT(tanggal, ' ', jam_selesai) <= ?", [$now->format('Y-m-d H:i:s')])
            ->get();

        $countKembali = $butuhKembali->count();

        if ($countKembali === 1) {
            $notifs->push($this->formatNotif('fas fa-undo text-danger', "1 Praktikum Selesai (Cek Alat)", route('jadwal.show', $butuhKembali->first()->id_pengajuan), 'Segera Lakukan Pengecekan'));
        } elseif ($countKembali > 1) {
            $notifs->push($this->formatNotif('fas fa-undo text-danger', "{$countKembali} Praktikum Selesai (Cek Alat)", route('jadwal.index'), 'Segera Lakukan Pengecekan'));
        }

        // Notifikasi Berita Acara yang Butuh Finalisasi Admin (Dosen sudah isi, Admin belum cetak)
        $butuhCetak = PengajuanPraktikum::whereHas('beritaAcara', function ($q) {
            // Cek apakah di JSON form_data BELUM ADA is_printed
            $q->where('form_data', 'not like', '%"is_printed":true%');
        })
            ->whereIn('id_lab', $labMilikAdmin)
            ->whereIn('status', ['Disetujui', 'Selesai'])
            ->get();

        $countCetak = $butuhCetak->count();

        if ($countCetak === 1) {
            $notifs->push($this->formatNotif('fas fa-print text-primary', "1 Berita Acara Menunggu Dicetak", route('berita-acara.create', $butuhCetak->first()->id_pengajuan), 'Segera Lengkapi & Cetak'));
        } elseif ($countCetak > 1) {
            $notifs->push($this->formatNotif('fas fa-print text-primary', "{$countCetak} Berita Acara Menunggu Dicetak", route('berita-acara.index'), 'Segera Lengkapi & Cetak'));
        }

        return $notifs;
    }

    // ==========================================
    // LOGIKA KAPRODI
    // ==========================================
    private function getKaprodiNotifications($user)
    {
        $notifs = collect();
        $pengajuanKaprodi = PengajuanPraktikum::where('status', 'Menunggu Kaprodi')
            ->whereHas('user', function ($q) use ($user) {
                $q->where('id_prodi', $user->id_prodi);
            })->get();

        $countKaprodi = $pengajuanKaprodi->count();

        if ($countKaprodi === 1) {
            $notifs->push($this->formatNotif('fas fa-clipboard-check text-warning', "1 Pengajuan butuh persetujuan", route('pengajuan.show', $pengajuanKaprodi->first()->id_pengajuan), 'Memerlukan Tindakan'));
        } elseif ($countKaprodi > 1) {
            $notifs->push($this->formatNotif('fas fa-clipboard-check text-warning', "{$countKaprodi} Pengajuan butuh persetujuan", route('dashboard'), 'Memerlukan Tindakan'));
        }

        return $notifs;
    }

    // ==========================================
    // LOGIKA DOSEN
    // ==========================================
    private function getDosenNotifications($user)
    {
        $notifs = collect();
        $now = Carbon::now('Asia/Jakarta');

        // HANYA NOTIFIKASI BERBASIS TUGAS (ACTIONABLE)
        $butuhBA = PengajuanPraktikum::doesntHave('beritaAcara')
            ->where('id_users', $user->id)
            ->whereIn('status', ['Disetujui', 'Selesai'])
            ->whereRaw("CONCAT(tanggal, ' ', jam_selesai) <= ?", [$now->format('Y-m-d H:i:s')])
            ->get();

        $countBA = $butuhBA->count();

        if ($countBA === 1) {
            $notifs->push($this->formatNotif('fas fa-pen text-warning', "1 Praktikum Selesai (Isi Berita Acara)", route('berita-acara.create', $butuhBA->first()->id_pengajuan), 'Segera Lengkapi Form'));
        } elseif ($countBA > 1) {
            $notifs->push($this->formatNotif('fas fa-pen text-warning', "{$countBA} Praktikum Selesai (Isi Berita Acara)", route('berita-acara.index'), 'Segera Lengkapi Form'));
        }

        return $notifs;
    }
}
