<?php

namespace App\Livewire\Permissions;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class PermissionsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $confirmingPermissionDeletion = null;
    public $deleteId = null;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

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
        $this->authorize('permissions:delete');
        $this->confirmingPermissionDeletion = true;
        $this->deleteId = $id;
    }

    public function deletePermission()
    {
        $this->authorize('permissions:delete');
        if (!$this->deleteId) return;

        $permission = Permission::find($this->deleteId);
        if ($permission) {
            $permission->delete();
            session()->flash('message', 'Permission deleted successfully.');
        }

        $this->reset(['confirmingPermissionDeletion', 'deleteId']);
    }

    public function render()
    {
        $permissions = Permission::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.permissions.permissions-table', compact('permissions'));
    }
}
