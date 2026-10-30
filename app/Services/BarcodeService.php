<?php

namespace App\Services;

class BarcodeService
{
    /**
     * Code 128 patterns (widths of alternating bars and spaces).
     * Patterns 0-105 have 6 digits (sum=11). Pattern 106 (Stop) has 7 digits (sum=13).
     *
     * @var array<int, string>
     */
    protected static array $patterns = [
        0 => '212222', 1 => '222122', 2 => '222221', 3 => '121223', 4 => '121322',
        5 => '131222', 6 => '122213', 7 => '122312', 8 => '132212', 9 => '221213',
        10 => '221312', 11 => '231212', 12 => '112232', 13 => '122132', 14 => '122231',
        15 => '113222', 16 => '123122', 17 => '123221', 18 => '223211', 19 => '221132',
        20 => '221231', 21 => '213212', 22 => '223112', 23 => '312131', 24 => '311222',
        25 => '321122', 26 => '321221', 27 => '312212', 28 => '322112', 29 => '322211',
        30 => '212123', 31 => '212321', 32 => '232121', 33 => '111323', 34 => '131123',
        35 => '131321', 36 => '112313', 37 => '132113', 38 => '132311', 39 => '211313',
        40 => '231113', 41 => '231311', 42 => '112133', 43 => '112331', 44 => '132131',
        45 => '113123', 46 => '113321', 47 => '133121', 48 => '313121', 49 => '211331',
        50 => '231131', 51 => '213113', 52 => '213311', 53 => '213131', 54 => '311123',
        55 => '311321', 56 => '331121', 57 => '312113', 58 => '312311', 59 => '332111',
        60 => '314111', 61 => '221411', 62 => '431111', 63 => '111224', 64 => '111422',
        65 => '121124', 66 => '121421', 67 => '141122', 68 => '141221', 69 => '112214',
        70 => '112412', 71 => '122114', 72 => '122411', 73 => '142112', 74 => '142211',
        75 => '241211', 76 => '221114', 77 => '413111', 78 => '241112', 79 => '134111',
        80 => '111242', 81 => '121142', 82 => '121241', 83 => '114212', 84 => '124112',
        85 => '124211', 86 => '411212', 87 => '421112', 88 => '421211', 89 => '212141',
        90 => '214121', 91 => '412121', 92 => '111143', 93 => '111341', 94 => '131141',
        95 => '114113', 96 => '114311', 97 => '411113', 98 => '411311', 99 => '113141',
        100 => '114131', 101 => '311141', 102 => '411131', 103 => '211412',
        104 => '211214', // Start Code B
        105 => '211232', // Start Code C
        106 => '2331112', // Stop Pattern
    ];

    /**
     * Generate standalone SVG for a Code 128 (Subset B) barcode.
     *
     * @param  string  $code  Text to encode in barcode
     * @param  int  $barHeight  Height of barcode lines in points
     * @param  bool  $showText  Whether to render the text underneath
     * @return string Valid SVG markup
     */
    public function generateSvg(string $code, int $barHeight = 45, bool $showText = true): string
    {
        $code = trim($code);
        if ($code === '') {
            $code = 'MNCU-00000';
        }

        // Sanitasi ke ASCII yang didukung Code 128 Set B (ASCII 32-126)
        $clean = '';
        for ($i = 0; $i < strlen($code); $i++) {
            $ord = ord($code[$i]);
            if ($ord >= 32 && $ord <= 126) {
                $clean .= $code[$i];
            } else {
                $clean .= '-';
            }
        }

        // Hitung kode nilai (Start Code B = 104)
        $startCode = 104;
        $checksum = $startCode;
        $symbols = [$startCode];

        for ($i = 0; $i < strlen($clean); $i++) {
            $val = ord($clean[$i]) - 32;
            $symbols[] = $val;
            $checksum += $val * ($i + 1);
        }

        $checksumVal = $checksum % 103;
        $symbols[] = $checksumVal;
        $symbols[] = 106; // Stop code

        // Bangun bar sequence (true = bar hitam, false = space putih)
        $quietZone = 10; // 10 modules
        $bars = [];

        // Quiet zone kiri
        for ($q = 0; $q < $quietZone; $q++) {
            $bars[] = false;
        }

        foreach ($symbols as $symIndex => $symVal) {
            $pattern = self::$patterns[$symVal] ?? self::$patterns[0];
            $isBar = true;
            for ($p = 0; $p < strlen($pattern); $p++) {
                $width = (int) $pattern[$p];
                for ($w = 0; $w < $width; $w++) {
                    $bars[] = $isBar;
                }
                $isBar = ! $isBar;
            }
        }

        // Quiet zone kanan
        for ($q = 0; $q < $quietZone; $q++) {
            $bars[] = false;
        }

        $totalModules = count($bars);
        $totalHeight = $barHeight + ($showText ? 16 : 0);

        // Gabungkan bar berurutan menjadi rectangle SVG yang ringkas
        $rects = [];
        $currentX = 0;
        $runLength = 0;
        $runningBar = false;

        foreach ($bars as $idx => $bar) {
            if ($bar === $runningBar) {
                $runLength++;
            } else {
                if ($runningBar && $runLength > 0) {
                    $rects[] = sprintf('<rect x="%d" y="0" width="%d" height="%d" fill="#000000" />', $currentX, $runLength, $barHeight);
                }
                $currentX = $idx;
                $runningBar = $bar;
                $runLength = 1;
            }
        }
        if ($runningBar && $runLength > 0) {
            $rects[] = sprintf('<rect x="%d" y="0" width="%d" height="%d" fill="#000000" />', $currentX, $runLength, $barHeight);
        }

        $rectsSvg = implode("\n    ", $rects);

        $textSvg = '';
        if ($showText) {
            $escapedText = htmlspecialchars($clean, ENT_QUOTES, 'UTF-8');
            $textY = $barHeight + 12;
            $textSvg = sprintf(
                '    <text x="50%%" y="%d" text-anchor="middle" font-family="Consolas, Monaco, monospace" font-size="11" font-weight="700" letter-spacing="1" fill="#111827">%s</text>',
                $textY,
                $escapedText
            );
        }

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$totalModules} {$totalHeight}" width="100%" height="{$totalHeight}" preserveAspectRatio="none" shape-rendering="crispEdges">
    <rect width="{$totalModules}" height="{$totalHeight}" fill="#FFFFFF" />
    {$rectsSvg}
{$textSvg}
</svg>
SVG;
    }
}
