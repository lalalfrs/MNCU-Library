<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('pengguna')) {
            return;
        }

        // 1. Pemetaan eksplisit untuk akun utama bawaan perpustakaan MNCU
        $explicitMap = [
            'MNCU00001' => '30001', // Kepala Perpustakaan
            'PG-001' => '30001',
            'MNCU00002' => '30002', // Pustakawan Sarah
            'PG-002' => '30002',
            'MNCU00003' => '30003', // Pustakawan Fajar
            'PG-003' => '30003',
            'MNCU00004' => '20001', // Mahasiswa Ahmad Fauzi
            'PG-004' => '20001',
            'MNCU00005' => '10001', // Dosen Prof Hendra Gunawan
            'PG-005' => '10001',
            'MNCU00006' => '20002', // Mahasiswa Nabila Putri
            'PG-006' => '20002',
        ];

        foreach ($explicitMap as $id => $nimNip5Digit) {
            DB::table('pengguna')
                ->where('id_pengguna', $id)
                ->update(['nomor_identitas' => $nimNip5Digit]);
        }

        // Juga update berdasarkan nilai nomor_identitas lama jika id-nya berbeda
        $oldIdentitasMap = [
            'PST-MNCU-001' => '30001',
            'PST-MNCU-002' => '30002',
            'PST-MNCU-003' => '30003',
            '2024010012' => '20001',
            'NIDN-0312048501' => '10001',
            '2024010045' => '20002',
        ];

        foreach ($oldIdentitasMap as $old => $new) {
            DB::table('pengguna')
                ->where('nomor_identitas', $old)
                ->update(['nomor_identitas' => $new]);
        }

        // 2. Normalisasi akun lain yang nomor identitasnya belum 5 digit angka murni
        // Format aturan 5 digit:
        // - Dosen: 10001 s/d 19999 (prefix 1)
        // - Mahasiswa: 20001 s/d 29999 (prefix 2)
        // - Tendik / Pustakawan / Admin: 30001 s/d 39999 (prefix 3)
        $users = DB::table('pengguna')
            ->leftJoin('peran', 'pengguna.id_peran', '=', 'peran.id_peran')
            ->select('pengguna.id_pengguna', 'pengguna.nomor_identitas', 'peran.nama_peran')
            ->get();

        $usedIds = DB::table('pengguna')
            ->whereNotNull('nomor_identitas')
            ->pluck('nomor_identitas')
            ->toArray();

        $dosenSeq = 10002;
        $mhsSeq = 20003;
        $tendikSeq = 30004;

        foreach ($users as $user) {
            $current = (string) ($user->nomor_identitas ?? '');
            $is5Digit = preg_match('/^\d{5}$/', $current);

            if (! $is5Digit) {
                $roleName = strtolower((string) ($user->nama_peran ?? ''));
                $newId = null;

                if (str_contains($roleName, 'dosen')) {
                    while (in_array((string) $dosenSeq, $usedIds, true)) {
                        $dosenSeq++;
                    }
                    $newId = (string) $dosenSeq;
                    $dosenSeq++;
                } elseif (str_contains($roleName, 'tendik') || str_contains($roleName, 'pustakawan') || str_contains($roleName, 'admin')) {
                    while (in_array((string) $tendikSeq, $usedIds, true)) {
                        $tendikSeq++;
                    }
                    $newId = (string) $tendikSeq;
                    $tendikSeq++;
                } else {
                    // Default Mahasiswa
                    while (in_array((string) $mhsSeq, $usedIds, true)) {
                        $mhsSeq++;
                    }
                    $newId = (string) $mhsSeq;
                    $mhsSeq++;
                }

                $usedIds[] = $newId;
                DB::table('pengguna')
                    ->where('id_pengguna', $user->id_pengguna)
                    ->update(['nomor_identitas' => $newId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert ke identitas bawaan awal jika diperlukan
        if (! Schema::hasTable('pengguna')) {
            return;
        }

        $revertMap = [
            '30001' => 'PST-MNCU-001',
            '30002' => 'PST-MNCU-002',
            '30003' => 'PST-MNCU-003',
            '20001' => '2024010012',
            '10001' => 'NIDN-0312048501',
            '20002' => '2024010045',
        ];

        foreach ($revertMap as $new => $old) {
            DB::table('pengguna')
                ->where('nomor_identitas', $new)
                ->update(['nomor_identitas' => $old]);
        }
    }
};
