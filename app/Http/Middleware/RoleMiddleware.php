<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware('role:admin|super_admin')
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $rolesArr = explode('|', $roles);

        // super_admin bypass
        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        }

        if ($request->user()->hasRole($rolesArr)) {
            return $next($request);
        }

        abort(403, 'Unauthorized.');
    }
}
