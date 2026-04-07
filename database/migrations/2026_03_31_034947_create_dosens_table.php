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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id('id_dosen');
            $table->unsignedBigInteger('id_users');
            $table->string('nip')->unique();
            $table->string('nama', 100);
            $table->string('gelar_depan', 20);
            $table->string('gelar_belakang', 20);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->unsignedBigInteger('id_prodi');
            $table->string('jabatan', 50);
            $table->string('email', 100);
            $table->string('telepon', 20);
            $table->string('foto', 2048);
            $table->date('tanggal_bergabung');
            $table->enum('status', ['Aktif', 'Nonaktif']);
            $table->timestamps();

            // Relasi
            $table->foreign('id_users')->references('id')->on('users');
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
