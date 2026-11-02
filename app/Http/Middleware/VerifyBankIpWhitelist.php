<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyBankIpWhitelist
{
    /**
     * Handle an incoming request for bank integration APIs.
     *
     * Validates client IP against configured allowed IPs.
     * If BANK_IP_WHITELIST_ENABLED is false, requests are allowed to proceed cleanly.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $whitelistEnabled = (bool) config('bank.ip_whitelist.enabled', false);

        // Flexible bypass: if IP whitelisting is temporarily disabled, allow traffic
        if (!$whitelistEnabled) {
            return $next($request);
        }

        $allowedIps = config('bank.ip_whitelist.allowed_ips', []);
        $clientIp   = $request->ip();

        if ($this->isIpAllowed($clientIp, $allowedIps)) {
            return $next($request);
        }

        Log::warning("Bank API Request rejected: IP {$clientIp} is not in the allowed IP whitelist.");

        return response()->json([
            'status'  => '99',
            'message' => 'Forbidden: IP address not authorized',
        ], 403);
    }

    /**
     * Check if client IP matches any allowed IP address or CIDR range.
     */
    private function isIpAllowed(string $clientIp, array $allowedIps): bool
    {
        foreach ($allowedIps as $allowed) {
            $allowed = trim($allowed);
            if (empty($allowed)) {
                continue;
            }

            // Direct IP match (supports IPv4 and IPv6)
            if ($clientIp === $allowed) {
                return true;
            }

            // CIDR range match (e.g. 192.168.1.0/24)
            if (str_contains($allowed, '/') && $this->cidrMatch($clientIp, $allowed)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Match an IPv4 against CIDR notation.
     */
    private function cidrMatch(string $ip, string $cidr): bool
    {
        [$subnet, $mask] = explode('/', $cidr, 2);

        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ||
            !filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return false;
        }

        $maskBits = (int) $mask;
        if ($maskBits < 0 || $maskBits > 32) {
            return false;
        }

        $ipLong     = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong   = -1 << (32 - $maskBits);

        return ($ipLong & $maskLong) === ($subnetLong & $maskLong);
    }
}
