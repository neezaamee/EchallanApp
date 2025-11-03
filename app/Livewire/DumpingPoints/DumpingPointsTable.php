<?php

namespace App\Livewire\DumpingPoints;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DumpingPoint;
use App\Models\Circle;

class DumpingPointsTable extends Component
{
    use WithPagination;

    // --- Public properties for search, filters, etc. ---
    public $search = '';              // search by dumping point name
    public $circleFilter = '';        // filter by circle
    public $perPage = 10;             // how many per page

    protected $paginationTheme = 'bootstrap'; // for bootstrap styling

    // --- Reset page when filters change ---
    protected $updatesQueryString = ['search', 'circleFilter'];

    // Reset pagination when search or filters update
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCircleFilter()
    {
        $this->resetPage();
    }

    // --- Delete dumping point ---
    public function delete($id)
    {
        $point = DumpingPoint::find($id);

        if ($point) {
            $point->delete();
            session()->flash('message', 'Dumping point deleted successfully.');
        }
    }

    // --- Main render method ---
    public function render()
    {
        // Query with relationships for filtering
        $query = DumpingPoint::with('circle.city.province')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
            })
            ->when($this->circleFilter, function ($q) {
                $q->where('circle_id', $this->circleFilter);
            })
            ->orderBy('name', 'asc');

        // Paginate results
        $dumpingPoints = $query->paginate($this->perPage);

        // Load circles for filter dropdown
        $circles = Circle::orderBy('name')->get();

        return view('livewire.dumping-points.dumping-points-table', [
            'dumpingPoints' => $dumpingPoints,
            'circles' => $circles,
        ]);
    }
}
