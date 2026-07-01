<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $confirmingUserDeletion = null;
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
        $this->authorize('users:delete');
        $this->confirmingUserDeletion = true;
        $this->deleteId = $id;
    }

    public function deleteUser()
    {
        $this->authorize('users:delete');
        if (!$this->deleteId) {
            return;
        }

        if ($this->deleteId === Auth::id()) {
            session()->flash('error', 'You cannot delete your own account.');
            $this->reset(['confirmingUserDeletion', 'deleteId']);
            return;
        }

        $user = User::find($this->deleteId);
        if ($user) {
            if (\App\Models\Challan::where('officer_id', $user->id)->exists()) {
                session()->flash('error', 'Cannot delete user because they are associated with existing challans.');
                $this->reset(['confirmingUserDeletion', 'deleteId']);
                return;
            }

            if (\App\Models\Warning::where('officer_id', $user->id)->exists()) {
                session()->flash('error', 'Cannot delete user because they are associated with existing warnings.');
                $this->reset(['confirmingUserDeletion', 'deleteId']);
                return;
            }

            try {
                $user->delete();
                session()->flash('message', 'User deleted successfully.');
            } catch (\Exception $e) {
                session()->flash('error', 'Failed to delete user. There might be related records preventing deletion.');
            }
        }

        $this->reset(['confirmingUserDeletion', 'deleteId']);
    }

    public function render()
    {
        $query = User::with('roles')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
            
        if (!Auth::user()->hasRole('super_admin')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'super_admin');
            });
        }

        $users = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.users.users-table', compact('users'));
    }
}
