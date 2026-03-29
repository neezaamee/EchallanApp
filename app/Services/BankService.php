<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\City;

class BankService
{
    /**
     * Generate a PSID for a specific payment head (MEDICAL, TRAFFIC_CAR, TRAFFIC_BIKE)
     * 
     * NEW Structure (20 Digits):
     * [Category(1)] [CityCode(3)] [Date(6)] [Random(9)] [Check Digit(1)]
     *
     * @param string $headType
     * @param float $amount
     * @param int|null $cityId
     * @return string
     */
    public static function generatePsid(string $headType, float $amount, $cityId = null)
    {
        // 1. Get Category Code
        $categoryMap = [
            'MEDICAL' => '1',
            'TRAFFIC_CAR' => '2',
            'TRAFFIC_BIKE' => '3',
            'TRAFFIC_OTHER' => '4',
        ];
        $category = $categoryMap[$headType] ?? '9';

        // 2. Get City Code (3 digits)
        $cityCode = '000';
        if ($cityId) {
            $city = City::find($cityId);
            if ($city && $city->code) {
                $cityCode = str_pad($city->code, 3, '0', STR_PAD_LEFT);
            } else if ($city) {
                $cityCode = str_pad($city->id, 3, '0', STR_PAD_LEFT);
            }
        }

        // 3. Date Component (6 digits: ymd)
        $date = date('ymd');

        // 4. Generate Random Numeric String (9 digits)
        $random = '';
        while (strlen($random) < 9) {
            $random .= mt_rand(0, 9);
        }

        // 5. Combine first 19 digits
        $basePsid = $category . $cityCode . $date . $random;

        // 6. Calculate Check Digit (Luhn Algorithm)
        $checkDigit = self::calculateLuhn($basePsid);

        return $basePsid . $checkDigit;
    }

    /**
     * Calculate Luhn Check Digit for a given numeric string
     * 
     * @param string $number
     * @return int
     */
    private static function calculateLuhn($number)
    {
        $sum = 0;
        $numDigits = strlen($number);
        $parity = ($numDigits + 1) % 2;

        for ($i = 0; $i < $numDigits; $i++) {
            $digit = (int)$number[$i];
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }

        return (10 - ($sum % 10)) % 10;
    }

    /**
     * Validate a PSID using Luhn Algorithm
     * 
     * @param string $psid
     * @return bool
     */
    public static function validatePsid($psid)
    {
        if (strlen($psid) !== 20 || !is_numeric($psid)) {
            return false;
        }

        $base = substr($psid, 0, 19);
        $check = (int)substr($psid, 19, 1);

        return self::calculateLuhn($base) === $check;
    }

    /**
     * Check payment status via API
     * 
     * @param string $psid
     * @return string 'PAID' or 'UNPAID'
     */
    public static function checkStatus(string $psid)
    {
        if (config('bank.sandbox.enabled')) {
             $url = config('bank.sandbox.api_url') . '/payment-status';
             try {
                $response = Http::post($url, ['psid' => $psid]);
                if ($response->successful()) {
                    return $response->json()['data']['payment_status'] ?? 'UNPAID';
                }
             } catch (\Exception $e) {
                Log::error("Bank API Error: " . $e->getMessage());
             }
             return 'UNPAID'; 
        }

        return 'UNPAID';
    }
}
