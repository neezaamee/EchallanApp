<?php

namespace App\Livewire\Cities;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\City;
use App\Models\Province;

class CitiesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 50;
    public $confirmingCityDeletion = null;
    public $deleteId = null;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search', 'perPage'];

    // ✅ Keep pagination in sync with search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // ✅ Sorting logic
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // ✅ Handle delete confirmation
    public function confirmDelete($id)
    {
        $this->authorize('cities:delete');
        $this->confirmingCityDeletion = true; // show modal
        $this->deleteId = $id;
    }

    // ✅ Perform deletion
    public function deleteCity()
    {
        $this->authorize('cities:delete');
        if (!$this->deleteId) {
            return;
        }

        $city = City::find($this->deleteId);
        if ($city) {
            $city->delete();
        }

        $this->reset(['confirmingCityDeletion', 'deleteId']);
        session()->flash('message', 'City deleted successfully!');
        $this->resetPage(); // refresh table after deletion
    }

    // ✅ Query builder with safe grouping
    public function render()
    {
        $user = Auth::user();
        $cityId = null;

        if (!$user->hasRole(['super_admin', 'admin'])) {
            $cityId = $user->staff?->activePosting?->city_id;
        }

        $cities = City::with('province')
            ->when($cityId, function ($q) use ($cityId) {
                $q->where('id', $cityId);
            })
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('province', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.cities.cities-table', compact('cities'));
    }
}
