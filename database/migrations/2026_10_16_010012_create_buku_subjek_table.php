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
        Schema::create('buku_subjek', function (Blueprint $table) {
            $table->string('id_buku_subjek', 10)->primary()->comment('Format: BS-001');
            $table->string('uuid', 36)->unique();
            $table->string('id_buku', 10);
            $table->string('id_subjek_buku', 10);
            $table->timestamps();

            $table->unique(['id_buku', 'id_subjek_buku']);
            $table->foreign('id_buku')->references('id_buku')->on('buku')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_subjek_buku')->references('id_subjek_buku')->on('subjek_buku')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_subjek');
    }
};
