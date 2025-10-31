<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\City;
use App\Models\Province;

class EditCity extends Component
{
    public $cityId;
    public $name;
    public $slug;
    public $province_id;

    public $provinces = [];

    public function mount($id)
    {
        $city = City::findOrFail($id);

        $this->cityId = $city->id;
        $this->name = $city->name;
        $this->slug = $city->slug;
        $this->province_id = $city->province_id;

        $this->provinces = Province::orderBy('name')->get();
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
        'province_id' => 'required|exists:provinces,id',
    ];

    public function updatedName($value)
    {
        // auto-generate slug if empty
        if (empty($this->slug)) {
            $this->slug = \Str::slug($value);
        }
    }

    public function updateCity()
    {
        $this->validate();

        $city = City::findOrFail($this->cityId);
        $city->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'province_id' => $this->province_id,
        ]);

        session()->flash('message', 'City updated successfully!');
        return redirect()->route('cities.index');
    }

    public function render()
    {
        return view('livewire.edit-city');
    }
}
