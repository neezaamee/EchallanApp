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
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole(['super_admin', 'admin'])) {
            // Admins see all feedback
            $feedbacks = Feedback::with('user')->latest()->paginate(15);
        } else {
            // Users see only their own feedback
            $feedbacks = Feedback::where('user_id', $user->id)->latest()->paginate(15);
        }

        return view('pages.feedback.index', compact('feedbacks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.feedback.create');
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

        return view('pages.feedback.show', compact('feedback'));
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
