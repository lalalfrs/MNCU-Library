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
        Schema::create('kontak', function (Blueprint $table) {
            $table->string('id_kontak', 10)->primary()->comment('Format: KK-001');
            $table->string('uuid', 36)->unique();
            $table->string('id_pengguna', 10)->nullable();
            $table->string('nama', 100);
            $table->string('email_kontak', 100);
            $table->string('nomor_telepon', 20)->nullable();
            $table->text('isi_pesan');
            $table->dateTime('waktu_kirim');
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontak');
    }
};
