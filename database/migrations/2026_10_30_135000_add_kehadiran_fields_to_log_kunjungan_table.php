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
        Schema::table('log_kunjungan', function (Blueprint $table) {
            if (! Schema::hasColumn('log_kunjungan', 'nama_pengunjung')) {
                $table->string('nama_pengunjung', 150)->nullable()->after('id_pengguna');
            }
            if (! Schema::hasColumn('log_kunjungan', 'kategori_pengunjung')) {
                $table->string('kategori_pengunjung', 50)->nullable()->after('nama_pengunjung')->comment('Mahasiswa, Dosen/Staf, Umum');
            }
            if (! Schema::hasColumn('log_kunjungan', 'identitas_pengunjung')) {
                $table->string('identitas_pengunjung', 255)->nullable()->after('kategori_pengunjung')->comment('NIM / NIP / Alamat (untuk Umum)');
            }
            if (! Schema::hasColumn('log_kunjungan', 'keperluan')) {
                $table->string('keperluan', 255)->nullable()->after('identitas_pengunjung');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_kunjungan', function (Blueprint $table) {
            $columns = array_filter(
                ['nama_pengunjung', 'kategori_pengunjung', 'identitas_pengunjung', 'keperluan'],
                fn (string $col) => Schema::hasColumn('log_kunjungan', $col)
            );
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
