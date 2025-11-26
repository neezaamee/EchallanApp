<?php

namespace App\Http\Controllers;

class RoleDashboardController extends Controller
{
    public function index(){
        return view('pages.dashboards.index');
    }
    public function superAdmin()
    {
        return view('pages.dashboards.index');
    }

    public function admin()
    {
        return view('pages.dashboards.admin');
    }

    public function doctor()
    {
        return view('pages.dashboards.index');
    }

    public function officer()
    {
        return view('pages.dashboards.officer');
    }

    public function accountant()
    {
        return view('pages.dashboards.accountant');
    }

    public function citizen()
    {
        return view('pages.dashboards.index');
    }
}
