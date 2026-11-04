<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class HolidayHelper
{
    /**
     * Daftar hari libur nasional Indonesia (Y-m-d).
     *
     * @var array<string, string>
     */
    protected static array $daftarLiburNasional = [
        // 2025
        '2025-01-01' => 'Tahun Baru 2025 Masehi',
        '2025-01-27' => 'Isra Mikraj Nabi Muhammad SAW',
        '2025-01-29' => 'Tahun Baru Imlek 2576 Kongzili',
        '2025-03-29' => 'Hari Suci Nyepi (Tahun Baru Saka 1947)',
        '2025-03-31' => 'Hari Raya Idul Fitri 1446 H',
        '2025-04-01' => 'Hari Raya Idul Fitri 1446 H',
        '2025-04-18' => 'Wafat Yesus Kristus',
        '2025-04-20' => 'Hari Paskah',
        '2025-05-01' => 'Hari Buruh Internasional',
        '2025-05-12' => 'Hari Raya Waisak 2569 BE',
        '2025-05-29' => 'Kenaikan Yesus Kristus',
        '2025-06-01' => 'Hari Lahir Pancasila',
        '2025-06-06' => 'Hari Raya Idul Adha 1446 H',
        '2025-06-27' => 'Tahun Baru Islam 1447 H',
        '2025-08-17' => 'Proklamasi Kemerdekaan RI',
        '2025-09-05' => 'Maulid Nabi Muhammad SAW',
        '2025-12-25' => 'Kelahiran Yesus Kristus (Hari Raya Natal)',

        // 2026
        '2026-01-01' => 'Tahun Baru 2026 Masehi',
        '2026-01-16' => 'Isra Mikraj Nabi Muhammad SAW',
        '2026-02-17' => 'Tahun Baru Imlek 2577 Kongzili',
        '2026-03-19' => 'Hari Suci Nyepi (Tahun Baru Saka 1948)',
        '2026-03-20' => 'Hari Raya Idul Fitri 1447 H',
        '2026-03-21' => 'Hari Raya Idul Fitri 1447 H',
        '2026-04-03' => 'Wafat Yesus Kristus',
        '2026-04-05' => 'Hari Paskah',
        '2026-05-01' => 'Hari Buruh Internasional',
        '2026-05-14' => 'Kenaikan Yesus Kristus',
        '2026-05-27' => 'Hari Raya Idul Adha 1447 H',
        '2026-05-31' => 'Hari Raya Waisak 2570 BE',
        '2026-06-01' => 'Hari Lahir Pancasila',
        '2026-06-16' => 'Tahun Baru Islam 1448 H',
        '2026-08-17' => 'Proklamasi Kemerdekaan RI',
        '2026-08-25' => 'Maulid Nabi Muhammad SAW',
        '2026-12-25' => 'Kelahiran Yesus Kristus (Hari Raya Natal)',

        // 2027
        '2027-01-01' => 'Tahun Baru 2027 Masehi',
        '2027-01-06' => 'Isra Mikraj Nabi Muhammad SAW',
        '2027-02-06' => 'Tahun Baru Imlek 2578 Kongzili',
        '2027-03-09' => 'Hari Raya Idul Fitri 1448 H',
        '2027-03-10' => 'Hari Raya Idul Fitri 1448 H',
        '2027-03-26' => 'Wafat Yesus Kristus',
        '2027-04-07' => 'Hari Suci Nyepi',
        '2027-05-01' => 'Hari Buruh Internasional',
        '2027-05-06' => 'Kenaikan Yesus Kristus',
        '2027-05-17' => 'Hari Raya Idul Adha 1448 H',
        '2027-05-20' => 'Hari Raya Waisak',
        '2027-06-01' => 'Hari Lahir Pancasila',
        '2027-06-06' => 'Tahun Baru Islam 1449 H',
        '2027-08-15' => 'Maulid Nabi Muhammad SAW',
        '2027-08-17' => 'Proklamasi Kemerdekaan RI',
        '2027-12-25' => 'Kelahiran Yesus Kristus (Hari Raya Natal)',
    ];

    /**
     * Dapatkan seluruh daftar libur nasional.
     *
     * @return array<string, string>
     */
    public static function getDaftarLiburNasional(): array
    {
        return self::$daftarLiburNasional;
    }

    /**
     * Cek apakah tanggal merupakan hari Minggu.
     */
    public static function isHariMinggu(CarbonInterface|string $date): bool
    {
        $carbon = $date instanceof CarbonInterface ? $date : Carbon::parse($date);

        return $carbon->isSunday();
    }

    /**
     * Cek apakah tanggal merupakan hari libur nasional Indonesia.
     */
    public static function isHariLiburNasional(CarbonInterface|string $date): bool
    {
        $ymd = ($date instanceof CarbonInterface ? $date : Carbon::parse($date))->toDateString();

        return isset(self::$daftarLiburNasional[$ymd]);
    }

    /**
     * Dapatkan keterangan nama hari libur nasional jika ada.
     */
    public static function getNamaHariLiburNasional(CarbonInterface|string $date): ?string
    {
        $ymd = ($date instanceof CarbonInterface ? $date : Carbon::parse($date))->toDateString();

        return self::$daftarLiburNasional[$ymd] ?? null;
    }

    /**
     * Cek apakah tanggal merupakan hari libur perpustakaan (Minggu atau Libur Nasional).
     */
    public static function isHariLibur(CarbonInterface|string $date): bool
    {
        return self::isHariMinggu($date) || self::isHariLiburNasional($date);
    }

    /**
     * Hitung tanggal jatuh tempo pinjaman dengan menambahkan sejumlah hari kerja efektif pinjaman (default: 7 hari),
     * dengan mengecualikan (tidak menghitung) hari Minggu dan Hari Libur Nasional.
     */
    public static function hitungJatuhTempo(CarbonInterface|string $tanggalMulai, int $durasiHariKerja = 7): Carbon
    {
        $current = ($tanggalMulai instanceof CarbonInterface ? $tanggalMulai->copy() : Carbon::parse($tanggalMulai))->startOfDay();
        $hariTerkumpul = 0;

        while ($hariTerkumpul < $durasiHariKerja) {
            $current->addDay();
            if (! self::isHariLibur($current)) {
                $hariTerkumpul++;
            }
        }

        return $current;
    }

    /**
     * Hitung jumlah hari keterlambatan efektif (hanya menghitung hari aktif, mengabaikan Minggu & Hari Libur Nasional).
     */
    public static function hitungHariTerlambatEfektif(CarbonInterface|string $jatuhTempo, CarbonInterface|string $tanggalTarget): int
    {
        $tempo = ($jatuhTempo instanceof CarbonInterface ? $jatuhTempo->copy() : Carbon::parse($jatuhTempo))->startOfDay();
        $target = ($tanggalTarget instanceof CarbonInterface ? $tanggalTarget->copy() : Carbon::parse($tanggalTarget))->startOfDay();

        if ($target->lte($tempo)) {
            return 0;
        }

        $terlambat = 0;
        $cursor = $tempo->copy();

        while ($cursor->lt($target)) {
            $cursor->addDay();
            if (! self::isHariLibur($cursor)) {
                $terlambat++;
            }
        }

        return $terlambat;
    }
}
