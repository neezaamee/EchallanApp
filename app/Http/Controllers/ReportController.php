<?php

namespace App\Http\Controllers;

use App\Models\Challan;
use App\Models\MedicalCenter;
use App\Models\MedicalRequest;
use App\Models\Payment;
use App\Models\Warning;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Reports & Analytics Executive Dashboard
     */
    public function analytics(Request $request)
    {
        $user = Auth::user();
        if (!$user->canany(['reports:view', 'reports:analytics']) && !$user->hasRole('super_admin')) {
            abort(403, 'Unauthorized access to Reports & Analytics.');
        }

        // Date range filters (default to current month)
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        $startDate = Carbon::parse($dateFrom)->startOfDay();
        $endDate = Carbon::parse($dateTo)->endOfDay();

        // 1. Financial & Revenue Metrics
        $medicalRevenue = (float) MedicalRequest::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $challanRevenue = (float) Challan::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('fine_amount');

        $totalRevenue = $medicalRevenue + $challanRevenue;

        // 2. Traffic Challans Metrics
        $challansQuery = Challan::whereBetween('created_at', [$startDate, $endDate]);
        $totalChallans = (clone $challansQuery)->count();
        $paidChallans = (clone $challansQuery)->where('payment_status', 'paid')->count();
        $pendingChallans = (clone $challansQuery)->where('payment_status', '!=', 'paid')->count();
        $carChallans = (clone $challansQuery)->where('vehicle_type', 'car')->count();
        $bikeChallans = (clone $challansQuery)->whereIn('vehicle_type', ['motorcycle', 'bike'])->count();

        // 3. Medical Requests Metrics
        $medicalQuery = MedicalRequest::whereBetween('created_at', [$startDate, $endDate]);
        $totalMedical = (clone $medicalQuery)->count();
        $passedMedical = (clone $medicalQuery)->where('status', 'passed')->count();
        $failedMedical = (clone $medicalQuery)->where('status', 'failed')->count();
        $pendingMedical = (clone $medicalQuery)->where('status', 'pending')->count();
        $paidMedical = (clone $medicalQuery)->where('payment_status', 'paid')->count();

        // 4. Warnings Metrics
        $warningsQuery = Warning::whereBetween('created_at', [$startDate, $endDate]);
        $totalWarnings = (clone $warningsQuery)->count();
        $uniqueViolators = (clone $warningsQuery)->distinct('violator_cnic')->count('violator_cnic');

        // Top 5 Warning Violations
        $topViolations = Warning::select('violation_name', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('violation_name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Top 5 Medical Centers by volume
        $topMedicalCenters = MedicalCenter::withCount(['medicalRequests' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['medicalRequests' => function ($q) use ($startDate, $endDate) {
                $q->where('payment_status', 'paid')->whereBetween('created_at', [$startDate, $endDate]);
            }], 'amount')
            ->orderByDesc('medical_requests_count')
            ->limit(5)
            ->get();

        return view('app.reports.analytics', compact(
            'dateFrom',
            'dateTo',
            'totalRevenue',
            'medicalRevenue',
            'challanRevenue',
            'totalChallans',
            'paidChallans',
            'pendingChallans',
            'carChallans',
            'bikeChallans',
            'totalMedical',
            'passedMedical',
            'failedMedical',
            'pendingMedical',
            'paidMedical',
            'totalWarnings',
            'uniqueViolators',
            'topViolations',
            'topMedicalCenters'
        ));
    }
}
