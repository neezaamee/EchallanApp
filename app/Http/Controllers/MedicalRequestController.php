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

class MedicalRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('citizen')) {
            $citizen = Citizen::where('user_id', $user->id)->first();
            $requests = MedicalRequest::where('citizen_id', $citizen->id)
                ->with('medicalCenter')
                ->latest()
                ->paginate(10);
        } elseif ($user->hasRole('doctor')) {
            // Find the medical center the doctor is assigned to
            // Assuming doctor is linked via staff -> staff_posting -> medical_center
            $staff = $user->staff;
            if (!$staff) {
                return redirect()->back()->with('error', 'You are not registered as staff.');
            }
            
            $posting = $staff->activeDoctorPosting;

            if (!$posting || !$posting->medical_center_id) {
                 // Fallback or empty if no active posting
                 $requests = collect(); // Empty collection
                 // You might want to show a message
                 session()->flash('error', 'No active medical center posting found.');
            } else {
                $requests = MedicalRequest::where('medical_center_id', $posting->medical_center_id)
                    ->with('citizen.user')
                    ->latest()
                    ->paginate(10);
            }
        } else {
            // Admin or others? For now just empty or all
             $requests = MedicalRequest::with(['citizen.user', 'medicalCenter'])->latest()->paginate(10);
        }

        return view('pages.medical-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::all();
        return view('pages.medical-requests.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $citizenId = null;
        $medicalCenterId = null;

        if ($user->hasRole('doctor')) {
            // Doctor Flow
            $request->validate([
                'full_name' => 'required|string|max:255',
                'father_name' => 'required|string|max:255',
                'cnic' => 'required|numeric|digits:13', // Check uniqueness logic later if needed
                'phone' => 'required|numeric|digits:11', // 03xxxxxxxxx
                'gender' => 'required|in:male,female,other',
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

            // Get Doctor's Medical Center
            $staff = $user->staff;
            if (!$staff || !$staff->activeDoctorPosting) {
                return redirect()->back()->with('error', 'You must have an active posting to create requests.');
            }
            $medicalCenterId = $staff->activeDoctorPosting->medical_center_id;

        } else {
            // Citizen Flow
            $request->validate([
                'medical_center_id' => 'required|exists:medical_centers,id',
            ]);
            
            $citizen = Citizen::where('user_id', $user->id)->firstOrFail();
            $citizenId = $citizen->id;
            $medicalCenterId = $request->medical_center_id;
        }

        // Generate PSID (Simple random for now, can be more complex)
        $psid = strtoupper(Str::random(10)); 
        // Ensure uniqueness
        while(MedicalRequest::where('psid', $psid)->exists()){
             $psid = strtoupper(Str::random(10));
        }

        MedicalRequest::create([
            'citizen_id' => $citizenId,
            'medical_center_id' => $medicalCenterId,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'psid' => $psid,
            'amount' => 500, // Fixed amount for now
            'created_by' => $user->id,
        ]);

        return redirect()->route('medical-requests.index')->with('success', 'Medical request submitted successfully. PSID: ' . $psid);
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
                'citizen' => $citizen
            ]);
        }
        return response()->json(['found' => false]);
    }
}
