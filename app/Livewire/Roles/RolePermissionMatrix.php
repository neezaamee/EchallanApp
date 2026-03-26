<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class RolePermissionMatrix extends Component
{
    public $roles;
    public $permissions;
    public $groupedPermissions;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->roles = Role::with('permissions')->get();
        $this->permissions = Permission::get();
        $this->groupedPermissions = $this->groupPermissions($this->permissions);
    }

    public function togglePermission($roleId, $permissionName)
    {
        if (!Auth::user()->hasRole('super_admin')) {
            session()->flash('error', 'Unauthorized access.');
            return;
        }

        $role = Role::find($roleId);
        if (!$role) return;

        // Prevent modification of super_admin role if you want to be safe, 
        // but usually super_admin should have everything.
        // if ($role->name === 'super_admin') {
        //     session()->flash('error', 'Cannot modify Super Admin permissions.');
        //     return;
        // }

        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
        } else {
            $role->givePermissionTo($permissionName);
        }

        $this->loadData();
        session()->flash('message', "Permission updated for " . $role->name);
    }

    private function groupPermissions($permissions)
    {
        $groups = [];
        foreach ($permissions as $permission) {
            $parts = explode(':', $permission->name); // Assuming name is like 'users:view'
            if (count($parts) > 1) {
                $groupName = $parts[0];
            } else {
                // Fallback for names with spaces or other formats
                $parts = explode(' ', $permission->name);
                $groupName = count($parts) > 1 ? end($parts) : 'Other';
            }
            $groups[ucfirst($groupName)][] = $permission;
        }
        return $groups;
    }

    public function render()
    {
        return view('livewire.roles.role-permission-matrix');
    }
}
