<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; // Added Permission model
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        $permissions = Permission::get(); // Fetch all permissions
        $unlinkedStaff = Staff::unlinked()->get();
        return view('admin.users.create', compact('roles', 'permissions', 'unlinkedStaff'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'cnic' => ['required', 'string', 'max:20', 'unique:' . User::class], // Added CNIC validation
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array'],
            'permissions' => ['nullable', 'array'], // Validate permissions
        ];

        // Conditional validation for staff_id
        $isCitizen = in_array('Citizen', $request->roles ?? []);
        if (!$isCitizen) {
            $rules['staff_id'] = ['required', 'exists:staff,id'];
        }

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cnic' => $request->cnic, // Save CNIC
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles($request->roles);

        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        // Link staff if applicable
        if ($request->has('staff_id') && !$isCitizen) {
            $staff = Staff::find($request->staff_id);
            if ($staff) {
                $staff->user_id = $user->id;
                $staff->save();
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $permissions = Permission::get(); // Fetch all permissions
        $userRoles = $user->roles->pluck('name', 'name')->all();
        $userPermissions = $user->getDirectPermissions()->pluck('name', 'name')->all(); // Get direct permissions
        $rolePermissions = $user->getPermissionsViaRoles()->pluck('name')->all(); // Get permissions inherited via roles

        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'userRoles', 'userPermissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$id],
            'cnic' => ['required', 'string', 'max:20', 'unique:users,cnic,'.$id], // Added CNIC validation
            'roles' => ['required', 'array'],
            'permissions' => ['nullable', 'array'], // Validate permissions
        ]);

        $user = User::find($id);
        
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = \Illuminate\Support\Arr::except($input,array('password'));    
        }

        $user->update($input);
        $user->syncRoles($request->roles);
        
        if($request->has('permissions')){
            $user->syncPermissions($request->permissions);
        } else {
             $user->syncPermissions([]); // Clear permissions if none selected
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
