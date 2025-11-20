<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Main dashboard for managing all locations.
     */
    public function index()
    {
        return view('admin.locations.index');
    }

    /**
     * Manage provinces.
     */
    public function provinces()
    {
        return view('admin.locations.provinces');
    }

    /**
     * Manage cities.
     */
    public function cities()
    {
        return view('admin.locations.cities');
    }

    /**
     * Manage circles.
     */
    public function circles()
    {
        return view('admin.locations.circles');
    }

    /**
     * Manage medical centers.
     */
    public function medicalCenters()
    {
        return view('admin.locations.medical-centers');
    }
}
