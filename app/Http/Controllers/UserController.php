<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; // Added Permission model
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;
use App\Mail\StaffAccountDetails;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rolesQuery = Role::query();
        if (!auth()->user()->hasRole('super_admin')) {
            $rolesQuery->where('name', '!=', 'super_admin');
        }
        $roles = $rolesQuery->pluck('name', 'name')->all();
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
            'plain_password' => $request->password,
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

        // Send email with credentials
        Mail::to($user->email)->send(new StaffAccountDetails($user, $request->password, 'created'));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!auth()->user()->hasRole('super_admin') && $user->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        if (!auth()->user()->hasRole('super_admin') && $user->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $rolesQuery = Role::query();
        if (!auth()->user()->hasRole('super_admin')) {
            $rolesQuery->where('name', '!=', 'super_admin');
        }
        $roles = $rolesQuery->pluck('name', 'name')->all();
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
        $user = User::find($id);
        if (!auth()->user()->hasRole('super_admin') && $user->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$id],
            'cnic' => ['required', 'string', 'max:20', 'unique:users,cnic,'.$id], // Added CNIC validation
            'roles' => ['required', 'array'],
            'permissions' => ['nullable', 'array'], // Validate permissions
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['plain_password'] = $input['password'];
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

        // Send email with updated credentials
        $passwordToSend = !empty($input['plain_password']) ? $input['plain_password'] : '******** (Unchanged)';
        Mail::to($user->email)->send(new StaffAccountDetails($user, $passwordToSend, 'updated'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User not found.');
        }

        if (!auth()->user()->hasRole('super_admin') && $user->hasRole('super_admin')) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        if (\App\Models\Challan::where('officer_id', $user->id)->exists()) {
            return redirect()->route('users.index')
                ->with('error', 'Cannot delete user because they are associated with existing challans.');
        }

        if (\App\Models\Warning::where('officer_id', $user->id)->exists()) {
            return redirect()->route('users.index')
                ->with('error', 'Cannot delete user because they are associated with existing warnings.');
        }

        try {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Failed to delete user. There might be related records preventing deletion.');
        }
    }
}
