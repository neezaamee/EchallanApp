<?php


namespace App\Livewire\DumpingPoints;


use Livewire\Component;

use App\Models\Province;

use App\Models\City;

use App\Models\Circle;

use App\Models\DumpingPoint;


class AddDumpingPoint extends Component

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

        $this->provinces = Province::orderBy('name')->get(); // Keep collection

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


        DumpingPoint::create([

            'name' => $this->name,

            'location' => $this->location,

            'circle_id' => $this->circle_id,

        ]);


        session()->flash('message', 'Dumping Point added successfully!');

        $this->reset(['name', 'location', 'province_id', 'city_id', 'circle_id', 'cities', 'circles']);

        $this->dispatch('dumping-point-added');

    }


    public function render()

    {

        return view('livewire.dumping-points.add-dumping-point');

    }

}
