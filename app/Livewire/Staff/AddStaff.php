<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\Staff;
use App\Models\Province;
use App\Models\City;
use App\Models\Rank;
use App\Models\Designation;

class AddStaff extends Component
{
    public $first_name;
    public $last_name;
    public $cnic;
    public $email;
    public $phone;
    public $belt_no;
    public $gender;
    public $status = 'active';
    
    public $rank_id;

    public $ranks = [];

    public function mount()
    {
        $this->ranks = Rank::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'cnic' => 'required|string|unique:staff,cnic',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'belt_no' => 'nullable|string|max:50',
            'gender' => 'nullable|in:male,female,other',
            'status' => 'required|in:active,inactive,suspended,retired,transferred_out',
            'rank_id' => 'nullable|exists:ranks,id',
        ]);

        Staff::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'cnic' => $this->cnic,
            'email' => $this->email,
            'phone' => $this->phone,
            'belt_no' => $this->belt_no,
            'gender' => $this->gender,
            'status' => $this->status,
            'rank_id' => $this->rank_id,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Staff member added successfully!');
        $this->reset(['first_name', 'last_name', 'cnic', 'email', 'phone', 'belt_no', 'gender', 'rank_id']);
        $this->status = 'active';
    }

    public function render()
    {
        return view('livewire.staff.add-staff');
    }
}
