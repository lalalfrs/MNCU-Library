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
        Schema::create('berita', function (Blueprint $table) {
            $table->string('id_berita', 10)->primary()->comment('Format: BR-001');
            $table->string('uuid', 36)->unique();
            $table->string('id_pengguna', 10);
            $table->string('judul_berita', 255);
            $table->string('slug', 255)->unique()->nullable();
            $table->string('kategori_berita', 100)->default('Pengumuman');
            $table->string('deskripsi_berita', 255);
            $table->longText('isi_berita');
            $table->string('gambar_berita', 255)->nullable();
            $table->date('tanggal_publikasi')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
