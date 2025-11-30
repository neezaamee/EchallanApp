<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\Staff;
use App\Models\Province;
use App\Models\City;
use App\Models\Rank;
use App\Models\Designation;
use Illuminate\Validation\Rule;

class EditStaff extends Component
{
    public $staffId;
    public $first_name;
    public $last_name;
    public $cnic;
    public $email;
    public $phone;
    public $belt_no;
    public $gender;
    public $status;
    
    public $rank_id;

    public $ranks = [];

    public function mount($id)
    {
        $this->staffId = $id;
        $staff = Staff::findOrFail($id);

        $this->first_name = $staff->first_name;
        $this->last_name = $staff->last_name;
        $this->cnic = $staff->cnic;
        $this->email = $staff->email;
        $this->phone = $staff->phone;
        $this->belt_no = $staff->belt_no;
        $this->gender = $staff->gender;
        $this->status = $staff->status;
        $this->rank_id = $staff->rank_id;

        $this->ranks = Rank::orderBy('name')->get();
    }

    public function update()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'cnic' => ['required', 'string', Rule::unique('staff', 'cnic')->ignore($this->staffId)],
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'belt_no' => 'nullable|string|max:50',
            'gender' => 'nullable|in:male,female,other',
            'status' => 'required|in:active,inactive,suspended,retired,transferred_out',
            'rank_id' => 'nullable|exists:ranks,id',
        ]);

        $staff = Staff::findOrFail($this->staffId);
        $staff->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'cnic' => $this->cnic,
            'email' => $this->email,
            'phone' => $this->phone,
            'belt_no' => $this->belt_no,
            'gender' => $this->gender,
            'status' => $this->status,
            'rank_id' => $this->rank_id,
        ]);

        session()->flash('message', 'Staff member updated successfully!');
    }

    public function render()
    {
        return view('livewire.staff.edit-staff');
    }
}
