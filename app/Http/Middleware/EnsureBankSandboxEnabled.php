<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBankSandboxEnabled
{
    /**
     * Handle an incoming request for mock bank sandbox endpoints.
     *
     * Ensures mock bank developer endpoints cannot be accessed unless
     * explicitly enabled via BANK_SANDBOX_ENABLED.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!config('bank.sandbox.enabled', false)) {
            return response()->json([
                'status'  => '99',
                'message' => 'Mock bank sandbox is disabled in this environment.',
            ], 404);
        }

        return $next($request);
    }
}
