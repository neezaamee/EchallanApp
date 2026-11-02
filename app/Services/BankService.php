<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\City;

class BankService
{
    // =========================================================================
    //  PUBLIC API  (callers never need to change — dispatcher routes to V1/V2)
    // =========================================================================

    /**
     * Generate a PSID.
     *
     * Automatically dispatches to the correct version based on the
     * PSID_VERSION env variable (default = 20).
     *
     * V1 → 20 digits:  [Category(1)][CityCode(3)][ymd(6)][Random(9)][Luhn(1)]
     * V2 → 12 digits:  [Category(1)][yy(2)][ddd(3)][Random(5)][Luhn(1)]
     *
     * Note on 1Bill: The 1Link prefix (e.g. 111787477101) is combined by the user
     * in mobile banking (Prefix + 12-digit PSID = 24 digits). 1Link routes the transaction
     * and strips the prefix before calling our API. The core PSID stored and verified
     * is 12 digits, but 24-digit incoming numbers are also accepted via normalizePsid().
     *
     * @param  string   $headType  MEDICAL | TRAFFIC_CAR | TRAFFIC_BIKE | TRAFFIC_OTHER
     * @param  float    $amount    Fine / fee amount (used by V1 for logging only)
     * @param  int|null $cityId    Optional city ID (used by V1 for city-code segment)
     * @return string
     */
    public static function generatePsid(string $headType, float $amount, $cityId = null): string
    {
        $version = self::getPsidVersion();

        if ($version === 12) {
            return self::generatePsidV2($headType);
        }

        // Default: V1 (20-digit legacy)
        return self::generatePsidV1($headType, $amount, $cityId);
    }

    /**
     * Validate a PSID using the Luhn algorithm.
     *
     * Automatically normalizes prefixed PSIDs (e.g. 24-digit 1Bill numbers)
     * and validates:
     *   - 20 digits → validated as V1
     *   - 12 digits → validated as V2
     *   - Any other length → invalid
     *
     * @param  string $psid
     * @return bool
     */
    public static function validatePsid(string $psid): bool
    {
        if (!is_numeric($psid)) {
            return false;
        }

        $clean = self::normalizePsid($psid);
        $len   = strlen($clean);

        if ($len === 20) {
            return self::validatePsidV1($clean);
        }

        if ($len === 12) {
            return self::validatePsidV2($clean);
        }

        // Any other length is invalid
        return false;
    }

    /**
     * Return the configured global bank prefix.
     *
     * @return string
     */
    public static function getBankPrefix(): string
    {
        return (string) config('bank.prefix', '');
    }

    /**
     * Strip the global bank prefix from an incoming PSID/consumer number if present.
     * e.g. "111787477101126303043625" -> "126303043625"
     *
     * @param  string $consumerNumber
     * @return string
     */
    public static function normalizePsid(string $consumerNumber): string
    {
        $consumerNumber = trim($consumerNumber);
        $prefix = self::getBankPrefix();

        if ($prefix !== '' && str_starts_with($consumerNumber, $prefix)) {
            return substr($consumerNumber, strlen($prefix));
        }

        return $consumerNumber;
    }

    /**
     * Format a 12-digit PSID for 1Link 1Bill payment display (Prefix + PSID).
     * e.g. "126303043625" -> "111787477101126303043625"
     *
     * @param  string $psid
     * @return string
     */
    public static function format1BillNumber(string $psid): string
    {
        return self::getBankPrefix() . $psid;
    }

    /**
     * Return the active PSID version (12 or 20).
     *
     * @return int
     */
    public static function getPsidVersion(): int
    {
        return (int) config('bank.psid_version', 20);
    }

    /**
     * Check payment status via bank API.
     *
     * @param  string $psid
     * @return string  'PAID' | 'UNPAID'
     */
    public static function checkStatus(string $psid): string
    {
        if (config('bank.sandbox.enabled')) {
            $url = config('bank.sandbox.api_url') . '/payment-status';
            try {
                $response = Http::post($url, ['psid' => $psid]);
                if ($response->successful()) {
                    return $response->json()['data']['payment_status'] ?? 'UNPAID';
                }
            } catch (\Exception $e) {
                Log::error('Bank API Error: ' . $e->getMessage());
            }
            return 'UNPAID';
        }

        return 'UNPAID';
    }

    // =========================================================================
    //  V1  —  LEGACY 20-DIGIT PSID  (preserved exactly, never deleted)
    //
    //  Structure: [Category(1)] [CityCode(3)] [ymd(6)] [Random(9)] [Luhn(1)]
    //  Example:    2              042            260701   382719465   7
    //  Full:       20420260701382719467
    //
    //  Restore at any time by setting PSID_VERSION=20 in .env
    // =========================================================================

    /**
     * Generate a 20-digit PSID (V1 — legacy format).
     *
     * @param  string   $headType
     * @param  float    $amount
     * @param  int|null $cityId
     * @return string
     */
    private static function generatePsidV1(string $headType, float $amount, $cityId = null): string
    {
        // 1. Category Code (1 digit)
        $categoryMap = [
            'MEDICAL'       => '1',
            'TRAFFIC_CAR'   => '2',
            'TRAFFIC_BIKE'  => '3',
            'TRAFFIC_OTHER' => '4',
        ];
        $category = $categoryMap[$headType] ?? '9';

        // 2. City Code (3 digits — zero-padded)
        $cityCode = '000';
        if ($cityId) {
            $city = City::find($cityId);
            if ($city && $city->code) {
                $cityCode = str_pad($city->code, 3, '0', STR_PAD_LEFT);
            } elseif ($city) {
                $cityCode = str_pad($city->id, 3, '0', STR_PAD_LEFT);
            }
        }

        // 3. Date — ymd (6 digits)
        $date = date('ymd');

        // 4. Random numeric string (9 digits)
        $random = '';
        while (strlen($random) < 9) {
            $random .= mt_rand(0, 9);
        }

        // 5. Combine 19 base digits
        $basePsid = $category . $cityCode . $date . $random;

        // 6. Append Luhn check digit
        $checkDigit = self::calculateLuhn($basePsid);

        return $basePsid . $checkDigit; // 20 digits
    }

    /**
     * Validate a 20-digit PSID (V1).
     *
     * @param  string $psid
     * @return bool
     */
    private static function validatePsidV1(string $psid): bool
    {
        if (strlen($psid) !== 20 || !is_numeric($psid)) {
            return false;
        }

        $base  = substr($psid, 0, 19);
        $check = (int) substr($psid, 19, 1);

        return self::calculateLuhn($base) === $check;
    }

    // =========================================================================
    //  V2  —  12-DIGIT PSID  (1Link 1Bill Compliant)
    //
    //  Structure: [Category(1)] [yy(2)] [ddd(3)] [Random(5)] [Luhn(1)]
    //  Example:    2              26      182       48231       7
    //  Full:       226182482317  (12 digits)
    //
    //  1Bill Presentation:
    //    When paying via 1Bill in banking apps, the user enters:
    //    [Bank Prefix (12)] + [PSID (12)] = 24 digits.
    //    e.g. 111787477101226182482317
    //    1Link routes using the prefix and forwards the 12-digit PSID to our API.
    //    Our normalizePsid() handles both stripped 12-digit and full 24-digit payloads.
    //
    //  Activate by setting PSID_VERSION=12 in .env
    // =========================================================================

    /**
     * Generate a 12-digit PSID (V2 — 1Link compliant format).
     *
     * @param  string $headType
     * @return string
     */
    private static function generatePsidV2(string $headType): string
    {
        // 1. Category Code (1 digit)
        $categoryMap = [
            'MEDICAL'       => '1',
            'TRAFFIC_CAR'   => '2',
            'TRAFFIC_BIKE'  => '3',
            'TRAFFIC_OTHER' => '4',
        ];
        $category = $categoryMap[$headType] ?? '9';

        // 2. Year — yy (2 digits, e.g. "26" for 2026)
        $year = date('y');

        // 3. Day of year — ddd (3 digits, 001–366)
        //    date('z') is zero-indexed (0=Jan 1), +1 makes it 1-indexed
        //    PHP automatically accounts for leap years (Feb 29 = day 60 in a leap year)
        $dayOfYear = str_pad((int) date('z') + 1, 3, '0', STR_PAD_LEFT);

        // 4. Random 5-digit number (00000–99999 → 100,000 combinations/day/category)
        $random = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);

        // 5. Combine 11 base digits
        $base = $category . $year . $dayOfYear . $random;

        // 6. Append Luhn check digit to produce self-contained 12-digit core
        $checkDigit = self::calculateLuhn($base);
        $corePsid   = $base . $checkDigit; // Exactly 12 digits

        // 7. Prepend Bank Prefix (e.g. 111787477101) to form the full 24-digit PSID
        $bankPrefix = self::getBankPrefix();

        return $bankPrefix . $corePsid;
    }

    /**
     * Validate a 12-digit PSID (V2).
     *
     * @param  string $psid
     * @return bool
     */
    private static function validatePsidV2(string $psid): bool
    {
        if (strlen($psid) !== 12 || !is_numeric($psid)) {
            return false;
        }

        $base  = substr($psid, 0, 11);
        $check = (int) substr($psid, 11, 1);

        return self::calculateLuhn($base) === $check;
    }

    // =========================================================================
    //  SHARED UTILITIES
    // =========================================================================

    /**
     * Calculate Luhn check digit for any numeric string.
     *
     * Works for both V1 (19-digit base) and V2 (11-digit base) without changes.
     *
     * @param  string $number
     * @return int
     */
    private static function calculateLuhn(string $number): int
    {
        $sum       = 0;
        $numDigits = strlen($number);
        $parity    = ($numDigits + 1) % 2;

        for ($i = 0; $i < $numDigits; $i++) {
            $digit = (int) $number[$i];
            if ($i % 2 === $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }

        return (10 - ($sum % 10)) % 10;
    }
}
