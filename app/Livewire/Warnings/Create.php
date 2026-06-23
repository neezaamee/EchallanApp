<?php

namespace App\Livewire\Warnings;

use App\Models\Warning;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $violator_name;

    public function mount()
    {
        if (!Auth::user()->can('warnings:create')) {
            abort(403, 'Unauthorized action.');
        }
    }
    public $violator_cnic;
    public $violator_mobile;
    public $vehicle_type;
    public $vehicle_number;
    public $violation_name;
    public $location;
    public $remarks;

    protected $rules = [
        'violator_name' => 'required|string|max:255',
        'violator_cnic' => 'required|string|max:15',
        'violator_mobile' => 'nullable|string|max:15',
        'vehicle_type' => 'required|string|max:50',
        'vehicle_number' => 'required|string|max:20',
        'violation_name' => 'required|string|max:255',
        'location' => 'nullable|string',
        'remarks' => 'nullable|string',
    ];

    public function updatedViolatorCnic()
    {
        $this->dispatch('check-history', cnic: $this->violator_cnic, vehicle_number: $this->vehicle_number);
    }

    public function updatedVehicleNumber()
    {
        $this->dispatch('check-history', cnic: $this->violator_cnic, vehicle_number: $this->vehicle_number);
    }

    public function save()
    {
        $this->validate();

        Warning::create([
            'officer_id' => Auth::id(),
            'violator_name' => $this->violator_name,
            'violator_cnic' => $this->violator_cnic,
            'violator_mobile' => $this->violator_mobile,
            'vehicle_type' => $this->vehicle_type,
            'vehicle_number' => $this->vehicle_number,
            'violation_name' => $this->violation_name,
            'location' => $this->location,
            'remarks' => $this->remarks,
        ]);

        session()->flash('message', 'Warning issued successfully.');

        return redirect()->route('warnings.index');
    }

    public function render()
    {
        return view('livewire.warnings.create')->extends('layouts.app')->section('cms-main-content');
    }
}
