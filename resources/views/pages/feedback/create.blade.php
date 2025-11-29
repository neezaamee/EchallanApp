@extends('layout.cms-layout')
@section('page-title', 'Submit Feedback - ')

@section('cms-main-content')
    <div class="container mt-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Submit Feedback</h5>
            </div>
            <div class="card-body bg-light">
                <form method="POST" action="{{ route('feedback.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="type">Feedback Type <span class="text-danger">*</span></label>
                        <select id="type" name="type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="suggestion" {{ old('type') == 'suggestion' ? 'selected' : '' }}>Suggestion
                            </option>
                            <option value="complaint" {{ old('type') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                            <option value="bug_report" {{ old('type') == 'bug_report' ? 'selected' : '' }}>Bug Report
                            </option>
                            <option value="feature_request" {{ old('type') == 'feature_request' ? 'selected' : '' }}>Feature
                                Request</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="subject">Subject <span class="text-danger">*</span></label>
                        <input type="text" id="subject" name="subject" class="form-control"
                            value="{{ old('subject') }}" required maxlength="255">
                        @error('subject')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="message">Message <span class="text-danger">*</span></label>
                        <textarea id="message" name="message" class="form-control" rows="6" required minlength="10">{{ old('message') }}</textarea>
                        <small class="text-muted">Minimum 10 characters</small>
                        @error('message')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('feedback.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <span class="fas fa-paper-plane me-1"></span>Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
