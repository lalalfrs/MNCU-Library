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
        if (Schema::hasTable('peminjaman_buku')) {
            Schema::table('peminjaman_buku', function (Blueprint $table): void {
                if (! Schema::hasColumn('peminjaman_buku', 'sanksi')) {
                    $table->string('sanksi', 100)->nullable()->after('catatan_keterlambatan');
                }
                if (! Schema::hasColumn('peminjaman_buku', 'status_sanksi')) {
                    $table->string('status_sanksi', 30)->default('Tidak Ada')->after('sanksi');
                }
                if (! Schema::hasColumn('peminjaman_buku', 'tanggal_sanksi')) {
                    $table->timestamp('tanggal_sanksi')->nullable()->after('status_sanksi');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('peminjaman_buku')) {
            Schema::table('peminjaman_buku', function (Blueprint $table): void {
                $columns = ['sanksi', 'status_sanksi', 'tanggal_sanksi'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('peminjaman_buku', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
