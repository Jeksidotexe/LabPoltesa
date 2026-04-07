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
        Schema::create('pengajuan_praktikum', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->unsignedBigInteger('id_users');
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_lab');
            $table->unsignedBigInteger('id_makul');
            $table->date('tanggal');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('jobsheet', 255);
            $table->enum('status', ['Menunggu Kaprodi', 'Ditolak Kaprodi', 'Menunggu Super Admin', 'Ditolak Super Admin', 'Disetujui'])->default('Menunggu Kaprodi');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Relasi
            $table->foreign('id_users')->references('id')->on('users');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori');
            $table->foreign('id_lab')->references('id_lab')->on('laboratorium');
            $table->foreign('id_makul')->references('id_makul')->on('mata_kuliah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_praktikum');
    }
};
