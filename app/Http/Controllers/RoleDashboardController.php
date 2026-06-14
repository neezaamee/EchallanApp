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
        $role = $user->getRoleNames()->first() ?? 'default';
        
        $data = match($role) {
            'super_admin' => $this->getSuperAdminData(),
            'admin'       => $this->getAdminData(),
            'doctor'      => $this->getDoctorData(),
            'medical_assistant' => $this->getDoctorData(),
            'cto'         => $this->getCTOData(),
            'lifter_challan_officer' => $this->getLifterChallanOfficerData(),
            'warning_officer' => $this->getWarningOfficerData(),
            'duty_officer'    => $this->getDutyOfficerData(),
            'accountant'  => $this->getAccountantData(),
            'citizen'     => $this->getCitizenData(),
            default       => [],
        };

        $data['role'] = $role;

        return view('app.dashboards.dashboard', $data);
    }

    private function getSuperAdminData()
    {
        $data = [
            'totalUsers' => \App\Models\User::count(),
            'totalRoles' => \Spatie\Permission\Models\Role::count(),
            'totalLogs' => \Spatie\Activitylog\Models\Activity::count(),
            'totalBackups' => 0,
        ];

        try {
            $backupPath = storage_path('app/backups');
            if (is_dir($backupPath)) {
                $data['totalBackups'] = count(glob($backupPath . '/*'));
            }
        } catch (\Exception $e) {}

        return $data;
    }

    private function getAdminData()
    {
        return [
            'totalCenters' => \App\Models\MedicalCenter::count(),
            'totalStaff' => \App\Models\Staff::count(),
            'totalRequests' => \App\Models\MedicalRequest::count(),
            'recentLogs' => \Spatie\Activitylog\Models\Activity::latest()->take(5)->get(),
        ];
    }

    private function getCTOData()
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
            $cityId = $posting->city_id ?? ($posting->circle?->city_id ?? ($posting->medicalCenter?->circle?->city_id ?? null));

            if ($cityId) {
                $city = \App\Models\City::find($cityId);
                $data['cityName'] = $city ? $city->name : 'N/A';

                $baseQuery = MedicalRequest::whereHas('medicalCenter.circle', function ($query) use ($cityId) {
                    $query->where('city_id', $cityId);
                });

                $data['pendingUnpaid'] = (clone $baseQuery)->where('status', 'pending')->where('payment_status', 'unpaid')->count();
                $data['pendingPaid'] = (clone $baseQuery)->where('status', 'pending')->where('payment_status', 'paid')->count();
                $data['passedThisMonth'] = (clone $baseQuery)->where('status', 'passed')->whereMonth('doctor_action_at', now()->month)->whereYear('doctor_action_at', now()->year)->count();
                $data['failedThisMonth'] = (clone $baseQuery)->where('status', 'failed')->whereMonth('doctor_action_at', now()->month)->whereYear('doctor_action_at', now()->year)->count();
                $data['recentRequests'] = (clone $baseQuery)->with(['citizen', 'medicalCenter'])->latest()->take(10)->get();
            }
        }

        return $data;
    }

    private function getDoctorData()
    {
        $user = Auth::user();
        $staff = $user->staff ?? null;

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

            $baseQuery = MedicalRequest::where('medical_center_id', $medicalCenterId);

            $data['pendingUnpaid'] = (clone $baseQuery)->where('status', 'pending')->where('payment_status', 'unpaid')->count();
            $data['pendingPaid'] = (clone $baseQuery)->where('status', 'pending')->where('payment_status', 'paid')->count();
            $data['passedThisMonth'] = (clone $baseQuery)->where('status', 'passed')->whereMonth('doctor_action_at', now()->month)->whereYear('doctor_action_at', now()->year)->count();
            $data['failedThisMonth'] = (clone $baseQuery)->where('status', 'failed')->whereMonth('doctor_action_at', now()->month)->whereYear('doctor_action_at', now()->year)->count();
            $data['recentRequests'] = (clone $baseQuery)->with('citizen')->latest()->take(10)->get();
        }

        return $data;
    }

    private function getLifterChallanOfficerData()
    {
        $user = Auth::user();
        return [
            'totalChallans' => \App\Models\Challan::where('officer_id', $user->id)->count(),
            'unpaidChallans' => \App\Models\Challan::where('officer_id', $user->id)->where('payment_status', 'unpaid')->count(),
            'todayChallans' => \App\Models\Challan::where('officer_id', $user->id)->whereDate('created_at', now()->today())->count(),
        ];
    }

    private function getWarningOfficerData()
    {
        $user = Auth::user();
        return [
            'totalWarnings' => \App\Models\Warning::where('officer_id', $user->id)->count(),
            'todayWarnings' => \App\Models\Warning::where('officer_id', $user->id)->whereDate('created_at', now()->today())->count(),
            'recentWarnings' => \App\Models\Warning::where('officer_id', $user->id)->latest()->take(5)->get(),
        ];
    }

    private function getDutyOfficerData()
    {
        $user = Auth::user();
        $staff = $user->staff;

        $data = [
            'totalBounded' => 0,
            'paidBounded' => 0,
            'unpaidBounded' => 0,
            'boundedVehicles' => collect(),
            'dumpingPoint' => null,
        ];

        if ($staff && $staff->activePosting) {
            $dumpingPointId = $staff->activePosting->dumping_point_id;
            
            if ($dumpingPointId) {
                $data['dumpingPoint'] = \App\Models\DumpingPoint::find($dumpingPointId);
                
                $baseQuery = \App\Models\Challan::where('dumping_point_id', $dumpingPointId)
                    ->bounded();

                $data['totalBounded'] = (clone $baseQuery)->count();
                $data['paidBounded'] = (clone $baseQuery)->where('payment_status', 'paid')->count();
                $data['unpaidBounded'] = (clone $baseQuery)->where('payment_status', 'unpaid')->count();
                $data['boundedVehicles'] = (clone $baseQuery)->with(['officer', 'pickUpPoint'])->latest()->take(10)->get();
            }
        }

        return $data;
    }

    private function getAccountantData()
    {
        return [
            'totalRevenue' => \App\Models\Payment::where('status', 'success')->sum('amount'),
            'monthlyRevenue' => \App\Models\Payment::where('status', 'success')->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum('amount'),
            'pendingRefunds' => \App\Models\Refund::where('status', 'pending')->count(),
            'recentPayments' => \App\Models\Payment::latest()->take(10)->get(),
        ];
    }

    private function getCitizenData()
    {
        $user = Auth::user();
        $cnic = $user->cnic;

        $data = [
            'totalRequests' => 0,
            'pendingRequests' => 0,
            'approvedRequests' => 0,
            'unpaidRequests' => 0,
            'recentRequests' => collect(),  // Medical
            'recentChallans' => collect(),  // Traffic
        ];

        if ($cnic) {
            // 1. Medical Requests (via associated Citizen record for strict relationship, or by CNIC)
            $medicalQuery = MedicalRequest::whereHas('citizen', function($q) use ($cnic) {
                $q->where('cnic', $cnic);
            });

            // 2. Traffic Challans (via violator_cnic)
            $challanQuery = \App\Models\Challan::where('violator_cnic', $cnic);

            // Combined Stats
            $data['totalRequests'] = $medicalQuery->count() + $challanQuery->count();
            
            // Pending/Processing
            $data['pendingRequests'] = $medicalQuery->clone()->where('status', 'pending')->count() + 
                                     $challanQuery->clone()->whereNotIn('status', ['paid', 'released'])->count();
            
            // Approved/Passed/Released
            $data['approvedRequests'] = $medicalQuery->clone()->where('status', 'passed')->count() + 
                                      $challanQuery->clone()->where('status', 'released')->count();
            
            // Unpaid
            $data['unpaidRequests'] = $medicalQuery->clone()->where('payment_status', 'unpaid')->count() + 
                                    $challanQuery->clone()->where('payment_status', 'unpaid')->count();

            // Recent Lists
            $data['recentRequests'] = $medicalQuery->with('medicalCenter')->latest()->take(5)->get();
            $data['recentChallans'] = $challanQuery->latest()->take(5)->get();
        }

        return $data;
    }
}
