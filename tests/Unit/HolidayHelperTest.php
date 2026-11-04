<?php

namespace Tests\Unit;

use App\Services\HolidayHelper;
use PHPUnit\Framework\TestCase;

class HolidayHelperTest extends TestCase
{
    public function test_it_identifies_sunday_as_holiday(): void
    {
        // 2026-11-08 adalah hari Minggu
        $this->assertTrue(HolidayHelper::isHariMinggu('2026-11-08'));
        $this->assertTrue(HolidayHelper::isHariLibur('2026-11-08'));

        // 2026-11-04 adalah hari Rabu
        $this->assertFalse(HolidayHelper::isHariMinggu('2026-11-04'));
    }

    public function test_it_identifies_national_holidays(): void
    {
        // 2026-08-17 adalah Hari Kemerdekaan RI (Senin)
        $this->assertTrue(HolidayHelper::isHariLiburNasional('2026-08-17'));
        $this->assertTrue(HolidayHelper::isHariLibur('2026-08-17'));
        $this->assertSame('Proklamasi Kemerdekaan RI', HolidayHelper::getNamaHariLiburNasional('2026-08-17'));

        // 2026-08-18 hari biasa
        $this->assertFalse(HolidayHelper::isHariLiburNasional('2026-08-18'));
    }

    public function test_it_calculates_due_date_skipping_sundays_and_holidays_for_7_days(): void
    {
        // Mulai Senin, 2026-11-02
        // Hari kerja 1: Sel 03 Nov
        // Hari kerja 2: Rab 04 Nov
        // Hari kerja 3: Kam 05 Nov
        // Hari kerja 4: Jum 06 Nov
        // Hari kerja 5: Sab 07 Nov
        // Minggu 08 Nov: LIBUR (dilewati)
        // Hari kerja 6: Sen 09 Nov
        // Hari kerja 7: Sel 10 Nov
        $jatuhTempo = HolidayHelper::hitungJatuhTempo('2026-11-02', 7);
        $this->assertSame('2026-11-10', $jatuhTempo->toDateString());
    }

    public function test_it_skips_national_holiday_in_due_date_calculation(): void
    {
        // Mulai Jumat, 2026-08-14
        // Hari 1: Sab 15 Aug
        // Minggu 16 Aug: LIBUR
        // Senin 17 Aug: LIBUR NASIONAL (Kemerdekaan RI)
        // Hari 2: Sel 18 Aug
        // Hari 3: Rab 19 Aug
        // Hari 4: Kam 20 Aug
        // Hari 5: Jum 21 Aug
        // Hari 6: Sab 22 Aug
        // Minggu 23 Aug: LIBUR
        // Hari 7: Sen 24 Aug
        $jatuhTempo = HolidayHelper::hitungJatuhTempo('2026-08-14', 7);
        $this->assertSame('2026-08-24', $jatuhTempo->toDateString());
    }

    public function test_it_calculates_effective_late_days_excluding_holidays(): void
    {
        // Tempo: Jumat 2026-11-06
        // Target: Selasa 2026-11-10
        // Hari lewat:
        // Sab 07 Nov (hari kerja 1)
        // Min 08 Nov (libur - tidak dihitung)
        // Sen 09 Nov (hari kerja 2)
        // Sel 10 Nov (hari kerja 3)
        $terlambat = HolidayHelper::hitungHariTerlambatEfektif('2026-11-06', '2026-11-10');
        $this->assertSame(3, $terlambat);
    }
}
