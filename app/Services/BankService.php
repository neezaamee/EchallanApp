<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BankService
{
    /**
     * Generate a PSID for a specific payment head (MEDICAL, TRAFFIC_CAR, TRAFFIC_BIKE)
     *
     * @param string $headType
     * @param float $amount
     * @param array $additionalData
     * @return string
     */
    public static function generatePsid(string $headType, float $amount, array $additionalData = [])
    {
        // 1. Check if Sandbox is enabled
        if (config('bank.sandbox.enabled')) {
            return self::generateSandboxPsid($headType, $amount);
        }

        // 2. Real API Integration (Placeholder)
        // return self::generateLivePsid($headType, $amount);
        
        // Fallback to local generation if API fails or not implemented
        return self::generateLocalPsid($headType);
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
                 \Log::error("Bank API Error: " . $e->getMessage());
             }
             return 'UNPAID'; // Default
        }

        return 'UNPAID';
    }

    private static function generateSandboxPsid($headType, $amount)
    {
        $url = config('bank.sandbox.api_url') . '/generate-psid';
        
        try {
            $response = Http::post($url, [
                'head' => $headType,
                'amount' => $amount
            ]);

            if ($response->successful()) {
                return $response->json()['data']['psid'];
            }
        } catch (\Exception $e) {
            \Log::error("Bank Sandbox API Error: " . $e->getMessage());
        }

        // Fallback if Mock API is down
        return self::generateLocalPsid($headType);
    }

    private static function generateLocalPsid($headType)
    {
        // 1. Get Prefix (Using config, check length)
        $prefix = config("bank.heads.{$headType}.prefix", '99');
        
        // Ensure prefix is reasonable (e.g., 2 chars). If shorter or longer, handle it? 
        // User config: 10, 20, 30. (2 digits)
        
        // 2. Date Component (6 digits: ymd)
        $date = date('ymd');
        
        // 3. Calculate remaining length for Random component
        // Target: 20 digits
        // Used: Length(Prefix) + 6
        $usedLength = strlen($prefix) + 6;
        $randomLength = 20 - $usedLength;
        
        if ($randomLength < 1) {
            // Fallback safety if prefix is huge (unlikely)
            $randomLength = 4;
        }

        // 4. Generate Random Numeric String
        // mt_rand max is limited, so we concat multiple if needed or use a loop
        $random = '';
        while (strlen($random) < $randomLength) {
            $random .= mt_rand(0, 9);
        }
        $random = substr($random, 0, $randomLength);

        return $prefix . $date . $random;
    }
}
