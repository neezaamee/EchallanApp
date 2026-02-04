<?php

namespace App\Livewire\DumpingPoints;

use Livewire\Component;
use App\Models\Province;
use App\Models\City;
use App\Models\Circle;
use App\Models\DumpingPoint;

class EditDumpingPoint extends Component
{
    public $dumpingPoint_id;
    public $name;
    public $location;
    public $circle_id;
    public $city_id;
    public $province_id;

    public $provinces = [];
    public $cities = [];
    public $circles = [];

    public function mount($id)
    {
        $dumpingPoint = DumpingPoint::find($id);
        
        if (!$dumpingPoint) {
            abort(404);
        }

        $this->dumpingPoint_id = $dumpingPoint->id;
        $this->name = $dumpingPoint->name;
        $this->location = $dumpingPoint->location;
        $this->circle_id = $dumpingPoint->circle_id;

        // Pre-fill dropdowns
        $circle = $dumpingPoint->circle;
        if ($circle) {
            $this->city_id = $circle->city_id;
            $city = $circle->city;
            if ($city) {
                $this->province_id = $city->province_id;
            }
        }

        // Load lists based on current selection
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
        $this->cities = City::where('province_id', $value)->orderBy('name')->get();
        $this->city_id = null;
        $this->circles = [];
        $this->circle_id = null;
    }

    public function updatedCityId($value)
    {
        $this->circles = Circle::where('city_id', $value)->orderBy('name')->get();
        $this->circle_id = null;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'circle_id' => 'required|exists:circles,id',
        ]);

        $dumpingPoint = DumpingPoint::find($this->dumpingPoint_id);
        $dumpingPoint->update([
            'name' => $this->name,
            'location' => $this->location,
            'circle_id' => $this->circle_id,
        ]);

        session()->flash('message', 'Dumping Point updated successfully!');
        return redirect()->route('dumping-points.index');
    }

    public function render()
    {
        return view('livewire.dumping-points.edit-dumping-point');
    }
}
