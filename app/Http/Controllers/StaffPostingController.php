<?php

namespace App\Http\Controllers;

use App\Models\StaffPosting;
use Illuminate\Http\Request;

class StaffPostingController extends Controller
{
    public function index()
    {
        return view('pages.staff-postings.index');
    }

    public function create()
    {
        return view('pages.staff-postings.create');
    }
}
