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
        Schema::create('eksemplar_buku', function (Blueprint $table) {
            $table->string('id_eksemplar', 10)->primary()->comment('Format: EK-001');
            $table->string('uuid', 36)->unique();
            $table->string('id_buku', 10);
            $table->string('kode_inventaris', 50)->unique();
            $table->string('nomor_panggil', 50)->nullable();
            $table->string('lokasi_rak', 100)->nullable();
            $table->string('status', 30)->default('Tersedia');
            $table->timestamps();

            $table->foreign('id_buku')->references('id_buku')->on('buku')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eksemplar_buku');
    }
};
