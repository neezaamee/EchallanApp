<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;   // ✅ Added this line
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;

class CustomRegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create()
    {
        return view('pages.auth.register');
    }

    /**
     * Handle registration form submission.
     */
    public function store(Request $request)
    {
        // Validate registration data
        $request->validate([
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|unique:users,cnic',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', 'min:8', 'confirmed', Password::defaults()],
        ]);

        // Create user as violator with email unverified
        $user = User::create([
            'name' => $request->name,
            'cnic' => $request->cnic,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_department_user' => false,
        ]);

        // Fire the Registered event
        event(new Registered($user));

        // Assign default role (for example, "violator")
        $user->assignRole('citizen');
        // Send verification email
        $user->sendEmailVerificationNotification();

        // ✅ Log in the user
        Auth::login($user);

        // Redirect to the email verification notice
        return redirect()
            ->route('verification.notice')
            ->with('registered_email', $user->email);
    }
}
