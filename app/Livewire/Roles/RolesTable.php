<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class RolesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $confirmingRoleDeletion = null;
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
        if (!Auth::user()->can('role-delete')) {
            abort(403, 'Unauthorized');
        }
        
        $role = Role::find($id);
        if ($role && ($role->name === 'super_admin' || $role->name === 'admin')) {
            session()->flash('error', 'Cannot delete system roles.');
            return;
        }

        $this->confirmingRoleDeletion = true;
        $this->deleteId = $id;
    }

    public function deleteRole()
    {
        if (!Auth::user()->can('role-delete') || !$this->deleteId) {
            return;
        }

        $role = Role::find($this->deleteId);
        
        if ($role) {
            if ($role->name === 'super_admin' || $role->name === 'admin') {
                session()->flash('error', 'Cannot delete system roles.');
                $this->reset(['confirmingRoleDeletion', 'deleteId']);
                return;
            }

            $role->delete();
            session()->flash('message', 'Role deleted successfully.');
        }

        $this->reset(['confirmingRoleDeletion', 'deleteId']);
    }

    public function render()
    {
        $roles = Role::where('name', '!=', 'super_admin')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.roles.roles-table', compact('roles'));
    }
}
