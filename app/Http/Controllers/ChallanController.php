<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Challan;
use App\Models\DumpingPoint;
use App\Models\PickUpPoint;
use App\Services\BankService;

class ChallanController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Super Admin & Admin see ALL challans
        if ($user->hasRole(['super_admin', 'admin'])) {
            $challans = Challan::latest()->paginate(10);
        } elseif ($user->hasRole('citizen')) {
            // Citizens see only their own challans based on CNIC
            $challans = Challan::where('violator_cnic', $user->cnic)
                ->latest()
                ->paginate(10);
        } else {
            // Officers see only their own generated challans
            $challans = Challan::where('officer_id', $user->id)
                ->latest()
                ->paginate(10);
        }
            
        return view('app.challans.index', compact('challans'));
    }

    public function create()
    {
        /** @var User $officer */
        $officer = Auth::user();
        $staff = $officer->staff;
        
        $posting = $staff ? $staff->activePosting : null;
        $dumpingPoint = $posting ? $posting->dumpingPoint : null;
        
        if (!$dumpingPoint) {
           // Fallback or error if officer not assigned to dumping point
           // For now, let's fetch all dumping points if none is assigned (super admin testing)
           // Or strictly require it. I'll return a view with error/empty message if null.
           return view('app.challans.create', [
               'dumpingPoint' => null,
               'pickUpPoints' => [],
               'violations' => $this->getViolations()
           ])->with('error', 'You are not assigned to a Dumping Point.');
        }

        $pickUpPoints = PickUpPoint::where('dumping_point_id', $dumpingPoint->id)
            ->where('is_active', true)
            ->get();

        return view('app.challans.create', [
            'dumpingPoint' => $dumpingPoint,
            'pickUpPoints' => $pickUpPoints,
            'violations' => $this->getViolations()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dumping_point_id' => 'required|exists:dumping_points,id',
            'pick_up_point_id' => 'required|exists:pick_up_points,id',
            'violator_name' => 'required|string',
            'violator_cnic' => 'required|string',
            'violator_mobile' => 'required|string',
            'vehicle_type' => 'required|in:motorcycle,car,other',
            'vehicle_number' => 'required|string',
            'violation' => 'required|string', // "Name|Amount"
        ]);

        list($violationName, $amount) = explode('|', $request->violation);

        // Determine Payment Head and Amount based on Vehicle Type
        $vehicleType = $request->vehicle_type;
        $headType = ($vehicleType === 'motorcycle') ? 'TRAFFIC_BIKE' : 'TRAFFIC_CAR';
        
        // Use central config 
        $amount = match($vehicleType) {
            'motorcycle' => config('fees.traffic.bike', 200),
            'car'        => config('fees.traffic.car', 2000),
            default      => config('fees.traffic.other', 3000),
        };

        // Get City ID for PSID generation
        /** @var User $user */
        $user = Auth::user();
        $staff = $user->staff;
        $cityId = $staff?->activePosting?->city_id;
        
        if (!$cityId && $request->dumping_point_id) {
             $dp = DumpingPoint::find($request->dumping_point_id);
             $cityId = $dp?->circle?->city_id;
        }

        // Generate PSID via Bank Service
        $psid = BankService::generatePsid($headType, (float) $amount, $cityId);

        // Ensure uniqueness
        while(Challan::where('psid', $psid)->exists()){
             $psid = BankService::generatePsid($headType, (float) $amount, $cityId);
        }

        Challan::create([
            'officer_id' => Auth::id(),
            'dumping_point_id' => $request->dumping_point_id,
            'pick_up_point_id' => $request->pick_up_point_id,
            'violator_name' => $request->violator_name,
            'violator_cnic' => $request->violator_cnic,
            'violator_mobile' => $request->violator_mobile,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_number' => $request->vehicle_number,
            'violation_name' => $violationName,
            'fine_amount' => $amount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'psid' => $psid,
        ]);

        return redirect()->route('challans.index')->with('success', 'Challan issued successfully with PSID: ' . $psid);
    }
    
    public function show(Challan $challan)
    {
        return view('app.challans.show', compact('challan'));
    }

    public function validatePayment(Request $request, Challan $challan)
    {
        $request->validate([
            'transaction_id' => 'required|string'
        ]);

        $challan->update([
            'status' => 'paid', // Or 'ready_for_release'
            'payment_status' => 'paid',
            'transaction_id' => $request->transaction_id
        ]);

        // Create Payment Record for the payment table
        \App\Models\Payment::create([
            'challan_id' => $challan->id,
            'psid' => $challan->psid,
            'amount' => $challan->fine_amount,
            'transaction_id' => $request->transaction_id,
            'payment_method' => 'manual',
            'status' => 'success',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Payment verified.');
    }

    public function checkStatusForm()
    {
        return view('app.impound.check-status');
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'psid' => 'required|string'
        ]);

        $challan = Challan::where('psid', $request->psid)->first();

        if (!$challan) {
            return back()->with('error', 'PSID not found.')->withInput();
        }

        return view('app.impound.status-result', compact('challan'));
    }

    public function releaseForm(Challan $challan)
    {
        if ($challan->payment_status !== 'paid') {
            return redirect()->route('dashboard')->with('error', 'Cannot release vehicle. Payment not cleared.');
        }

        if ($challan->released_at) {
            $releasedAt = $challan->released_at instanceof \Carbon\Carbon 
                ? $challan->released_at->format('d M, Y') 
                : $challan->released_at;
            return redirect()->route('dashboard')->with('error', 'Vehicle already released on ' . $releasedAt);
        }

        return view('app.impound.release', compact('challan'));
    }

    public function release(Request $request, Challan $challan)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'receiver_cnic' => 'required|string|digits:13',
            'receiver_father_name' => 'required|string|max:255',
        ]);

        if ($challan->payment_status !== 'paid') {
            return back()->with('error', 'Payment must be verified before release.');
        }

        /** @var User $user */
        $user = Auth::user();

        $challan->update([
            'status' => 'released',
            'released_at' => now(),
            'released_by_staff_id' => $user->staff?->id,
            'receiver_name' => $request->receiver_name,
            'receiver_cnic' => $request->receiver_cnic,
            'receiver_father_name' => $request->receiver_father_name,
        ]);

        return redirect()->route('dashboard')->with('success', 'Vehicle released successfully to ' . $request->receiver_name);
    }

    public function destroy(Challan $challan)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole('super_admin')) {
             abort(403);
        }
        
        $challan->delete(); 
        
        return back()->with('success', 'Challan deleted successfully.');
    }

    private function getViolations()
    {
        $bikeFee = config('fees.traffic.bike', 200);
        $carFee = config('fees.traffic.car', 2000);

        return [
            ['name' => 'Wrong Parking', 'fine_car' => $carFee, 'fine_bike' => $bikeFee],
            ['name' => 'No Parking Zone', 'fine_car' => $carFee, 'fine_bike' => $bikeFee],
            ['name' => 'Obstruction of Traffic', 'fine_car' => $carFee, 'fine_bike' => $bikeFee],
            ['name' => 'Double Parking', 'fine_car' => $carFee, 'fine_bike' => $bikeFee],
        ];
    }
}
