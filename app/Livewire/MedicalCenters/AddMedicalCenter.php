<?php

namespace App\Livewire\MedicalCenters;

use Livewire\Component;
use App\Models\Province;
use App\Models\City;
use App\Models\Circle;
use App\Models\MedicalCenter;

class AddMedicalCenter extends Component
{
    public $provinces = [];
    public $cities = [];
    public $circles = [];

    public $province_id;
    public $city_id;
    public $circle_id;
    public $name;
    public $location;

    public function mount()
    {
        $user = auth()->user();
        if (!$user->hasRole(['super_admin', 'admin'])) {
            $cityId = $user->staff?->activePosting?->city_id;
            if ($cityId) {
                $city = City::find($cityId);
                $this->province_id = $city->province_id;
                $this->city_id = $cityId;
                $this->updatedProvinceId($this->province_id);
                $this->updatedCityId($this->city_id);
            }
        }
        $this->provinces = Province::orderBy('name')->get();
    }

    public function updatedProvinceId($value)
    {
        $this->cities = City::where('province_id', $value)
            ->orderBy('name')
            ->get();

        $this->city_id = null;
        $this->circles = [];
    }

    public function updatedCityId($value)
    {
        $this->circles = Circle::where('city_id', $value)
            ->orderBy('name')
            ->get();

        $this->circle_id = null;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'circle_id' => 'required|exists:circles,id',
        ]);

        MedicalCenter::create([
            'name' => $this->name,
            'location' => $this->location,
            'circle_id' => $this->circle_id,
        ]);

        session()->flash('message', 'Medical Center added successfully!');
        $this->reset(['name', 'location', 'province_id', 'city_id', 'circle_id', 'cities', 'circles']);
        $this->dispatch('medical-center-added');
    }

    public function render()
    {
        return view('livewire.medical-centers.add-medical-center');
    }
}
