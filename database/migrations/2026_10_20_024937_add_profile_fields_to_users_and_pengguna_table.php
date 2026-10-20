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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                if (! Schema::hasColumn('users', 'nama_lengkap')) {
                    $table->string('nama_lengkap', 100)->nullable()->after('name');
                }
                if (! Schema::hasColumn('users', 'role')) {
                    $table->string('role', 30)->default('mahasiswa')->after('email');
                }
                if (! Schema::hasColumn('users', 'alamat_lengkap')) {
                    $table->text('alamat_lengkap')->nullable();
                }
                if (! Schema::hasColumn('users', 'no_wa')) {
                    $table->string('no_wa', 25)->nullable();
                }
                if (! Schema::hasColumn('users', 'tempat_lahir')) {
                    $table->string('tempat_lahir', 100)->nullable();
                }
                if (! Schema::hasColumn('users', 'tanggal_lahir')) {
                    $table->date('tanggal_lahir')->nullable();
                }
                if (! Schema::hasColumn('users', 'nik')) {
                    $table->string('nik', 30)->nullable();
                }
                if (! Schema::hasColumn('users', 'nim')) {
                    $table->string('nim', 30)->nullable();
                }
                if (! Schema::hasColumn('users', 'nidn')) {
                    $table->string('nidn', 30)->nullable();
                }
                if (! Schema::hasColumn('users', 'foto_profil')) {
                    $table->string('foto_profil', 255)->nullable();
                }
            });
        }

        if (Schema::hasTable('pengguna')) {
            Schema::table('pengguna', function (Blueprint $table): void {
                if (! Schema::hasColumn('pengguna', 'alamat_lengkap')) {
                    $table->text('alamat_lengkap')->nullable()->after('nomor_identitas');
                }
                if (! Schema::hasColumn('pengguna', 'no_wa')) {
                    $table->string('no_wa', 25)->nullable()->after('alamat_lengkap');
                }
                if (! Schema::hasColumn('pengguna', 'tempat_lahir')) {
                    $table->string('tempat_lahir', 100)->nullable()->after('no_wa');
                }
                if (! Schema::hasColumn('pengguna', 'tanggal_lahir')) {
                    $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
                }
                if (! Schema::hasColumn('pengguna', 'nik')) {
                    $table->string('nik', 30)->nullable()->after('tanggal_lahir');
                }
                if (! Schema::hasColumn('pengguna', 'nim')) {
                    $table->string('nim', 30)->nullable()->after('nik');
                }
                if (! Schema::hasColumn('pengguna', 'nidn')) {
                    $table->string('nidn', 30)->nullable()->after('nim');
                }
                if (! Schema::hasColumn('pengguna', 'foto_profil')) {
                    $table->string('foto_profil', 255)->nullable()->after('nidn');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                $columns = ['nama_lengkap', 'role', 'alamat_lengkap', 'no_wa', 'tempat_lahir', 'tanggal_lahir', 'nik', 'nim', 'nidn', 'foto_profil'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('pengguna')) {
            Schema::table('pengguna', function (Blueprint $table): void {
                $columns = ['alamat_lengkap', 'no_wa', 'tempat_lahir', 'tanggal_lahir', 'nik', 'nim', 'nidn', 'foto_profil'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('pengguna', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
