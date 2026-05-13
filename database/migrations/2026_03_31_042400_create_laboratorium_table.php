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
        Schema::create('laboratorium', function (Blueprint $table) {
            $table->id('id_lab');
            $table->unsignedBigInteger('id_admin')->nullable();
            $table->string('nama', 20);
            $table->string('kode', 100);
            $table->string('lokasi', 100);
            $table->integer('kapasitas');
            $table->text('deskripsi');
            $table->enum('status', ['Aktif', 'Nonaktif']);
            $table->string('foto', 2048);
            $table->timestamps();

            // Relasi
            $table->foreign('id_admin')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorium');
    }
};
