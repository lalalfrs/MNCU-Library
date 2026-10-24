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
        Schema::create('peminjaman_buku', function (Blueprint $table) {
            $table->string('id_peminjaman', 10)->primary()->comment('Format: PJ00001');
            $table->string('uuid', 36)->unique();
            $table->string('id_pengguna', 10);
            $table->string('id_buku', 10);
            $table->string('id_eksemplar', 10);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_kembali')->nullable();
            $table->string('status', 30)->default('Dipinjam')->comment('Dipinjam | Kembali | Terlambat');
            $table->unsignedInteger('hari_keterlambatan')->default(0)->comment('Jumlah hari lewat jatuh tempo tanpa sanksi denda');
            $table->text('catatan_keterlambatan')->nullable();
            $table->boolean('notifikasi_peringatan_dikirim')->default(false);
            $table->timestamp('tanggal_notifikasi')->nullable();
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('id_buku')->references('id_buku')->on('buku')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('id_eksemplar')->references('id_eksemplar')->on('eksemplar_buku')->cascadeOnUpdate()->restrictOnDelete();

            $table->index(['id_pengguna', 'status']);
            $table->index(['tanggal_jatuh_tempo', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_buku');
    }
};
