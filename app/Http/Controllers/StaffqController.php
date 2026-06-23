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
            'activePosting.sector',
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
        $sectors = \App\Models\Sector::all();
        $dumpingPoints = DumpingPoint::all();
        $medicalCenters = MedicalCenter::all();

        // Role mapping for dropdown (dynamic Spatie roles excluding non-staff)
        $roles = \Spatie\Permission\Models\Role::whereNotIn('name', ['super_admin', 'citizen', 'admin'])
            ->pluck('name', 'id')
            ->toArray();

        return view('staff.create', compact('provinces','cities','circles','sectors','dumpingPoints','medicalCenters','roles'));
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
            'sector_id' => $request->sector_id,
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
        $sectors = \App\Models\Sector::all();
        $dumpingPoints = DumpingPoint::all();
        $medicalCenters = MedicalCenter::all();
        $roles = \Spatie\Permission\Models\Role::whereNotIn('name', ['super_admin', 'citizen', 'admin'])
            ->pluck('name', 'id')
            ->toArray();

        $currentPosting = $staff->currentPosting; // may be null

        return view('staff.edit', compact('staff','currentPosting','provinces','cities','circles','sectors','dumpingPoints','medicalCenters','roles'));
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
            $currentPosting->update($request->only(['province_id','city_id','circle_id','sector_id','dumping_point_id','medical_center_id']));
        }

        return redirect()->route('staff.index')->with('success','Staff updated successfully');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('success','Staff deleted successfully');
    }
}
