<?php

namespace App\Livewire\StaffPostings;

use Livewire\Component;
use App\Models\Staff;
use App\Models\StaffPosting;
use App\Models\Province;
use App\Models\City;
use App\Models\Circle;
use App\Models\DumpingPoint;
use App\Models\MedicalCenter;
use Illuminate\Support\Facades\DB;

class CreatePosting extends Component
{
    public $staff_id;
    public $location_type = '';
    public $start_date;

    // Location IDs
    public $province_id;
    public $city_id;
    public $circle_id;
    public $dumping_point_id;
    public $medical_center_id;

    // Dropdowns
    public $staff_list = [];
    public $provinces = [];
    public $cities = [];
    public $circles = [];
    public $dumping_points = [];
    public $medical_centers = [];

    public function mount()
    {
        $this->staff_list = Staff::orderBy('first_name')->get();
        $this->provinces = Province::orderBy('name')->get();
        $this->start_date = now()->format('Y-m-d');

        // Pre-select staff if passed via query param
        if (request()->has('staff_id')) {
            $this->staff_id = request()->get('staff_id');
        }
    }

    public function updatedLocationType($value)
    {
        // Reset all location fields when type changes
        $this->province_id = null;
        $this->city_id = null;
        $this->circle_id = null;
        $this->dumping_point_id = null;
        $this->medical_center_id = null;
        $this->cities = [];
        $this->circles = [];
        $this->dumping_points = [];
        $this->medical_centers = [];
    }

    public function updatedProvinceId($value)
    {
        $this->cities = City::where('province_id', $value)->orderBy('name')->get();
        $this->city_id = null;
        $this->circle_id = null;
        $this->circles = [];
    }

    public function updatedCityId($value)
    {
        $this->circles = Circle::where('city_id', $value)->orderBy('name')->get();
        $this->circle_id = null;
    }

    public function updatedCircleId($value)
    {
        if ($this->location_type === 'dumping_point') {
            $this->dumping_points = DumpingPoint::where('circle_id', $value)->orderBy('name')->get();
        } elseif ($this->location_type === 'medical_center') {
            $this->medical_centers = MedicalCenter::where('circle_id', $value)->orderBy('name')->get();
        }
    }

    public function save()
    {
        $this->validate([
            'staff_id' => 'required|exists:staff,id',
            'location_type' => 'required|in:province,city,circle,dumping_point,medical_center',
            'start_date' => 'required|date',
        ]);

        // Validate specific location based on type
        $locationRules = [
            'province' => ['province_id' => 'required|exists:provinces,id'],
            'city' => ['city_id' => 'required|exists:cities,id'],
            'circle' => ['circle_id' => 'required|exists:circles,id'],
            'dumping_point' => ['dumping_point_id' => 'required|exists:dumping_points,id'],
            'medical_center' => ['medical_center_id' => 'required|exists:medical_centers,id'],
        ];

        $this->validate($locationRules[$this->location_type] ?? []);

        DB::transaction(function () {
            // Deactivate all active postings for this staff
            StaffPosting::where('staff_id', $this->staff_id)
                ->where('status', 'active')
                ->update([
                    'status' => 'inactive',
                    'end_date' => now()->subDay()->format('Y-m-d')
                ]);

            // Create new posting
            StaffPosting::create([
                'staff_id' => $this->staff_id,
                'province_id' => $this->location_type === 'province' ? $this->province_id : null,
                'city_id' => $this->location_type === 'city' ? $this->city_id : null,
                'circle_id' => $this->location_type === 'circle' ? $this->circle_id : null,
                'dumping_point_id' => $this->location_type === 'dumping_point' ? $this->dumping_point_id : null,
                'medical_center_id' => $this->location_type === 'medical_center' ? $this->medical_center_id : null,
                'start_date' => $this->start_date,
                'status' => 'active',
            ]);
        });

        session()->flash('message', 'Staff posted/transferred successfully!');
        return redirect()->route('staff-postings.index');
    }

    public function render()
    {
        return view('livewire.staff-postings.create-posting');
    }
}
