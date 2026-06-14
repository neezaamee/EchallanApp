<?php

namespace App\Livewire\Challans;

use Livewire\Component;
use App\Models\Challan;
use App\Models\DumpingPoint;
use App\Models\PickUpPoint;
use App\Services\BankService;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $dumping_point_id;
    public $dumping_point_name;
    public $pickUpPoints = [];
    
    public $pick_up_point_id;
    public $violator_name;
    public $violator_cnic;
    public $violator_mobile;
    public $vehicle_type = '';
    public $vehicle_number;
    public $violation = '';
    
    public $fine_amount = 0;

    protected $rules = [
        'dumping_point_id' => 'required|exists:dumping_points,id',
        'pick_up_point_id' => 'required|exists:pick_up_points,id',
        'violator_name' => 'required|string|max:255',
        'violator_cnic' => 'required|string|max:15',
        'violator_mobile' => 'required|string|max:15',
        'vehicle_type' => 'required|in:motorcycle,car,other',
        'vehicle_number' => 'required|string|max:20',
        'violation' => 'required|string',
    ];

    public function mount()
    {
        $officer = Auth::user();
        $staff = $officer->staff;
        
        $posting = $staff ? $staff->activePosting : null;
        $dumpingPoint = $posting ? $posting->dumpingPoint : null;

        if ($dumpingPoint) {
            $this->dumping_point_id = $dumpingPoint->id;
            $this->dumping_point_name = $dumpingPoint->name;
            $this->pickUpPoints = PickUpPoint::where('dumping_point_id', $dumpingPoint->id)
                ->where('is_active', true)
                ->get();
        }
    }

    public function updatedVehicleType()
    {
        $this->calculateFine();
    }

    public function updatedViolation()
    {
        $this->calculateFine();
    }

    public function updatedViolatorCnic()
    {
        $this->dispatch('check-history', cnic: $this->violator_cnic, vehicle_number: $this->vehicle_number);
    }

    public function updatedVehicleNumber()
    {
        $this->dispatch('check-history', cnic: $this->violator_cnic, vehicle_number: $this->vehicle_number);
    }

    private function calculateFine()
    {
        if (empty($this->vehicle_type)) {
            $this->fine_amount = 0;
            return;
        }

        $this->fine_amount = match($this->vehicle_type) {
            'motorcycle' => config('fees.traffic.bike', 200),
            'car'        => config('fees.traffic.car', 2000),
            default      => config('fees.traffic.other', 3000),
        };
    }

    public function getViolationsProperty()
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

    public function save()
    {
        $this->validate();

        $headType = ($this->vehicle_type === 'motorcycle') ? 'TRAFFIC_BIKE' : 'TRAFFIC_CAR';
        
        $amount = match($this->vehicle_type) {
            'motorcycle' => config('fees.traffic.bike', 200),
            'car'        => config('fees.traffic.car', 2000),
            default      => config('fees.traffic.other', 3000),
        };

        $user = Auth::user();
        $staff = $user->staff;
        $cityId = $staff?->activePosting?->city_id;
        
        if (!$cityId && $this->dumping_point_id) {
             $dp = DumpingPoint::find($this->dumping_point_id);
             $cityId = $dp?->circle?->city_id;
        }

        $psid = BankService::generatePsid($headType, (float) $amount, $cityId);

        while(Challan::where('psid', $psid)->exists()){
             $psid = BankService::generatePsid($headType, (float) $amount, $cityId);
        }

        Challan::create([
            'officer_id' => Auth::id(),
            'dumping_point_id' => $this->dumping_point_id,
            'pick_up_point_id' => $this->pick_up_point_id,
            'violator_name' => $this->violator_name,
            'violator_cnic' => $this->violator_cnic,
            'violator_mobile' => $this->violator_mobile,
            'vehicle_type' => $this->vehicle_type,
            'vehicle_number' => $this->vehicle_number,
            'violation_name' => $this->violation,
            'fine_amount' => $amount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'psid' => $psid,
        ]);

        session()->flash('success', 'Challan issued successfully with PSID: ' . $psid);
        return redirect()->route('challans.index');
    }

    public function render()
    {
        return view('livewire.challans.create')->extends('layouts.app')->section('cms-main-content');
    }
}
