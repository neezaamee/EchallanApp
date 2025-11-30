@extends('layout.cms-layout')
@section('page-title', 'Feedback - ')

@section('cms-main-content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Feedback</h4>
            <a href="{{ route('feedback.create') }}" class="btn btn-primary">
                <span class="fas fa-plus me-1"></span>Submit Feedback
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                @role(['super_admin', 'admin'])
                                    <th>User</th>
                                @endrole
                                <th>Type</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feedbacks as $feedback)
                                <tr>
                                    <td>{{ $feedback->id }}</td>
                                    @role(['super_admin', 'admin'])
                                        <td>
                                            <div>{{ $feedback->user->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $feedback->user->email ?? '' }}</small>
                                        </td>
                                    @endrole
                                    <td>
                                        <span
                                            class="badge bg-info">{{ ucwords(str_replace('_', ' ', $feedback->type)) }}</span>
                                    </td>
                                    <td>{{ Str::limit($feedback->subject, 50) }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $feedback->status === 'resolved' ? 'bg-success' : ($feedback->status === 'reviewed' ? 'bg-warning' : 'bg-secondary') }}">
                                            {{ ucfirst($feedback->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('feedback.show', $feedback->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <span class="fas fa-eye"></span>
                                        </a>
                                        @role(['super_admin', 'admin'])
                                            <form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure?')">
                                                    <span class="fas fa-trash"></span>
                                                </button>
                                            </form>
                                        @endrole
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->hasRole(['super_admin', 'admin'])? 7: 6 }}"
                                        class="text-center py-4">
                                        No feedback found. <a href="{{ route('feedback.create') }}">Submit your first
                                            feedback</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($feedbacks->hasPages())
                <div class="card-footer">
                    {{ $feedbacks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
