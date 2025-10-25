<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Staff;
use App\Models\Designation;
use App\Models\Rank;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin|super_admin']);
    }

    public function index(Request $request)
    {
        $query = Staff::with(['designation','rank','city','province','creator']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($wr) use ($q) {
                $wr->where('first_name','like',"%{$q}%")
                   ->orWhere('last_name','like',"%{$q}%")
                   ->orWhere('cnic','like',"%{$q}%")
                   ->orWhere('email','like',"%{$q}%");
            });
        }

        $staff = $query->orderByDesc('id')->paginate(15);
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        $designations = Designation::orderBy('name')->get();
        $ranks = Rank::orderBy('name')->get();
        $provinces = Province::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('staff.create', compact('designations','ranks','provinces','cities'));
    }

    public function store(StoreStaffRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $staff = Staff::create($data);

        return redirect()->route('staff.index')->with('success','Staff created.');
    }

    public function edit(Staff $staff)
    {
        $this->authorize('update', $staff); // optional policy

        $designations = Designation::orderBy('name')->get();
        $ranks = Rank::orderBy('name')->get();
        $provinces = Province::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('staff.edit', compact('staff','designations','ranks','provinces','cities'));
    }

    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        $data = $request->validated();
        $staff->update($data);
        return redirect()->route('staff.index')->with('success','Staff updated.');
    }

    public function destroy(Staff $staff)
    {
        $this->authorize('delete', $staff); // optional
        $staff->delete();
        return redirect()->route('staff.index')->with('success','Staff deleted.');
    }
}
