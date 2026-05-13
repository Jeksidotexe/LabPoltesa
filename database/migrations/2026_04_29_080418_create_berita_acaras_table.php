<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_acara', function (Blueprint $table) {
            $table->id('id_berita_acara');
            $table->unsignedBigInteger('id_pengajuan')->nullable();
            $table->string('semester')->nullable();
            $table->string('judul_praktikum')->nullable();
            $table->string('teknisi')->nullable();
            $table->text('kejadian')->nullable();
            $table->json('form_data')->nullable();
            $table->timestamps();

            $table->foreign('id_pengajuan')->references('id_pengajuan')->on('pengajuan_praktikum')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acara');
    }
};
