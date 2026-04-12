<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_alat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengajuan');
            $table->unsignedBigInteger('id_alat');
            $table->integer('jumlah_pinjam');

            // Status dan Rincian Kondisi Saat Dikembalikan
            $table->enum('status_kembali', ['Belum', 'Sudah'])->default('Belum');
            $table->integer('jml_kembali_baik')->default(0);
            $table->integer('jml_rusak_ringan')->default(0);
            $table->integer('jml_rusak_berat')->default(0);

            $table->timestamps();

            // Relasi / Foreign Keys
            $table->foreign('id_pengajuan')
                ->references('id_pengajuan')
                ->on('pengajuan_praktikum')
                ->onDelete('cascade'); // Jika pengajuan dihapus, data peminjaman alatnya ikut terhapus

            $table->foreign('id_alat')
                ->references('id_alat')
                ->on('alat')
                ->onDelete('cascade'); // Jika alat dihapus dari master, riwayat peminjamannya ikut terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_alat');
    }
};
