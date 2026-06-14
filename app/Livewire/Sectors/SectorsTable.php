<?php

namespace App\Livewire\Sectors;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sector;
use App\Models\Circle;

class SectorsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $confirmingSectorDeletion = null;
    public $deleteId = null;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['sector-added' => 'render'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($id)
    {
        $this->authorize('sectors:delete');
        $this->confirmingSectorDeletion = true;
        $this->deleteId = $id;
    }

    public function deleteSector()
    {
        $this->authorize('sectors:delete');
        if (!$this->deleteId) return;

        $sector = Sector::find($this->deleteId);
        if ($sector) $sector->delete();

        $this->reset(['confirmingSectorDeletion', 'deleteId']);
        session()->flash('message', 'Sector deleted successfully!');
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        $cityId = null;

        if (!$user->hasRole(['super_admin', 'admin'])) {
            $cityId = $user->staff?->activePosting?->city_id;
        }

        $query = Sector::with('circle.city')
            ->when($cityId, function ($q) use ($cityId) {
                $q->whereHas('circle', function ($q2) use ($cityId) {
                    $q2->where('city_id', $cityId);
                });
            })
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where('name', 'like', $s)
                  ->orWhere('slug', 'like', $s)
                  ->orWhereHas('circle', function ($q2) use ($s) {
                      $q2->where('name', 'like', $s)
                        ->orWhereHas('city', function ($q3) use ($s) {
                            $q3->where('name', 'like', $s);
                        });
                  });
            });

        $sectors = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);
        $circles = Circle::orderBy('name')->get();

        return view('livewire.sectors.sectors-table', compact('sectors', 'circles'));
    }
}
