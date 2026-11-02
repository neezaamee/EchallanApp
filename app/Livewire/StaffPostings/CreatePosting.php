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
    public $sector_id;
    public $dumping_point_id;
    public $medical_center_id;

    // Dropdowns
    public $staff_list = [];
    public $provinces = [];
    public $cities = [];
    public $circles = [];
    public $sectors = [];
    public $dumping_points = [];
    public $medical_centers = [];

    // City Admin Restrictions
    public $is_city_admin = false;
    public $admin_province_id = null;
    public $admin_city_id = null;

    public function mount()
    {
        $this->staff_list = Staff::orderBy('first_name')->get();
        $this->start_date = now()->format('Y-m-d');

        $user = auth()->user();
        if (!$user->hasRole('super_admin')) {
            $this->is_city_admin = true;
            $staff = DB::table('staff')->where('user_id', $user->id)->first();
            if ($staff) {
                $activePosting = DB::table('staff_postings')
                    ->where('staff_id', $staff->id)
                    ->where('status', 'active')
                    ->first();

                if ($activePosting) {
                    $this->admin_city_id = $activePosting->city_id;
                    $this->admin_province_id = $activePosting->province_id;

                    if (!$this->admin_city_id) {
                        if ($activePosting->circle_id) {
                            $this->admin_city_id = DB::table('circles')->where('id', $activePosting->circle_id)->value('city_id');
                        } elseif ($activePosting->sector_id) {
                            $circleId = DB::table('sectors')->where('id', $activePosting->sector_id)->value('circle_id');
                            $this->admin_city_id = DB::table('circles')->where('id', $circleId)->value('city_id');
                        } elseif ($activePosting->medical_center_id) {
                            $circleId = DB::table('medical_centers')->where('id', $activePosting->medical_center_id)->value('circle_id');
                            $this->admin_city_id = DB::table('circles')->where('id', $circleId)->value('city_id');
                        } elseif ($activePosting->dumping_point_id) {
                            $circleId = DB::table('dumping_points')->where('id', $activePosting->dumping_point_id)->value('circle_id');
                            $this->admin_city_id = DB::table('circles')->where('id', $circleId)->value('city_id');
                        }
                    }

                    if (!$this->admin_province_id && $this->admin_city_id) {
                        $this->admin_province_id = DB::table('cities')->where('id', $this->admin_city_id)->value('province_id');
                    }
                }
            }

            // Lock values
            $this->province_id = $this->admin_province_id;
            $this->city_id = $this->admin_city_id;

            // Load collections filtered by this city
            if ($this->city_id) {
                $this->cities = City::where('id', $this->city_id)->get();
                $this->circles = Circle::where('city_id', $this->city_id)->orderBy('name')->get();
            }
            if ($this->province_id) {
                $this->provinces = Province::where('id', $this->province_id)->get();
            }
        } else {
            $this->provinces = Province::orderBy('name')->get();
        }

        // Pre-select staff if passed via query param
        if (request()->has('staff_id')) {
            $this->staff_id = request()->get('staff_id');
        }
    }

    public function updatedLocationType($value)
    {
        // Reset all location fields when type changes
        $this->circle_id = null;
        $this->sector_id = null;
        $this->dumping_point_id = null;
        $this->medical_center_id = null;

        if ($this->is_city_admin) {
            $this->province_id = $this->admin_province_id;
            $this->city_id = $this->admin_city_id;
            if ($this->city_id) {
                $this->circles = Circle::where('city_id', $this->city_id)->orderBy('name')->get();
            }
        } else {
            $this->province_id = null;
            $this->city_id = null;
            $this->cities = [];
            $this->circles = [];
        }

        $this->sectors = [];
        $this->dumping_points = [];
        $this->medical_centers = [];
    }

    public function updatedProvinceId($value)
    {
        if ($this->is_city_admin) {
            $this->province_id = $this->admin_province_id;
            return;
        }
        $this->cities = City::where('province_id', $value)->orderBy('name')->get();
        $this->city_id = null;
        $this->circle_id = null;
        $this->circles = [];
    }

    public function updatedCityId($value)
    {
        if ($this->is_city_admin) {
            $this->city_id = $this->admin_city_id;
            return;
        }
        $this->circles = Circle::where('city_id', $value)->orderBy('name')->get();
        $this->circle_id = null;
    }

    public function updatedCircleId($value)
    {
        if ($this->location_type === 'dumping_point') {
            $this->dumping_points = DumpingPoint::where('circle_id', $value)->orderBy('name')->get();
        } elseif ($this->location_type === 'medical_center') {
            $this->medical_centers = MedicalCenter::where('circle_id', $value)->orderBy('name')->get();
        } elseif ($this->location_type === 'sector') {
            $this->sectors = \App\Models\Sector::where('circle_id', $value)->orderBy('name')->get();
        }
    }

    public function save()
    {
        if ($this->is_city_admin) {
            if ($this->location_type === 'province') {
                $this->addError('location_type', 'You are not authorized to post staff to a province.');
                return;
            }
            $this->province_id = $this->admin_province_id;
            $this->city_id = $this->admin_city_id;
        }

        $this->validate([
            'staff_id' => 'required|exists:staff,id',
            'location_type' => 'required|in:province,city,circle,sector,dumping_point,medical_center',
            'start_date' => 'required|date',
        ]);

        // Validate specific location based on type
        $locationRules = [
            'province' => ['province_id' => 'required|exists:provinces,id'],
            'city' => ['city_id' => 'required|exists:cities,id'],
            'circle' => ['circle_id' => 'required|exists:circles,id'],
            'sector' => ['sector_id' => 'required|exists:sectors,id'],
            'dumping_point' => ['dumping_point_id' => 'required|exists:dumping_points,id'],
            'medical_center' => ['medical_center_id' => 'required|exists:medical_centers,id'],
        ];

        $this->validate($locationRules[$this->location_type] ?? []);

        // Additional backend validation for City Admin
        if ($this->is_city_admin) {
            if ($this->province_id != $this->admin_province_id || $this->city_id != $this->admin_city_id) {
                $this->addError('location_type', 'You can only post staff within your assigned city and province.');
                return;
            }
            
            // If circle or lower is selected, verify it belongs to their city
            if (in_array($this->location_type, ['circle', 'sector', 'dumping_point', 'medical_center'])) {
                $circle = Circle::find($this->circle_id);
                if (!$circle || $circle->city_id != $this->admin_city_id) {
                    $this->addError('circle_id', 'Selected circle does not belong to your assigned city.');
                    return;
                }
            }
        }

        DB::transaction(function () {
            // Deactivate all active postings for this staff
            StaffPosting::where('staff_id', $this->staff_id)
                ->where('status', 'active')
                ->update([
                    'status' => 'inactive',
                    'end_date' => \Carbon\Carbon::parse($this->start_date)->subDay()->format('Y-m-d')
                ]);

            // Create new posting
            StaffPosting::create([
                'staff_id' => $this->staff_id,
                'province_id' => $this->location_type === 'province' ? $this->province_id : null,
                'city_id' => $this->location_type === 'city' ? $this->city_id : null,
                'circle_id' => $this->location_type === 'circle' ? $this->circle_id : null,
                'sector_id' => $this->location_type === 'sector' ? $this->sector_id : null,
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
