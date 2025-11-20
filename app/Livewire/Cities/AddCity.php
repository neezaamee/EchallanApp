<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\City;
use App\Models\Province;
use Illuminate\Support\Str;

class AddCity extends Component
{
    public $name;
    public $slug;
    public $province_id;
    public $provinces = [];

    public function mount()
    {
        $this->provinces = Province::orderBy('name')->get();
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
        'province_id' => 'required|exists:provinces,id',
    ];

    public function updatedName($value)
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate();

        City::create([
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'province_id' => $this->province_id,
        ]);

        session()->flash('message', 'City added successfully!');
        $this->reset(['name', 'slug', 'province_id']);
    }

    public function render()
    {
        return view('livewire.add-city');
    }
}
