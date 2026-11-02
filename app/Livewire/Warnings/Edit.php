<?php

namespace App\Livewire\Warnings;

use App\Models\Warning;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    public $warning_id;
    public $violator_name;
    public $violator_cnic;
    public $violator_mobile;
    public $vehicle_type;
    public $vehicle_number;
    public $violation_name;
    public $location;
    public $remarks;

    public function mount($id)
    {
        if (!Auth::user()->can('warnings:edit')) {
            abort(403, 'Unauthorized action.');
        }

        $warning = Warning::findOrFail($id);

        $this->warning_id = $warning->id;
        $this->violator_name = $warning->violator_name;
        $this->violator_cnic = $warning->violator_cnic;
        $this->violator_mobile = $warning->violator_mobile;
        $this->vehicle_type = $warning->vehicle_type;
        $this->vehicle_number = $warning->vehicle_number;
        $this->violation_name = $warning->violation_name;
        $this->location = $warning->location;
        $this->remarks = $warning->remarks;
    }

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

    public function update()
    {
        if (!Auth::user()->can('warnings:edit')) {
            abort(403, 'Unauthorized action.');
        }

        $this->validate();

        $warning = Warning::findOrFail($this->warning_id);
        $warning->update([
            'violator_name' => $this->violator_name,
            'violator_cnic' => $this->violator_cnic,
            'violator_mobile' => $this->violator_mobile,
            'vehicle_type' => $this->vehicle_type,
            'vehicle_number' => $this->vehicle_number,
            'violation_name' => $this->violation_name,
            'location' => $this->location,
            'remarks' => $this->remarks,
        ]);

        session()->flash('message', 'Warning updated successfully.');

        return redirect()->route('warnings.index');
    }

    public function render()
    {
        return view('livewire.warnings.edit')->extends('layouts.app')->section('cms-main-content');
    }
}
