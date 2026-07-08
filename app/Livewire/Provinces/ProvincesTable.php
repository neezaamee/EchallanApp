<?php

namespace App\Livewire\Provinces;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Province;

class ProvincesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 50;

    public $confirmingProvinceDeletion = null;
    public $deleteId = null;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search', 'perPage'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
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
        $this->authorize('provinces:delete');

        $this->confirmingProvinceDeletion = $id;
        $this->deleteId = $id;
    }

    public function deleteProvince()
    {
        $this->authorize('provinces:delete');
        if (!$this->deleteId) return;

        Province::findOrFail($this->deleteId)->delete();

        $this->confirmingProvinceDeletion = null;
        $this->deleteId = null;

        session()->flash('message', 'Province deleted successfully!');
        $this->resetPage();
    }

    public function render()
    {
        $provinces = Province::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('slug', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.provinces.provinces-table', compact('provinces'));
    }
}
