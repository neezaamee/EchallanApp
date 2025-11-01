<?php

namespace App\Livewire\Circles;

use Livewire\Component;
use App\Models\Circle;
use App\Models\City;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AddCircle extends Component
{
    public $name;
    public $slug;
    public $city_id;
    public $cities = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
        'city_id' => 'required|exists:cities,id',
    ];

    public function mount()
    {
        $this->cities = City::orderBy('name')->get();
    }

    public function updatedName($value)
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('circles')->where(fn ($query) => $query->where('city_id', $this->city_id)),
            ],
            'city_id' => 'required|exists:cities,id',

        ],
        [
            'name.unique' => 'A circle with this name already exists in the selected city.',
        ]
    );

        Circle::create([
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'city_id' => $this->city_id,
        ]);

        session()->flash('message', 'Circle added successfully!');
        $this->reset(['name', 'slug', 'city_id']);
        $this->dispatch('circle-added'); // in case table component wants to refresh via event
    }

    public function render()
    {
        return view('livewire.circles.add-circle');
    }
}
