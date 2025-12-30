<?php

namespace App\Http\Controllers;

use App\Models\MedicalRequest;
use App\Models\Citizen;
use Illuminate\Support\Facades\Auth;

class RoleDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('super_admin')) return $this->superAdmin();
        if ($user->hasRole('admin')) return $this->admin();
        if ($user->hasRole('doctor')) return $this->doctor();
        if ($user->hasRole('cto')) return $this->cto();
        if ($user->hasRole('challan_officer')) return $this->officer();
        if ($user->hasRole('accountant')) return $this->accountant();
        if ($user->hasRole('citizen')) return $this->citizen();

        // Fallback or generic dashboard
        return view('pages.dashboards.index');
    }

    public function superAdmin()
    {
        // Add stats for super admin here if needed
        return view('pages.dashboards.super-admin');
    }

    public function admin()
    {
        // Add stats for admin here if needed
        return view('pages.dashboards.admin');
    }

    public function cto()
    {
        $user = Auth::user();
        $staff = $user->staff;

        $data = [
            'pendingUnpaid' => 0,
            'pendingPaid' => 0,
            'passedThisMonth' => 0,
            'failedThisMonth' => 0,
            'recentRequests' => collect(),
            'cityName' => 'All Locations',
        ];

        if ($staff && $staff->activePosting) {
            $posting = $staff->activePosting;
            $cityId = $posting->city_id;

            // If city_id is not directly set, try to get it from circle or medical center
            if (!$cityId) {
                if ($posting->circle_id) {
                    $cityId = $posting->circle?->city_id ?? null;
                } elseif ($posting->medical_center_id) {
                    $cityId = $posting->medicalCenter?->circle?->city_id ?? null;
                }
            }

            if ($cityId) {
                $city = \App\Models\City::find($cityId);
                $data['cityName'] = $city ? $city->name : 'N/A';

                $baseQuery = MedicalRequest::whereHas('medicalCenter.circle', function ($query) use ($cityId) {
                    $query->where('city_id', $cityId);
                });

                // Count pending unpaid
                $data['pendingUnpaid'] = (clone $baseQuery)->where('status', 'pending')
                    ->where('payment_status', 'unpaid')
                    ->count();

                // Count pending paid (actionable)
                $data['pendingPaid'] = (clone $baseQuery)->where('status', 'pending')
                    ->where('payment_status', 'paid')
                    ->count();

                // Count passed this month
                $data['passedThisMonth'] = (clone $baseQuery)->where('status', 'passed')
                    ->whereMonth('doctor_action_at', now()->month)
                    ->whereYear('doctor_action_at', now()->year)
                    ->count();

                // Count failed this month
                $data['failedThisMonth'] = (clone $baseQuery)->where('status', 'failed')
                    ->whereMonth('doctor_action_at', now()->month)
                    ->whereYear('doctor_action_at', now()->year)
                    ->count();

                // Recent requests
                $data['recentRequests'] = (clone $baseQuery)->with(['citizen', 'medicalCenter'])
                    ->latest()
                    ->take(10)
                    ->get();
            }
        }

        return view('pages.dashboards.cto', $data);
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
            'cityName' => 'N/A',
        ];

        if ($staff && $staff->activeDoctorPosting) {
            $medicalCenterId = $staff->activeDoctorPosting->medical_center_id;
            $data['medicalCenter'] = $staff->activeDoctorPosting->medicalCenter;
            $data['cityName'] = $data['medicalCenter']?->circle?->city?->name ?? 'N/A';

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

        return view('pages.dashboards.doctor', $data);
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

        return view('pages.dashboards.citizen', $data);
    }
}
