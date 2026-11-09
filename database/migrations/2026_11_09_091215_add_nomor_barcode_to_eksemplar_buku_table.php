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
        Schema::table('eksemplar_buku', function (Blueprint $table): void {
            $table->string('nomor_barcode', 60)->nullable()->after('kode_inventaris')->comment('Nomor barcode stiker buku');
            $table->index('nomor_barcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eksemplar_buku', function (Blueprint $table): void {
            $table->dropIndex(['nomor_barcode']);
            $table->dropColumn('nomor_barcode');
        });
    }
};
