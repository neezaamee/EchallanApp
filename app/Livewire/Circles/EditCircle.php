<?php

namespace App\Livewire\Circles;

use Livewire\Component;
use App\Models\Province;
use App\Models\Circle;
use App\Models\City;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EditCircle extends Component
{
    public $circle_id;
    public $name;
    public $slug;
    public $city_id;
    public $cities = [];

    protected $listeners = ['setCity' => 'setCityFromJs'];

    public function mount($id)
    {
        // Load the circle with its city + province
        $circle = Circle::with('city.province')->findOrFail($id);

        $this->circle_id = $circle->id;
        $this->name = $circle->name;
        $this->slug = $circle->slug;
        $this->city_id = $circle->city_id;

        // FIX: eager load province for each city
        $this->cities = City::with('province')->get();
    }

    public function setCityFromJs($data = []) // <--- Add default value here
    {
        // safe access using null coalescing operator
        $this->city_id = $data['cityId'] ?? $this->city_id;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:circles,slug,' . $this->circle_id,
            'city_id' => 'required|exists:cities,id',
        ]);

        $circle = Circle::findOrFail($this->circle_id);
        $circle->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'city_id' => $this->city_id,

        ]);

        session()->flash('success', 'Circle updated successfully.');
        return redirect()->route('circles.index');
    }

    public function render()
    {
        return view('livewire.circles.edit-circle');
    }
}
