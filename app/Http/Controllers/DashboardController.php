<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) {
            return redirect()->route('dashboard.super-admin');
        }

        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard.admin');
        }

        if ($user->hasRole('challan_officer')) {
            return redirect()->route('dashboard.officer');
        }

        if ($user->hasRole('accountant')) {
            return redirect()->route('dashboard.accountant');
        }

        if ($user->hasRole('violator')) {
            if (! $user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
            return redirect()->route('dashboard.violator');
        }

        abort(403, 'Dashboard not assigned.');
    }
}
