<?php

namespace App\Http\Controllers;

use App\Models\MedicalRequest;
use App\Models\Citizen;
use Illuminate\Support\Facades\Auth;

class RoleDashboardController extends Controller
{
    public function index(){
        return view('pages.dashboards.index');
    }
    public function superAdmin()
    {
        return view('pages.dashboards.index');
    }

    public function admin()
    {
        return view('pages.dashboards.admin');
    }

    public function doctor()
    {
        $user = Auth::user();
        $staff = $user->staff;
        
        $data = [
            'pendingUnpaid' => 0,
            'pendingPaid' => 0,
            'passedThisMonth' => 0,
            'failedThisMonth' => 0,
            'recentRequests' => collect(),
            'medicalCenter' => null,
        ];

        if ($staff && $staff->activeDoctorPosting) {
            $medicalCenterId = $staff->activeDoctorPosting->medical_center_id;
            $data['medicalCenter'] = $staff->activeDoctorPosting->medicalCenter;

            // Count pending unpaid
            $data['pendingUnpaid'] = MedicalRequest::where('medical_center_id', $medicalCenterId)
                ->where('status', 'pending')
                ->where('payment_status', 'unpaid')
                ->count();

            // Count pending paid (actionable)
            $data['pendingPaid'] = MedicalRequest::where('medical_center_id', $medicalCenterId)
                ->where('status', 'pending')
                ->where('payment_status', 'paid')
                ->count();

            // Count passed this month
            $data['passedThisMonth'] = MedicalRequest::where('medical_center_id', $medicalCenterId)
                ->where('status', 'passed')
                ->whereMonth('doctor_action_at', now()->month)
                ->whereYear('doctor_action_at', now()->year)
                ->count();

            // Count failed this month
            $data['failedThisMonth'] = MedicalRequest::where('medical_center_id', $medicalCenterId)
                ->where('status', 'failed')
                ->whereMonth('doctor_action_at', now()->month)
                ->whereYear('doctor_action_at', now()->year)
                ->count();

            // Recent requests
            $data['recentRequests'] = MedicalRequest::where('medical_center_id', $medicalCenterId)
                ->with('citizen')
                ->latest()
                ->take(10)
                ->get();
        }

        return view('pages.dashboards.index', $data);
    }

    public function officer()
    {
        return view('pages.dashboards.officer');
    }

    public function accountant()
    {
        return view('pages.dashboards.accountant');
    }

    public function citizen()
    {
        $user = Auth::user();
        $citizen = Citizen::where('user_id', $user->id)->first();

        $data = [
            'totalRequests' => 0,
            'pendingRequests' => 0,
            'approvedRequests' => 0,
            'unpaidRequests' => 0,
            'recentRequests' => collect(),
        ];

        if ($citizen) {
            // Total requests
            $data['totalRequests'] = MedicalRequest::where('citizen_id', $citizen->id)->count();

            // Pending requests
            $data['pendingRequests'] = MedicalRequest::where('citizen_id', $citizen->id)
                ->where('status', 'pending')
                ->count();

            // Approved requests
            $data['approvedRequests'] = MedicalRequest::where('citizen_id', $citizen->id)
                ->where('status', 'passed')
                ->count();

            // Unpaid requests
            $data['unpaidRequests'] = MedicalRequest::where('citizen_id', $citizen->id)
                ->where('payment_status', 'unpaid')
                ->count();

            // Recent requests
            $data['recentRequests'] = MedicalRequest::where('citizen_id', $citizen->id)
                ->with('medicalCenter')
                ->latest()
                ->take(10)
                ->get();
        }

        return view('pages.dashboards.index', $data);
    }
}
