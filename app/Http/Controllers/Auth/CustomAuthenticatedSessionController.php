<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CustomAuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('pages.auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login'); // can be CNIC or email
        $password = $request->input('password');

        // Determine login field: if it contains '@' treat as email otherwise CNIC
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'cnic';

        $credentials = [$field => $login, 'password' => $password];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'login' => [trans('auth.failed')],
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
