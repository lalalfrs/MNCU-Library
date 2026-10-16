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
        Schema::create('jenis_buku', function (Blueprint $table) {
            $table->string('id_jenis_buku', 10)->primary()->comment('Format: JB-001');
            $table->string('uuid', 36)->unique();
            $table->string('nama_jenis_buku', 100)->unique();
            $table->text('deskripsi_jenis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_buku');
    }
};
