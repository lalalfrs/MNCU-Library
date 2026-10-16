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
        Schema::create('log_kunjungan', function (Blueprint $table) {
            $table->string('id_log_kunjungan', 10)->primary()->comment('Format: LK-001');
            $table->string('uuid', 36)->unique();
            $table->string('id_pengguna', 10)->nullable();
            $table->dateTime('waktu_kunjungan');
            $table->string('session_id', 255)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_kunjungan');
    }
};
