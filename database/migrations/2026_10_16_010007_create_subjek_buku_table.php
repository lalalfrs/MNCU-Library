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
        Schema::create('subjek_buku', function (Blueprint $table) {
            $table->string('id_subjek_buku', 10)->primary()->comment('Format: SB-001');
            $table->string('uuid', 36)->unique();
            $table->string('nama_subjek', 100)->unique();
            $table->text('deskripsi_subjek')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjek_buku');
    }
};
