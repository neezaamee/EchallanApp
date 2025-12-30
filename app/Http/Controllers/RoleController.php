<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::where('name', '!=', 'super_admin')->orderBy('id','DESC')->paginate(10);
        return view('admin.roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::get();
        $groupedPermissions = $this->groupPermissions($permissions);
        return view('admin.roles.create', compact('groupedPermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
        ]);
    
        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        $role->syncPermissions($request->permission);
    
        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        $rolePermissions = $role->permissions;
    
        return view('admin.roles.show',compact('role','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::get();
        $groupedPermissions = $this->groupPermissions($permissions);
        $rolePermissions = $role->permissions->pluck('id')->toArray();
    
        return view('admin.roles.edit',compact('role','groupedPermissions','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$id,
            'permission' => 'required|array',
        ]);
    
        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->save();
    
        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if ($role->name === 'super_admin' || $role->name === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete system roles.');
        }
        $role->delete();
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }

    private function groupPermissions($permissions)
    {
        $groups = [];
        foreach ($permissions as $permission) {
            $parts = explode(' ', $permission->name);
            $groupName = count($parts) > 1 ? end($parts) : 'Other';
            $groups[ucfirst($groupName)][] = $permission;
        }
        return $groups;
    }
}
