<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\StaffPosting;
use App\Models\User;
use App\Models\Province;
use App\Models\City;
use App\Models\Circle;
use App\Models\DumpingPoint;
use App\Models\MedicalCenter;

class StaffqController extends Controller
{
    public function index()
    {
        // Load all staff with current posting and related entities
        $staff = Staff::with([
            'activePosting.city',
            'activePosting.circle',
            'activePosting.dumpingPoint',
            'activePosting.medicalCenter',
            'user',
            'rank'
        ])->get();

        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        // Needed for dropdowns
        $provinces = Province::all();
        $cities = City::all();
        $circles = Circle::all();
        $dumpingPoints = DumpingPoint::all();
        $medicalCenters = MedicalCenter::all();

        // Role mapping for dropdown
        $roles = [
            3  => 'incharge',
            4  => 'duty_officer',
            5  => 'deo',
            6  => 'challan_officer',
            7  => 'circle_officer',
            8  => 'reader',
            9  => 'accountant',
            10 => 'doctor',
            11 => 'cto',
            12 => 'dig',
            13 => 'addl_ig',
            14 => 'ig',
        ];

        return view('staff.create', compact('provinces','cities','circles','dumpingPoints','medicalCenters','roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'email'=>'required|email|unique:staff,email',
            'phone'=>'required|string',
            'role_id'=>'required|integer',
            'rank_id'=>'required|integer',
        ]);

        // Create Staff
        $staff = Staff::create($request->only(['first_name','last_name','email','phone','role_id','rank_id','status']));

        // Create initial posting
        StaffPosting::create([
            'staff_id' => $staff->id,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'circle_id' => $request->circle_id,
            'dumping_point_id' => $request->dumping_point_id,
            'medical_center_id' => $request->medical_center_id,
            'status' => 'active',
            'start_date' => now(),
        ]);

        return redirect()->route('staff.index')->with('success','Staff added successfully');
    }

    public function edit(Staff $staff)
    {
        $provinces = Province::all();
        $cities = City::all();
        $circles = Circle::all();
        $dumpingPoints = DumpingPoint::all();
        $medicalCenters = MedicalCenter::all();
        $roles = [
            3  => 'incharge',
            4  => 'duty_officer',
            5  => 'deo',
            6  => 'challan_officer',
            7  => 'circle_officer',
            8  => 'reader',
            9  => 'accountant',
            10 => 'doctor',
            11 => 'cto',
            12 => 'dig',
            13 => 'addl_ig',
            14 => 'ig',
        ];

        $currentPosting = $staff->currentPosting; // may be null

        return view('staff.edit', compact('staff','currentPosting','provinces','cities','circles','dumpingPoints','medicalCenters','roles'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'email'=>'required|email|unique:staff,email,'.$staff->id,
            'phone'=>'required|string',
            'role_id'=>'required|integer',
            'rank_id'=>'required|integer',
        ]);

        $staff->update($request->only(['first_name','last_name','email','phone','role_id','rank_id','status']));

        // Update current posting safely
        $currentPosting = $staff->currentPosting;
        if($currentPosting) {
            $currentPosting->update($request->only(['province_id','city_id','circle_id','dumping_point_id','medical_center_id']));
        }

        return redirect()->route('staff.index')->with('success','Staff updated successfully');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('success','Staff deleted successfully');
    }
}
