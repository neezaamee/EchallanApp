<?php

namespace App\Livewire\Admin\Locations;

use Livewire\Component;
use App\Models\MedicalCenter;
use App\Models\Circle;
use Livewire\WithPagination;

class MedicalCenters extends Component
{
    use WithPagination;

    public $name, $circle_id;
    public $isEdit = false, $centerId;

    public function render()
    {
        return view('livewire.admin.locations.medical-centers', [
            'centers' => MedicalCenter::with('circle.city.province')->paginate(10),
            'circles' => Circle::with('city')->get(),
        ]);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'circle_id' => 'required|exists:circles,id',
        ]);

        if ($this->isEdit) {
            MedicalCenter::findOrFail($this->centerId)->update([
                'name' => $this->name,
                'circle_id' => $this->circle_id,
            ]);
        } else {
            MedicalCenter::create([
                'name' => $this->name,
                'circle_id' => $this->circle_id,
            ]);
        }

        $this->resetFields();
        session()->flash('success', 'Medical Center saved successfully.');
    }

    public function edit($id)
    {
        $center = MedicalCenter::findOrFail($id);
        $this->name = $center->name;
        $this->circle_id = $center->circle_id;
        $this->centerId = $id;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        MedicalCenter::findOrFail($id)->delete();
    }

    public function resetFields()
    {
        $this->name = '';
        $this->circle_id = '';
        $this->isEdit = false;
        $this->centerId = null;
    }
}
