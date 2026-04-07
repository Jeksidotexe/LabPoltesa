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
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id('id_makul');
            $table->string('kode', 20);
            $table->string('nama', 100);
            $table->integer('sks');
            $table->unsignedBigInteger('id_prodi');
            $table->timestamps();

            // Relasi
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
