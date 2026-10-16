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
        Schema::create('pengguna', function (Blueprint $table) {
            $table->string('id_pengguna', 10)->primary()->comment('Format: PG-001');
            $table->string('uuid', 36)->unique();
            $table->string('id_peran', 10);
            $table->string('id_prodi', 10)->nullable();
            $table->string('nama_lengkap', 100);
            $table->string('email', 100)->unique();
            $table->string('kata_sandi', 255);
            $table->string('nomor_identitas', 30)->nullable()->unique();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_peran')->references('id_peran')->on('peran')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
