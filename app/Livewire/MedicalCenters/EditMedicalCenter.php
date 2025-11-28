<?php

namespace App\Livewire\MedicalCenters;

use Livewire\Component;
use App\Models\Province;
use App\Models\City;
use App\Models\Circle;
use App\Models\MedicalCenter;

class EditMedicalCenter extends Component
{
    public $medicalCenterId;
    public $provinces = [];
    public $cities = [];
    public $circles = [];

    public $province_id;
    public $city_id;
    public $circle_id;
    public $name;
    public $location;

    public function mount($id)
    {
        $this->medicalCenterId = $id;
        $mc = MedicalCenter::findOrFail($id);
        
        $this->name = $mc->name;
        $this->location = $mc->location;
        $this->circle_id = $mc->circle_id;

        // Pre-fill dropdowns
        $circle = Circle::find($this->circle_id);
        if ($circle) {
            $this->city_id = $circle->city_id;
            $city = City::find($this->city_id);
            if ($city) {
                $this->province_id = $city->province_id;
            }
        }

        $this->provinces = Province::orderBy('name')->get();
        if ($this->province_id) {
            $this->cities = City::where('province_id', $this->province_id)->orderBy('name')->get();
        }
        if ($this->city_id) {
            $this->circles = Circle::where('city_id', $this->city_id)->orderBy('name')->get();
        }
    }

    public function updatedProvinceId($value)
    {
        $this->cities = City::where('province_id', $value)
            ->orderBy('name')
            ->get();

        $this->city_id = null;
        $this->circles = [];
        $this->circle_id = null;
    }

    public function updatedCityId($value)
    {
        $this->circles = Circle::where('city_id', $value)
            ->orderBy('name')
            ->get();

        $this->circle_id = null;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'circle_id' => 'required|exists:circles,id',
        ]);

        $mc = MedicalCenter::findOrFail($this->medicalCenterId);
        $mc->update([
            'name' => $this->name,
            'location' => $this->location,
            'circle_id' => $this->circle_id,
        ]);

        session()->flash('message', 'Medical Center updated successfully!');
    }

    public function render()
    {
        return view('livewire.medical-centers.edit-medical-center');
    }
}
