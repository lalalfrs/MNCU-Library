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
        Schema::create('buku_penulis', function (Blueprint $table) {
            $table->string('id_buku_penulis', 10)->primary()->comment('Format: BP-001');
            $table->string('uuid', 36)->unique();
            $table->string('id_buku', 10);
            $table->string('id_penulis', 10);
            $table->timestamps();

            $table->unique(['id_buku', 'id_penulis']);
            $table->foreign('id_buku')->references('id_buku')->on('buku')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_penulis')->references('id_penulis')->on('penulis')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_penulis');
    }
};
