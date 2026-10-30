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
            if (! Schema::hasColumn('peminjaman_buku', 'tanggal_pengajuan_pinjam')) {
                $table->timestamp('tanggal_pengajuan_pinjam')->nullable()->after('id_eksemplar');
            }
            if (! Schema::hasColumn('peminjaman_buku', 'catatan_pengajuan_pinjam')) {
                $table->text('catatan_pengajuan_pinjam')->nullable()->after('tanggal_pengajuan_pinjam');
            }
            if (! Schema::hasColumn('peminjaman_buku', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('catatan_keterlambatan');
            }
            $table->string('id_eksemplar', 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_buku', function (Blueprint $table): void {
            $columns = ['tanggal_pengajuan_pinjam', 'catatan_pengajuan_pinjam', 'alasan_penolakan'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('peminjaman_buku', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
