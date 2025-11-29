@extends('layout.cms-layout')
@section('page-title', 'Feedback Details - ')

@section('cms-main-content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Feedback Details</h4>
            <a href="{{ route('feedback.index') }}" class="btn btn-secondary">
                <span class="fas fa-arrow-left me-1"></span>Back to List
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Feedback Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Type:</strong></div>
                            <div class="col-md-8">
                                <span class="badge bg-info">{{ ucwords(str_replace('_', ' ', $feedback->type)) }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Subject:</strong></div>
                            <div class="col-md-8">{{ $feedback->subject }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Status:</strong></div>
                            <div class="col-md-8">
                                <span
                                    class="badge {{ $feedback->status === 'resolved' ? 'bg-success' : ($feedback->status === 'reviewed' ? 'bg-warning' : 'bg-secondary') }}">
                                    {{ ucfirst($feedback->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Submitted:</strong></div>
                            <div class="col-md-8">{{ $feedback->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        @role(['super_admin', 'admin'])
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Submitted By:</strong></div>
                                <div class="col-md-8">
                                    {{ $feedback->user->name ?? 'N/A' }}<br>
                                    <small class="text-muted">{{ $feedback->user->email ?? '' }}</small>
                                </div>
                            </div>
                        @endrole
                        <hr>
                        <div class="mb-0">
                            <strong>Message:</strong>
                            <p class="mt-2">{{ $feedback->message }}</p>
                        </div>

                        @if ($feedback->admin_notes)
                            <hr>
                            <div class="mb-0">
                                <strong>Admin Response:</strong>
                                <div class="alert alert-info mt-2 mb-0">
                                    {{ $feedback->admin_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @role(['super_admin', 'admin'])
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Admin Actions</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('feedback.update', $feedback->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select id="status" name="status" class="form-select" required>
                                        <option value="pending" {{ $feedback->status === 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="reviewed" {{ $feedback->status === 'reviewed' ? 'selected' : '' }}>
                                            Reviewed</option>
                                        <option value="resolved" {{ $feedback->status === 'resolved' ? 'selected' : '' }}>
                                            Resolved</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="admin_notes">Admin Notes</label>
                                    <textarea id="admin_notes" name="admin_notes" class="form-control" rows="4">{{ $feedback->admin_notes }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <span class="fas fa-save me-1"></span>Update
                                </button>
                            </form>
                        </div>
                    </div>

                    @if ($feedback->admin_notes)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Current Admin Notes</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $feedback->admin_notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endrole
        </div>
    </div>
@endsection
