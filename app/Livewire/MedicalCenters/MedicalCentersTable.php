<?php

namespace App\Livewire\MedicalCenters;

use Livewire\Component;
use App\Models\MedicalCenter;
use App\Models\Circle;

class MedicalCentersTable extends Component
{
    public $centers;
    public $circles;
    public $circle_id, $name, $location;
    public $center_id;
    public $isEditMode = false;

    public function mount()
    {
        $this->circles = Circle::all();
        $this->loadCenters();
    }

    public function loadCenters()
    {
        $this->centers = MedicalCenter::with('circle')->latest()->get();
    }

    public function resetFields()
    {
        $this->circle_id = '';
        $this->name = '';
        $this->location = '';
        $this->center_id = null;
        $this->isEditMode = false;
    }

    public function save()
    {
        $this->validate([
            'circle_id' => 'required|exists:circles,id',
            'name' => 'required|string|max:255',
        ]);

        MedicalCenter::create([
            'circle_id' => $this->circle_id,
            'name' => $this->name,
            'location' => $this->location,
        ]);

        session()->flash('message', 'Medical center added successfully.');
        $this->resetFields();
        $this->loadCenters();
    }

    public function edit($id)
    {
        $center = MedicalCenter::findOrFail($id);
        $this->center_id = $center->id;
        $this->circle_id = $center->circle_id;
        $this->name = $center->name;
        $this->location = $center->location;
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate([
            'circle_id' => 'required|exists:circles,id',
            'name' => 'required|string|max:255',
        ]);

        $center = MedicalCenter::findOrFail($this->center_id);
        $center->update([
            'circle_id' => $this->circle_id,
            'name' => $this->name,
            'location' => $this->location,
        ]);

        session()->flash('message', 'Medical center updated successfully.');
        $this->resetFields();
        $this->loadCenters();
    }

    public function delete($id)
    {
        MedicalCenter::findOrFail($id)->delete();
        session()->flash('message', 'Medical center deleted.');
        $this->loadCenters();
    }

    public function render()
    {
        return view('livewire.medical-centers.medical-centers-table');
    }
}
