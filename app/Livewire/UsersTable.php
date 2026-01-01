<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $confirmingUserDeletion = false;
    public $userToDeleteId = null;

    protected $paginationTheme = 'bootstrap';

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

    public function toggleStatus($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->status = $user->status === 'active' ? 'inactive' : 'active';
            $user->save();
            session()->flash('message', 'User status updated successfully.');
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingUserDeletion = true;
        $this->userToDeleteId = $id;
    }

    public function deleteUser()
    {
        $user = User::find($this->userToDeleteId);
        if ($user) {
            $user->delete();
            session()->flash('message', 'User deleted successfully.');
        }
        $this->confirmingUserDeletion = false;
        $this->userToDeleteId = null;
    }

    public function render()
    {
        $users = User::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.users-table', [
            'users' => $users,
        ]);
    }
}
