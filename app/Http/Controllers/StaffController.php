<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;

class StaffController extends Controller
{
    public function __construct()
    {
        // only allow authenticated users with role admin or super_admin
        //$this->middleware(['auth','role:admin|super_admin']);
    }

    /**
     * Display a paginated listing of staff.
     */
    public function index(Request $request)
    {
        $staff = Staff::with('creator')->orderByDesc('created_at')->paginate(15);
        return view('staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff.
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Store a newly created staff in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|unique:staff,cnic',
            'email' => 'required|email|unique:staff,email|unique:users,email',
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'current_posting' => 'nullable|string|max:255',
        ]);

        $data['created_by'] = auth()->id();

        Staff::create($data);

        return redirect()->route('staff.index')->with('success', 'Staff created.');
    }

    // (Optional) Add edit/update/destroy later as needed.
}
