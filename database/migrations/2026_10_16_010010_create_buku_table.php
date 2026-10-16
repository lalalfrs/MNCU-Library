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
        Schema::create('buku', function (Blueprint $table) {
            $table->string('id_buku', 10)->primary()->comment('Format: BK-001');
            $table->string('uuid', 36)->unique();
            $table->string('id_jenis_buku', 10);
            $table->string('id_kategori_buku', 10);
            $table->string('id_penerbit', 10)->nullable();
            $table->string('judul_buku', 255);
            $table->string('isbn', 20)->nullable();
            $table->integer('tahun_terbit')->nullable();
            $table->string('edisi', 50)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('sampul_buku', 255)->nullable();
            $table->integer('jumlah_halaman')->nullable();
            $table->timestamps();

            $table->foreign('id_jenis_buku')->references('id_jenis_buku')->on('jenis_buku')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('id_kategori_buku')->references('id_kategori_buku')->on('kategori_buku')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('id_penerbit')->references('id_penerbit')->on('penerbit')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
