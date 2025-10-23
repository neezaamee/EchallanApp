<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;

class CustomRegisteredUserController extends Controller
{
    public function create()
    {
        return view('pages.auth.register');
    }

    public function store(Request $request)
    {
        // Only allow violator self-registration
        $request->validate([
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|unique:users,cnic',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required','confirmed', Password::defaults()],
        ]);

        // create user as violator with email unverified
        $user = User::create([
            'name' => $request->name,
            'cnic' => $request->cnic,
            'username' => $request->cnic, // optional
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_department_user' => false,
        ]);

        // attach violator role
        $role = Role::firstWhere('name', 'violator');
        if ($role) {
            $user->roles()->attach($role->id);
        }

        event(new Registered($user));

        // redirect to the verification notice page
        return redirect()->route('verification.notice');
    }
}
