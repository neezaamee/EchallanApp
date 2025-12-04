<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentDashboardController extends Controller
{
    public function index()
    {
        // Today's metrics
        $todayRevenue = \App\Models\Payment::where('status', 'success')
            ->whereDate('paid_at', today())
            ->sum('amount');

        $todayTransactions = \App\Models\Payment::whereDate('created_at', today())->count();

        // Monthly metrics
        $monthlyRevenue = \App\Models\Payment::where('status', 'success')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        // Success Rate
        $totalAttempts = \App\Models\Payment::count();
        $successfulPayments = \App\Models\Payment::where('status', 'success')->count();
        $successRate = $totalAttempts > 0 ? round(($successfulPayments / $totalAttempts) * 100, 1) : 0;

        // Recent Transactions
        $recentPayments = \App\Models\Payment::with('medicalRequest.citizen')
            ->latest()
            ->take(10)
            ->get();

        // Payment Methods Breakdown
        $paymentMethods = \App\Models\Payment::where('status', 'success')
            ->select('payment_method', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        return view('pages.dashboard.payments.index', compact(
            'todayRevenue',
            'todayTransactions',
            'monthlyRevenue',
            'successRate',
            'recentPayments',
            'paymentMethods'
        ));
    }
}
