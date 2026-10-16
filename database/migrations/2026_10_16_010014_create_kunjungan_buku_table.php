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
        Schema::create('kunjungan_buku', function (Blueprint $table) {
            $table->string('id_kunjungan_buku', 10)->primary()->comment('Format: KJB-001');
            $table->string('uuid', 36)->unique();
            $table->string('id_buku', 10)->unique();
            $table->integer('jumlah_kunjungan')->default(0);
            $table->timestamps();

            $table->foreign('id_buku')->references('id_buku')->on('buku')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan_buku');
    }
};
