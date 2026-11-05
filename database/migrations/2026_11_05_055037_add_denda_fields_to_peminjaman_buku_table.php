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
                if (! Schema::hasColumn('peminjaman_buku', 'nominal_denda')) {
                    $table->unsignedInteger('nominal_denda')->default(0)->after('catatan_keterlambatan')->comment('Nominal denda keterlambatan (Rp 500 per hari)');
                }
                if (! Schema::hasColumn('peminjaman_buku', 'status_pembayaran_denda')) {
                    $table->string('status_pembayaran_denda', 30)->nullable()->after('nominal_denda')->comment('Belum Lunas | Lunas');
                }
                if (! Schema::hasColumn('peminjaman_buku', 'metode_pembayaran_denda')) {
                    $table->string('metode_pembayaran_denda', 30)->default('Cash')->after('status_pembayaran_denda')->comment('Opsi pembayaran hanya Cash');
                }
                if (! Schema::hasColumn('peminjaman_buku', 'tanggal_pembayaran_denda')) {
                    $table->timestamp('tanggal_pembayaran_denda')->nullable()->after('metode_pembayaran_denda');
                }
                if (! Schema::hasColumn('peminjaman_buku', 'id_pustakawan_denda')) {
                    $table->string('id_pustakawan_denda', 10)->nullable()->after('tanggal_pembayaran_denda');
                }
                if (! Schema::hasColumn('peminjaman_buku', 'catatan_denda')) {
                    $table->text('catatan_denda')->nullable()->after('id_pustakawan_denda');
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
                $columns = [
                    'nominal_denda',
                    'status_pembayaran_denda',
                    'metode_pembayaran_denda',
                    'tanggal_pembayaran_denda',
                    'id_pustakawan_denda',
                    'catatan_denda',
                ];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('peminjaman_buku', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
