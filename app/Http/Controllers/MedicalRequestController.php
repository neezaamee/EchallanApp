<?php

namespace App\Http\Controllers;

use App\Models\MedicalRequest;
use App\Models\MedicalCenter;
use App\Models\City;
use App\Models\Province;
use App\Models\Citizen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\BarcodeService;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = MedicalRequest::query();

        // Apply Search Filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('psid', 'like', "%{$search}%")
                  ->orWhereHas('citizen', function ($cQuery) use ($search) {
                      $cQuery->where('full_name', 'like', "%{$search}%")
                             ->orWhere('cnic', 'like', "%{$search}%")
                             ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('medicalCenter', function ($mQuery) use ($search) {
                      $mQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('creator', function ($uQuery) use ($search) {
                      $uQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $eagerLoads = ['citizen.user', 'medicalCenter.circle', 'creator.roles', 'latestPayment'];

        // Apply Role-Based Constraints
        if ($user->hasRole('citizen')) {
            $citizen = Citizen::where('user_id', $user->id)->first();
            if (!$citizen) {
                // Should not happen if registered correctly, but handle safety
                $requests = MedicalRequest::where('id', 0)->paginate(10);
            } else {
                $query->where('citizen_id', $citizen->id);
                $requests = $query->with($eagerLoads)->latest()->paginate(10);
            }
        } elseif ($user->hasRole(['doctor', 'medical_assistant'])) {
            $staff = $user->staff;
            if (!$staff) {
                return redirect()->back()->with('error', 'You are not registered as staff.');
            }
            
            $posting = $staff->activeDoctorPosting;

            if (!$posting || !$posting->medical_center_id) {
                 $requests = MedicalRequest::where('id', 0)->paginate(10);
                 session()->flash('error', 'No active medical center posting found.');
            } else {
                $query->where('medical_center_id', $posting->medical_center_id);
                $requests = $query->with($eagerLoads)->latest()->paginate(10);
            }
        } elseif ($user->hasRole('cto')) {
            $staff = $user->staff;
            $cityId = $staff?->activePosting?->city_id;

            if (!$cityId) {
                $requests = MedicalRequest::where('id', 0)->paginate(10);
                session()->flash('error', 'No active city posting found for CTO.');
            } else {
                $query->whereHas('medicalCenter.circle', function ($q) use ($cityId) {
                    $q->where('city_id', $cityId);
                });
                $requests = $query->with($eagerLoads)->latest()->paginate(10);
            }
        } else {
            // Admin or others (Super Admin, etc.)
             $requests = $query->with($eagerLoads)->latest()->paginate(10);
        }

        return view('app.medical-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('medical-requests:create');
        $provinces = Province::all();
        return view('app.medical-requests.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('medical-requests:create');
        $user = Auth::user();
        $citizenId = null;
        $medicalCenterId = null;

        $isStaff = $user->is_department_user || !empty($user->staff);
        $hasActiveMedicalCenterPosting = $isStaff && !empty($user->staff?->activeDoctorPosting?->medical_center_id);

        if ($isStaff) {
            // Staff / Department Flow (Doctor, Medical Assistant, Admin, etc.)
            $request->validate([
                'full_name' => 'required|string|max:255',
                'father_name' => 'required|string|max:255',
                'cnic' => 'required|numeric|digits:13',
                'phone' => 'required|numeric|digits:11',
                'gender' => 'required|in:male,female,other',
                'medical_center_id' => $hasActiveMedicalCenterPosting ? 'nullable|exists:medical_centers,id' : 'required|exists:medical_centers,id',
            ]);

            // Find or Create Citizen (Shadow Citizen)
            $citizen = Citizen::firstOrCreate(
                ['cnic' => $request->cnic],
                [
                    'full_name' => $request->full_name,
                    'father_name' => $request->father_name,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'user_id' => null, // Not linked to a user account yet
                    'role_id' => 4, // Citizen role ID (hardcoded based on previous file, better to lookup)                    
                ]
            );
            $citizenId = $citizen->id;

            // Determine Medical Center
            $medicalCenterId = $hasActiveMedicalCenterPosting 
                ? $user->staff->activeDoctorPosting->medical_center_id 
                : $request->medical_center_id;
        } else {
            // Citizen Flow (Citizen creates for themselves)
            $request->validate([
                'medical_center_id' => 'required|exists:medical_centers,id',
            ]);
            
            $citizen = Citizen::where('user_id', $user->id)->firstOrFail();
            $citizenId = $citizen->id;
            $medicalCenterId = $request->medical_center_id;
        }

        // Get City ID from Medical Center
        $center = MedicalCenter::find($medicalCenterId);
        $cityId = $center?->circle?->city_id;

        // Generate PSID via BankService
        $medicalFee = config('fees.medical', 200);
        $psid = \App\Services\BankService::generatePsid('MEDICAL', $medicalFee, $cityId);
        
        // Ensure uniqueness (though very unlikely to collide with 18 digits + time)
        while(MedicalRequest::where('psid', $psid)->exists()){
             $psid = \App\Services\BankService::generatePsid('MEDICAL', $medicalFee, $cityId);
        }

        $medicalRequest = MedicalRequest::create([
            'citizen_id' => $citizenId,
            'medical_center_id' => $medicalCenterId,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'psid' => $psid,
            'amount' => $medicalFee, // From central fees config
            'created_by' => $user->id,
        ]);

        return redirect()->route('medical-requests.show', $medicalRequest->id)->with('success', 'Medical request created successfully. 1Bill PSID: ' . $psid);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalRequest $medicalRequest)
    {
        $request->validate([
            'action' => 'required|in:passed,failed',
        ]);

        if ($medicalRequest->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Cannot action unpaid requests.');
        }

        $medicalRequest->update([
            'status' => $request->action,
            'doctor_action_by' => Auth::id(),
            'doctor_action_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Request updated successfully.');
    }

    public function getMedicalCenters(City $city)
    {
        // Assuming MedicalCenter has a relationship or way to filter by City
        // The MedicalCenter model has 'location' but not explicit city_id in fillable, 
        // but usually it's linked via Circle -> City or directly.
        // Let's check MedicalCenter model again. 
        // It belongsTo Circle. Circle belongsTo City.
        
        $centers = MedicalCenter::whereHas('circle', function($q) use ($city) {
            $q->where('city_id', $city->id);
        })->get(['id', 'name']);

        return response()->json($centers);
    }

    public function getCities(Province $province)
    {
        $cities = City::where('province_id', $province->id)->get(['id', 'name']);
        return response()->json($cities);
    }

    public function checkCitizen($cnic)
    {
        $citizen = Citizen::where('cnic', $cnic)->first();
        if ($citizen) {
            return response()->json([
                'found' => true,
                'citizen' => [
                    'full_name'   => $citizen->full_name,
                    'father_name' => $citizen->father_name,
                    'phone'       => $citizen->phone,
                    'gender'      => $citizen->gender,
                ]
            ]);
        }

        return response()->json([
            'found' => false
        ]);
    }


    /**
     * Display the specified medical request.
     */
    public function show(MedicalRequest $medicalRequest)
    {
        $this->authorizeAccess($medicalRequest);

        $medicalRequest->load([
            'citizen.user',
            'medicalCenter.circle.city',
            'creator.roles',
            'doctorActionBy.staff.rank',
            'latestPayment',
            'payments'
        ]);

        $barcodeSvg = BarcodeService::getCode39Svg($medicalRequest->psid, 45, 1.1, 2.6);

        return view('app.medical-requests.show', compact('medicalRequest', 'barcodeSvg'));
    }

    /**
     * Display the printable 1Bill voucher slip.
     */
    public function voucher(MedicalRequest $medicalRequest, Request $request)
    {
        $this->authorizeAccess($medicalRequest);

        $medicalRequest->load([
            'citizen',
            'medicalCenter.circle.city',
            'latestPayment',
        ]);

        $barcodeSvg = BarcodeService::getCode39Svg($medicalRequest->psid, 40, 1.05, 2.5);

        $logoPath = public_path('assets/img/team/ctpf-logo.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

        return view('app.medical-requests.voucher', compact('medicalRequest', 'barcodeSvg', 'logoBase64'));
    }

    /**
     * Download the printable 1Bill voucher slip as a PDF.
     */
    /**
     * Display the thermal printer optimized receipt (80mm).
     */
    public function thermalReceipt(MedicalRequest $medicalRequest, Request $request)
    {
        $this->authorizeAccess($medicalRequest);

        $medicalRequest->load([
            'citizen',
            'medicalCenter.circle.city',
            'doctorActionBy.staff.rank',
            'latestPayment',
        ]);

        $barcodeSvg = BarcodeService::getCode39Svg($medicalRequest->psid, 26, 0.78, 1.85);
        $isPdf = false;

        return view('app.medical-requests.receipt-thermal', compact('medicalRequest', 'barcodeSvg', 'isPdf'));
    }

    /**
     * Download thermal printer receipt as PDF (80mm).
     */
    public function downloadThermalReceipt(MedicalRequest $medicalRequest)
    {
        $this->authorizeAccess($medicalRequest);

        $medicalRequest->load([
            'citizen',
            'medicalCenter.circle.city',
            'doctorActionBy.staff.rank',
            'latestPayment',
        ]);

        $barcodeSvg = BarcodeService::getCode39Svg($medicalRequest->psid, 26, 0.78, 1.85);
        $isPdf = true;

        $paperHeight = $medicalRequest->isPaid() ? 560 : 510;

        $pdf = Pdf::loadView('app.medical-requests.receipt-thermal', compact('medicalRequest', 'barcodeSvg', 'isPdf'))
            ->setPaper([0, 0, 226.77, $paperHeight], 'portrait');

        return $pdf->download('thermal-' . $medicalRequest->psid . '.pdf');
    }

    public function downloadVoucher(MedicalRequest $medicalRequest)
    {
        $this->authorizeAccess($medicalRequest);

        $medicalRequest->load([
            'citizen',
            'medicalCenter.circle.city',
            'latestPayment',
        ]);

        $barcodeSvg = BarcodeService::getCode39Svg($medicalRequest->psid, 32, 0.95, 2.2);

        $logoPath = public_path('assets/img/team/ctpf-logo.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

        $pdf = Pdf::loadView('app.medical-requests.voucher-pdf', compact('medicalRequest', 'barcodeSvg', 'logoBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('1bill-voucher-' . $medicalRequest->psid . '.pdf');
    }

    /**
     * Authorize access to the medical request based on user role.
     */
    protected function authorizeAccess(MedicalRequest $medicalRequest): void
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            return;
        }

        if ($user->hasRole('citizen')) {
            $citizen = Citizen::where('user_id', $user->id)->first();
            if (!$citizen || $medicalRequest->citizen_id !== $citizen->id) {
                abort(403, 'Unauthorized access to this medical request.');
            }
            return;
        }

        if ($user->hasRole(['doctor', 'medical_assistant'])) {
            $staff = $user->staff;
            $posting = $staff?->activeDoctorPosting;
            if (!$posting || $medicalRequest->medical_center_id !== $posting->medical_center_id) {
                abort(403, 'Unauthorized access to medical request from another center.');
            }
            return;
        }

        if ($user->hasRole('cto')) {
            $staff = $user->staff;
            $cityId = $staff?->activePosting?->city_id;
            if (!$cityId || $medicalRequest->medicalCenter?->circle?->city_id !== $cityId) {
                abort(403, 'Unauthorized access to medical request from another city.');
            }
            return;
        }

        if (!$user->can('medical-requests:view')) {
            abort(403, 'Unauthorized access.');
        }
    }
    public function destroy(\App\Models\MedicalRequest $medicalRequest)
    {
        if (!auth()->user()->hasRole('super_admin')) {
             abort(403);
        }

        if (!$medicalRequest->isUnpaid()) {
            return back()->with('error', 'Cannot delete a paid or actioned medical request.');
        }
        
        $medicalRequest->delete(); 
        
        return back()->with('success', 'Medical Request deleted successfully.');
    }
}
