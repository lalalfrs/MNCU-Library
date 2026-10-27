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
        if (Schema::hasTable('pengguna')) {
            Schema::table('pengguna', function (Blueprint $table): void {
                if (! Schema::hasColumn('pengguna', 'is_member')) {
                    $table->boolean('is_member')->default(false)->after('id_peran');
                }
                if (! Schema::hasColumn('pengguna', 'member_sejak')) {
                    $table->timestamp('member_sejak')->nullable()->after('is_member');
                }
                if (! Schema::hasColumn('pengguna', 'nomor_anggota')) {
                    $table->string('nomor_anggota', 30)->nullable()->unique()->after('member_sejak');
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                if (! Schema::hasColumn('users', 'is_member')) {
                    $table->boolean('is_member')->default(false);
                }
                if (! Schema::hasColumn('users', 'member_sejak')) {
                    $table->timestamp('member_sejak')->nullable();
                }
                if (! Schema::hasColumn('users', 'nomor_anggota')) {
                    $table->string('nomor_anggota', 30)->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pengguna')) {
            Schema::table('pengguna', function (Blueprint $table): void {
                $columns = ['is_member', 'member_sejak', 'nomor_anggota'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('pengguna', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                $columns = ['is_member', 'member_sejak', 'nomor_anggota'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
