<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyBankBearerToken
{
    /**
     * Handle an incoming request for bank integration APIs.
     *
     * Validates the Bearer token using constant-time hash_equals().
     * Enforces fail-closed behavior: if no callback token is configured,
     * requests are strictly denied rather than permitted.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            return response()->json([
                'status'  => '99',
                'message' => 'Unauthorized: Missing Authorization header',
            ], 401);
        }

        // Extract token from "Bearer <token>" or standalone token string
        if (str_starts_with($authHeader, 'Bearer ')) {
            $providedToken = substr($authHeader, 7);
        } else {
            $providedToken = $authHeader;
        }

        $expectedToken = (string) config('bank.sandbox.callback_token');

        // Fail-closed: do not allow requests if token is unconfigured or empty
        if (empty($expectedToken)) {
            Log::error('Bank API Request rejected: BANK_CALLBACK_TOKEN is not configured in the application.');
            return response()->json([
                'status'  => '99',
                'message' => 'Unauthorized: Authentication service misconfigured',
            ], 401);
        }

        // Constant-time comparison to prevent timing attacks
        if (!hash_equals($expectedToken, $providedToken)) {
            Log::warning('Bank API Request rejected: Invalid Bearer token from IP ' . $request->ip());
            return response()->json([
                'status'  => '99',
                'message' => 'Unauthorized: Invalid token',
            ], 401);
        }

        return $next($request);
    }
}
