<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $perPage = $request->input('per_page', 50);
        if (!in_array($perPage, [20, 50, 100])) {
            $perPage = 50;
        }

        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSorts = ['id', 'type', 'subject', 'status', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'created_at';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $query = Feedback::with('user');

        if (!$user->hasRole(['super_admin', 'admin'])) {
            $query->where('user_id', $user->id);
        }

        $feedbacks = $query->orderBy($sortField, $sortDirection)->paginate($perPage);

        return view('app.feedback.index', compact('feedbacks', 'perPage', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.feedback.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:suggestion,complaint,bug_report,feature_request,other',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->route('feedback.index')->with('success', 'Thank you for your feedback!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        $user = Auth::user();

        // Only admins or the feedback owner can view
        if (!$user->hasRole(['super_admin', 'admin']) && $feedback->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('app.feedback.show', compact('feedback'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback)
    {
        // Only admins can update
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
            'admin_notes' => 'nullable|string',
        ]);

        $feedback->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Feedback updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback)
    {
        // Only admins can delete
        if (!Auth::user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $feedback->delete();

        return redirect()->route('feedback.index')->with('success', 'Feedback deleted successfully.');
    }
}
