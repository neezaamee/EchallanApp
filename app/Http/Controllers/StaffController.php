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
        return view('pages.staff.index');
    }

    public function create()
    {
        return view('pages.staff.create');
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
        return view('pages.staff.edit', compact('staff'));
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
