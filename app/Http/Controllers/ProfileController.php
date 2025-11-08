<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the logged-in user's profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('pages.profile.edit');
    }

    /**
     * Update the user's profile.
     */

}
