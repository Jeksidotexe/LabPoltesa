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

        // Verifikasi Akun
        $userMenunggu = User::where('status', 'Nonaktif')->count();
        if ($userMenunggu > 0) {
            $notifs->push($this->formatNotif(
                'fas fa-user-plus text-info',
                "{$userMenunggu} Pengguna butuh verifikasi",
                route('pengguna.index'),
                'Memerlukan Tindakan'
            ));
        }

        // Finalisasi Pengajuan
        $pengajuanSuper = PengajuanPraktikum::where('status', 'Menunggu Super Admin')->get();
        $countSuper = $pengajuanSuper->count();

        if ($countSuper === 1) {
            // Jika cuma 1, langsung tembak ke halaman Detail Pengajuan
            $notifs->push($this->formatNotif(
                'fas fa-file-signature text-primary',
                "1 Pengajuan butuh finalisasi",
                route('pengajuan.show', $pengajuanSuper->first()->id_pengajuan),
                'Memerlukan Tindakan'
            ));
        } elseif ($countSuper > 1) {
            // Jika lebih dari 1, arahkan ke tabel Dashboard
            $notifs->push($this->formatNotif(
                'fas fa-file-signature text-primary',
                "{$countSuper} Pengajuan butuh finalisasi",
                route('dashboard'),
                'Memerlukan Tindakan'
            ));
        }

        return $notifs;
    }

    // ==========================================
    // LOGIKA ADMIN
    // ==========================================
    private function getAdminNotifications($user)
    {
        $notifs = collect();
        $now = Carbon::now('Asia/Jakarta');

        // Peminjaman Lab Selesai
        $butuhKembali = PengajuanPraktikum::where('status', 'Disetujui')
            ->whereRaw("CONCAT(tanggal, ' ', jam_selesai) <= ?", [$now->format('Y-m-d H:i:s')])
            ->get();

        $countKembali = $butuhKembali->count();

        if ($countKembali === 1) {
            // Jika cuma 1, langsung tembak ke halaman Detail Jadwal untuk dikembalikan
            $notifs->push($this->formatNotif(
                'fas fa-undo text-danger',
                "1 Praktikum Selesai (Cek Alat)",
                route('jadwal.show', $butuhKembali->first()->id_pengajuan),
                'Segera Lakukan Pengecekan'
            ));
        } elseif ($countKembali > 1) {
            // Jika banyak, arahkan ke tabel Jadwal Praktikum
            $notifs->push($this->formatNotif(
                'fas fa-undo text-danger',
                "{$countKembali} Praktikum Selesai (Cek Alat)",
                route('jadwal.index'),
                'Segera Lakukan Pengecekan'
            ));
        }

        // Butuh Pembuatan Berita Acara
        $butuhBA = PengajuanPraktikum::doesntHave('beritaAcara')
            ->whereIn('status', ['Disetujui', 'Selesai'])
            ->get();

        $countBA = $butuhBA->count();

        if ($countBA === 1) {
            // Jika cuma 1, langsung buka form Buat Berita Acara
            $notifs->push($this->formatNotif(
                'fas fa-print text-warning',
                "1 Jadwal belum dibuat Berita Acara",
                route('berita-acara.create', $butuhBA->first()->id_pengajuan),
                'Menunggu Dicetak'
            ));
        } elseif ($countBA > 1) {
            // Jika banyak, arahkan ke tabel antrean Berita Acara
            $notifs->push($this->formatNotif(
                'fas fa-print text-warning',
                "{$countBA} Jadwal belum dibuat Berita Acara",
                route('berita-acara.index'),
                'Menunggu Dicetak'
            ));
        }

        return $notifs;
    }

    // ==========================================
    // LOGIKA KAPRODI
    // ==========================================
    private function getKaprodiNotifications($user)
    {
        $notifs = collect();

        // Verifikasi Jadwal dari Dosen di Prodinya
        $pengajuanKaprodi = PengajuanPraktikum::where('status', 'Menunggu Kaprodi')
            ->whereHas('user', function ($q) use ($user) {
                $q->where('id_prodi', $user->id_prodi);
            })->get();

        $countKaprodi = $pengajuanKaprodi->count();

        if ($countKaprodi === 1) {
            // Jika cuma 1, langsung buka halaman Detail Pengajuan
            $notifs->push($this->formatNotif(
                'fas fa-clipboard-check text-warning',
                "1 Pengajuan butuh persetujuan",
                route('pengajuan.show', $pengajuanKaprodi->first()->id_pengajuan),
                'Memerlukan Tindakan'
            ));
        } elseif ($countKaprodi > 1) {
            // Jika banyak, arahkan ke tabel Dashboard
            $notifs->push($this->formatNotif(
                'fas fa-clipboard-check text-warning',
                "{$countKaprodi} Pengajuan butuh persetujuan",
                route('dashboard'),
                'Memerlukan Tindakan'
            ));
        }

        return $notifs;
    }

    // ==========================================
    // LOGIKA DOSEN
    // ==========================================
    private function getDosenNotifications($user)
    {
        $notifs = collect();

        $pengajuanUpdate = PengajuanPraktikum::with('makul')
            ->where('id_users', $user->id)
            ->whereIn('status', ['Disetujui', 'Ditolak Kaprodi', 'Ditolak Super Admin', 'Selesai'])
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get();

        foreach ($pengajuanUpdate as $p) {
            $isSuccess = in_array($p->status, ['Disetujui', 'Selesai']);
            $icon = $isSuccess ? 'fas fa-check-circle text-success' : 'fas fa-times-circle text-danger';
            $makulNama = $p->makul->nama ?? 'Laboratorium';

            $notifs->push($this->formatNotif(
                $icon,
                "Jadwal {$makulNama}: {$p->status}",
                route('pengajuan.show', $p->id_pengajuan),
                Carbon::parse($p->updated_at)->locale('id')->diffForHumans()
            ));
        }

        return $notifs;
    }
}
