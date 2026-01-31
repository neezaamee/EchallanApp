<?php

namespace App\Http\Controllers;

use App\Models\StaffPosting;
use Illuminate\Http\Request;

class StaffPostingController extends Controller
{
    public function index()
    {
        return view('app.staff-postings.index');
    }

    public function create()
    {
        return view('app.staff-postings.create');
    }
}
