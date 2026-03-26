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

    public function index(Request $request)
    {
        return view('app.staff.index');
    }

    public function create()
    {
        $this->authorize('staff:create');
        return view('app.staff.create');
    }

    public function store(StoreStaffRequest $request)
    {
        $this->authorize('staff:create');
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $staff = Staff::create($data);

        return redirect()->route('staff.index')->with('success','Staff created.');
    }

    public function show(Staff $staff)
    {
        $staff->load(['rank', 'city', 'province', 'user', 'activePosting.province', 'activePosting.city', 'activePosting.circle', 'activePosting.dumpingPoint', 'activePosting.medicalCenter']);
        
        $postings = \App\Models\StaffPosting::where('staff_id', $staff->id)
            ->with(['province', 'city', 'circle', 'dumpingPoint', 'medicalCenter'])
            ->orderBy('id', 'desc')
            ->get();

        return view('app.staff.show', compact('staff', 'postings'));
    }

    public function edit(Staff $staff)
    {
        $this->authorize('staff:edit');
        return view('app.staff.edit', compact('staff'));
    }

    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        $this->authorize('staff:edit');
        $data = $request->validated();
        $staff->update($data);
        return redirect()->route('staff.index')->with('success','Staff updated.');
    }

    public function destroy(Staff $staff)
    {
        $this->authorize('staff:delete');
        $staff->delete();
        return redirect()->route('staff.index')->with('success','Staff deleted.');
    }
}
