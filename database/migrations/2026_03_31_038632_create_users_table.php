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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('username');
            $table->string('password');
            $table->enum('role', ['Super Admin', 'Admin', 'Dosen', 'Kaprodi', 'Kajur']);
            $table->string('nip')->unique();
            $table->string('nama', 100);
            $table->string('gelar_depan', 20)->nullable();
            $table->string('gelar_belakang', 20);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->unsignedBigInteger('id_prodi')->nullable();
            $table->string('jabatan', 50);
            $table->string('email', 100);
            $table->string('telepon', 20);
            $table->string('foto', 2048)->nullable();
            $table->date('tanggal_bergabung');
            $table->enum('status', ['Aktif', 'Nonaktif']);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            // $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();

            $table->foreign('id_prodi')->references('id_prodi')->on('program_studi');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
