<?php

namespace App\Http\Controllers;

use App\Models\Changelog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangelogController extends Controller
{
    /**
     * Display a listing of changelogs (Admin view)
     */
    public function index()
    {
        // Only admins can access
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $changelogs = Changelog::ordered()->paginate(20);
        return view('pages.changelog.admin.index', compact('changelogs'));
    }

    /**
     * Show the form for creating a new changelog entry
     */
    public function create()
    {
        // Only admins can access
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.changelog.admin.create');
    }

    /**
     * Store a newly created changelog entry
     */
    public function store(Request $request)
    {
        // Only admins can access
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'version' => 'required|string|max:255',
            'release_date' => 'required|date',
            'type' => 'required|in:added,changed,fixed,removed,security,deprecated',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_published' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        Changelog::create([
            'version' => $request->version,
            'release_date' => $request->release_date,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'is_published' => $request->has('is_published'),
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('changelog.index')->with('success', 'Changelog entry created successfully.');
    }

    /**
     * Show the form for editing the specified changelog entry
     */
    public function edit(Changelog $changelog)
    {
        // Only admins can access
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.changelog.admin.edit', compact('changelog'));
    }

    /**
     * Update the specified changelog entry
     */
    public function update(Request $request, Changelog $changelog)
    {
        // Only admins can access
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'version' => 'required|string|max:255',
            'release_date' => 'required|date',
            'type' => 'required|in:added,changed,fixed,removed,security,deprecated',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_published' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $changelog->update([
            'version' => $request->version,
            'release_date' => $request->release_date,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'is_published' => $request->has('is_published'),
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('changelog.index')->with('success', 'Changelog entry updated successfully.');
    }

    /**
     * Remove the specified changelog entry
     */
    public function destroy(Changelog $changelog)
    {
        // Only admins can access
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $changelog->delete();

        return redirect()->route('changelog.index')->with('success', 'Changelog entry deleted successfully.');
    }

    /**
     * Display public changelog view
     */
    public function publicView()
    {
        // Get all published changelogs grouped by version
        $changelogs = Changelog::published()
            ->ordered()
            ->get()
            ->groupBy('version');

        return view('pages.changelog.public', compact('changelogs'));
    }
}
