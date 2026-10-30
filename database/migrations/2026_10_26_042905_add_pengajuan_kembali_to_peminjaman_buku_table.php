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
        Schema::table('peminjaman_buku', function (Blueprint $table): void {
            if (! Schema::hasColumn('peminjaman_buku', 'tanggal_pengajuan_kembali')) {
                $table->timestamp('tanggal_pengajuan_kembali')->nullable()->after('tanggal_kembali');
            }
            if (! Schema::hasColumn('peminjaman_buku', 'catatan_pengajuan_kembali')) {
                $table->text('catatan_pengajuan_kembali')->nullable()->after('tanggal_pengajuan_kembali');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_buku', function (Blueprint $table): void {
            $columns = ['tanggal_pengajuan_kembali', 'catatan_pengajuan_kembali'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('peminjaman_buku', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
