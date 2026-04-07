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
        Schema::create('alat', function (Blueprint $table) {
            $table->id('id_alat');
            $table->unsignedBigInteger('id_lab');
            $table->string('nama_alat', 100);
            $table->string('spesifikasi_alat', 255);
            $table->string('instruksi_kerja', 255);
            $table->year('tahun_pengadaan');
            $table->integer('jumlah');
            $table->string('foto', 2048)->nullable();
            $table->timestamps();

            // Relasi
            $table->foreign('id_lab')->references('id_lab')->on('laboratorium');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
