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
        return view('pages.dashboards.admin');
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
        return view('pages.dashboards.citizen');
    }
}
