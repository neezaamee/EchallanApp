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
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $perPage = (int) $request->input('per_page', 50);
        if (!in_array($perPage, [20, 50, 100])) {
            $perPage = 50;
        }

        $sortBy = $request->input('sort', 'created_at');
        $sortDir = $request->input('direction', 'desc');
        
        $allowedSorts = ['id', 'psid', 'vehicle_number', 'violator_name', 'violation_name', 'fine_amount', 'status', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $search = $request->input('search');
        
        $query = Challan::query();

        // Roles check
        if ($user->hasRole(['super_admin', 'admin'])) {
            // Super Admin & Admin see ALL challans
        } elseif ($user->hasRole('citizen')) {
            // Citizens see only their own challans based on CNIC
            $query->where('violator_cnic', $user->cnic);
        } else {
            // Officers see only their own generated challans
            $query->where('officer_id', $user->id);
        }

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('psid', 'LIKE', "%{$search}%")
                  ->orWhere('vehicle_number', 'LIKE', "%{$search}%")
                  ->orWhere('violator_name', 'LIKE', "%{$search}%")
                  ->orWhere('violator_cnic', 'LIKE', "%{$search}%")
                  ->orWhere('violation_name', 'LIKE', "%{$search}%");
            });
        }

        $challans = $query->orderBy($sortBy, $sortDir)->paginate($perPage);
            
        return view('app.challans.index', compact('challans'));
    }

    // create() and store() have been moved to App\Livewire\Challans\Create
    
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

        if (!$challan->isUnpaid()) {
            return back()->with('error', 'Cannot delete a paid or actioned challan.');
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
