<?php

namespace App\Services;

class BarcodeService
{
    /**
     * Code 39 Character Patterns
     * 0 = narrow, 1 = wide
     */
    private static array $code39 = [
        '0' => '000110100', '1' => '100100001', '2' => '001100001', '3' => '101100000',
        '4' => '000110001', '5' => '100110000', '6' => '001110000', '7' => '000100101',
        '8' => '100100100', '9' => '001100100', 'A' => '100001001', 'B' => '001001001',
        'C' => '101001000', 'D' => '000011001', 'E' => '100011000', 'F' => '001011000',
        'G' => '000001101', 'H' => '100001100', 'I' => '001001100', 'J' => '000011100',
        'K' => '100000011', 'L' => '001000011', 'M' => '101000010', 'N' => '000010011',
        'O' => '100010010', 'P' => '001010010', 'Q' => '000000111', 'R' => '100000110',
        'S' => '001000110', 'T' => '000010110', 'U' => '110000001', 'V' => '011000001',
        'W' => '111000000', 'X' => '010010001', 'Y' => '110010000', 'Z' => '011010000',
        '-' => '010000101', '.' => '110000100', ' ' => '011000100', '$' => '010101000',
        '/' => '010100010', '+' => '010001010', '%' => '000101010', '*' => '010010100',
    ];

    /**
     * Generate an SVG Barcode string for Code 39
     *
     * @param string $text
     * @param int $height
     * @param float $narrowWidth
     * @param float $wideWidth
     * @return string
     */
    public static function getCode39Svg(string $text, int $height = 45, float $narrowWidth = 1.1, float $wideWidth = 2.6): string
    {
        $text = strtoupper(trim($text));
        $fullText = '*' . $text . '*';
        $elements = [];
        $totalWidth = 6; // Left quiet zone

        for ($i = 0; $i < strlen($fullText); $i++) {
            $char = $fullText[$i];
            if (!isset(self::$code39[$char])) {
                continue;
            }
            $pattern = self::$code39[$char];

            for ($b = 0; $b < 9; $b++) {
                $isBar = ($b % 2 === 0);
                $isWide = ($pattern[$b] === '1');
                $width = $isWide ? $wideWidth : $narrowWidth;

                if ($isBar) {
                    $elements[] = sprintf(
                        '<rect x="%.2f" y="0" width="%.2f" height="%d" fill="#000000" />',
                        $totalWidth,
                        $width,
                        $height
                    );
                }
                $totalWidth += $width;
            }
            // Inter-character space
            $totalWidth += $narrowWidth;
        }

        $totalWidth += 6; // Right quiet zone

        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%.2f" height="%d" viewBox="0 0 %.2f %d" style="max-width: 100%%; height: auto;">%s</svg>',
            $totalWidth,
            $height,
            $totalWidth,
            $height,
            implode('', $elements)
        );
    }
}

