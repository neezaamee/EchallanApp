<?php

namespace App\Livewire\DumpingPoints;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DumpingPoint;
use App\Models\Circle;
use App\Models\City;

class DumpingPointsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $confirmingDumpingPointDeletion = null;
    public $deleteId = null;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['dumping-point-added' => 'render'];

    /**
     * Reset pagination when search input changes
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Handle sorting logic
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Open delete confirmation modal
     */
    public function confirmDelete($id)
    {
        $this->confirmingDumpingPointDeletion = true;
        $this->deleteId = $id;
    }

    /**
     * Perform deletion
     */
    public function deleteDumpingPoint()
    {
        if (!$this->deleteId) return;

        $dumpingPoint = DumpingPoint::find($this->deleteId);
        if ($dumpingPoint) $dumpingPoint->delete();

        $this->reset(['confirmingDumpingPointDeletion', 'deleteId']);
        session()->flash('message', 'Dumping Point deleted successfully!');
        $this->resetPage();
    }

    /**
     * Render component with filtered, sorted, and paginated data
     */
    public function render()
    {
        $query = DumpingPoint::with(['circle.city'])
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where('name', 'like', $s)
                  ->orWhereHas('circle', function ($q2) use ($s) {
                      $q2->where('name', 'like', $s)
                         ->orWhereHas('city', function ($q3) use ($s) {
                             $q3->where('name', 'like', $s);
                         });
                  });
            });

        $dumpingPoints = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        // Useful lists for filters or dropdowns if needed
        $circles = Circle::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('livewire.dumping-points.dumping-points-table', compact('dumpingPoints', 'circles', 'cities'));
    }
}
