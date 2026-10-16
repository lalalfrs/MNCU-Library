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
        Schema::create('acara_perpustakaan', function (Blueprint $table) {
            $table->string('id_acara', 10)->primary()->comment('Format: AC-001');
            $table->string('uuid', 36)->unique();
            $table->string('id_pengguna', 10);
            $table->string('nama_acara', 255);
            $table->string('slug', 255)->unique()->nullable();
            $table->string('kategori_acara', 100)->default('Acara Perpustakaan');
            $table->string('ringkasan_acara', 255)->nullable();
            $table->text('deskripsi_acara')->nullable();
            $table->string('gambar_acara', 255)->nullable();
            $table->string('narasumber', 100)->nullable();
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->string('lokasi_acara', 255)->nullable();
            $table->string('tautan_pendaftaran', 255)->nullable();
            $table->unsignedInteger('kuota')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acara_perpustakaan');
    }
};
