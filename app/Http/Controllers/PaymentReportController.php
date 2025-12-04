<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentReportController extends Controller
{
    public function daily()
    {
        $dailyPayments = \App\Models\Payment::select(
            \Illuminate\Support\Facades\DB::raw('DATE(paid_at) as date'),
            \Illuminate\Support\Facades\DB::raw('count(*) as total_transactions'),
            \Illuminate\Support\Facades\DB::raw('sum(amount) as total_revenue'),
            \Illuminate\Support\Facades\DB::raw('sum(case when status = "success" then 1 else 0 end) as successful'),
            \Illuminate\Support\Facades\DB::raw('sum(case when status = "failed" then 1 else 0 end) as failed')
        )
        ->where('paid_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        return view('pages.reports.payments.daily', compact('dailyPayments'));
    }

    public function monthly()
    {
        $monthlyPayments = \App\Models\Payment::select(
            \Illuminate\Support\Facades\DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as month'),
            \Illuminate\Support\Facades\DB::raw('count(*) as total_transactions'),
            \Illuminate\Support\Facades\DB::raw('sum(amount) as total_revenue'),
            \Illuminate\Support\Facades\DB::raw('avg(amount) as average_amount')
        )
        ->where('status', 'success')
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->get();

        return view('pages.reports.payments.monthly', compact('monthlyPayments'));
    }

    public function byMethod()
    {
        $methodStats = \App\Models\Payment::select(
            'payment_method',
            \Illuminate\Support\Facades\DB::raw('count(*) as total_transactions'),
            \Illuminate\Support\Facades\DB::raw('sum(amount) as total_revenue'),
            \Illuminate\Support\Facades\DB::raw('sum(case when status = "success" then 1 else 0 end) as successful'),
            \Illuminate\Support\Facades\DB::raw('sum(case when status = "failed" then 1 else 0 end) as failed')
        )
        ->groupBy('payment_method')
        ->get();

        return view('pages.reports.payments.by-method', compact('methodStats'));
    }

    public function byCenter()
    {
        $centerStats = \Illuminate\Support\Facades\DB::table('medical_centers')
            ->join('medical_requests', 'medical_centers.id', '=', 'medical_requests.medical_center_id')
            ->join('payments', 'medical_requests.id', '=', 'payments.medical_request_id')
            ->select(
                'medical_centers.name',
                \Illuminate\Support\Facades\DB::raw('count(payments.id) as total_transactions'),
                \Illuminate\Support\Facades\DB::raw('sum(payments.amount) as total_revenue')
            )
            ->where('payments.status', 'success')
            ->groupBy('medical_centers.id', 'medical_centers.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return view('pages.reports.payments.by-center', compact('centerStats'));
    }
}
