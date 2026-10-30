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
        Schema::table('buku', function (Blueprint $table): void {
            if (! Schema::hasColumn('buku', 'dimensi')) {
                $table->string('dimensi', 100)->nullable()->after('jumlah_halaman')->comment('Dimensi atau deskripsi fisik buku, misal: 21 cm atau 15 x 23 cm');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table): void {
            if (Schema::hasColumn('buku', 'dimensi')) {
                $table->dropColumn('dimensi');
            }
        });
    }
};
