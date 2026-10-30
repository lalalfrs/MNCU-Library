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
            if (! Schema::hasColumn('buku', 'kata_kunci')) {
                $table->text('kata_kunci')->nullable()->after('deskripsi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table): void {
            if (Schema::hasColumn('buku', 'kata_kunci')) {
                $table->dropColumn('kata_kunci');
            }
        });
    }
};
