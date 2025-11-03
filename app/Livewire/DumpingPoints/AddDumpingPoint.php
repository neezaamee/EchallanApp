<?php

namespace App\Livewire\DumpingPoints;

use Livewire\Component;
use App\Models\DumpingPoint;
use App\Models\Circle;
use Illuminate\Validation\Rule;

class AddDumpingPoint extends Component
{
    // Public properties (form fields)
    public $name;
    public $location;
    public $circle_id;
    public $circles = [];

    public $successMessage;

    /**
     * Mount the component.
     * Load all circles for the dropdown (used when adding dumping points).
     */
    public function mount()
    {
        // Get all circles (could filter later if needed)
        $this->circles = Circle::orderBy('name')->get();
    }

    /**
     * Define validation rules.
     */
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Prevent duplicate dumping points within the same circle
                Rule::unique('dumping_points')->where(function ($query) {
                    return $query->where('circle_id', $this->circle_id);
                }),
            ],
            'location' => 'nullable|string|max:500',
            'circle_id' => 'required|exists:circles,id',
        ];
    }

    /**
     * Save dumping point to database.
     */
    public function save()
    {
        $this->validate();

        DumpingPoint::create([
            'name' => $this->name,
            'location' => $this->location,
            'circle_id' => $this->circle_id,
        ]);

        // Reset the form after saving
        $this->reset(['name', 'location', 'circle_id']);

        // Show success message
        $this->successMessage = 'Dumping Point added successfully!';

        // Optionally, emit event to refresh table component
        $this->dispatch('dumpingPointAdded');
    }

    /**
     * Render the view.
     */
    public function render()
    {
        return view('livewire.dumping-points.add-dumping-point');
    }
}
