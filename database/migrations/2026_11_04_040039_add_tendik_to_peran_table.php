<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $existing = DB::table('peran')->where('nama_peran', 'Tendik')->first();

        if (! $existing) {
            // Cek apakah ada record peran dengan ID MNCU-style atau RL-style
            $samplePeran = DB::table('peran')->first();
            $newId = 'MNCU00005';
            if ($samplePeran && str_starts_with($samplePeran->id_peran, 'RL-')) {
                $newId = 'RL-005';
            }

            DB::table('peran')->insert([
                'id_peran' => $newId,
                'uuid' => (string) Str::uuid(),
                'nama_peran' => 'Tendik',
                'deskripsi_peran' => 'Tenaga kependidikan / staf operasional universitas',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('peran')->where('nama_peran', 'Tendik')->delete();
    }
};
