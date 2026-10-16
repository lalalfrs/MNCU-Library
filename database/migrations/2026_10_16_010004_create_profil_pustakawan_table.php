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
        Schema::create('profil_pustakawan', function (Blueprint $table) {
            $table->string('id_profil_pustakawan', 10)->primary()->comment('Format: PP-001');
            $table->string('uuid', 36)->unique();
            $table->string('id_pengguna', 10)->unique();
            $table->string('nama_pustakawan', 100);
            $table->string('jabatan', 100)->nullable();
            $table->string('foto_pustakawan', 255)->nullable();
            $table->text('deskripsi_pustakawan')->nullable();
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_pustakawan');
    }
};
