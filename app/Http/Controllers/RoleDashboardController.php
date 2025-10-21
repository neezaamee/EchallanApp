<?php

namespace App\Http\Controllers;

class RoleDashboardController extends Controller
{
    public function superAdmin()
    {
        return view('pages.dashboards.super-admin');
    }

    public function admin()
    {
        return view('dashboards.admin');
    }

    public function officer()
    {
        return view('dashboards.officer');
    }

    public function accountant()
    {
        return view('dashboards.accountant');
    }

    public function violator()
    {
        return view('dashboards.violator');
    }
}
