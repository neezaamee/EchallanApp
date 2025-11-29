@extends('layout.cms-layout')
@section('page-title', isset($changelog) ? 'Edit Changelog - ' : 'Create Changelog - ')

@section('cms-main-content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ isset($changelog) ? 'Edit' : 'Create' }} Changelog Entry</h5>
            </div>
            <div class="card-body">
                <form method="POST"
                    action="{{ isset($changelog) ? route('changelog.update', $changelog->id) : route('changelog.store') }}">
                    @csrf
                    @if (isset($changelog))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="version">Version <span class="text-danger">*</span></label>
                            <input type="text" id="version" name="version" class="form-control"
                                value="{{ old('version', $changelog->version ?? '') }}" required placeholder="e.g., 1.2.0">
                            @error('version')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="release_date">Release Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" id="release_date" name="release_date" class="form-control"
                                value="{{ old('release_date', isset($changelog) ? $changelog->release_date->format('Y-m-d') : '') }}"
                                required>
                            @error('release_date')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="type">Type <span class="text-danger">*</span></label>
                            <select id="type" name="type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="added"
                                    {{ old('type', $changelog->type ?? '') == 'added' ? 'selected' : '' }}>Added</option>
                                <option value="changed"
                                    {{ old('type', $changelog->type ?? '') == 'changed' ? 'selected' : '' }}>Changed
                                </option>
                                <option value="fixed"
                                    {{ old('type', $changelog->type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                <option value="removed"
                                    {{ old('type', $changelog->type ?? '') == 'removed' ? 'selected' : '' }}>Removed
                                </option>
                                <option value="security"
                                    {{ old('type', $changelog->type ?? '') == 'security' ? 'selected' : '' }}>Security
                                </option>
                                <option value="deprecated"
                                    {{ old('type', $changelog->type ?? '') == 'deprecated' ? 'selected' : '' }}>Deprecated
                                </option>
                            </select>
                            @error('type')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="order">Order</label>
                            <input type="number" id="order" name="order" class="form-control"
                                value="{{ old('order', $changelog->order ?? 0) }}" min="0">
                            <small class="text-muted">Lower numbers appear first within a version</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control"
                            value="{{ old('title', $changelog->title ?? '') }}" required maxlength="255">
                        @error('title')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="description">Description <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description', $changelog->description ?? '') }}</textarea>
                        @error('description')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1"
                            {{ old('is_published', $changelog->is_published ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">
                            Publish (visible to users)
                        </label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('changelog.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <span class="fas fa-save me-1"></span>{{ isset($changelog) ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
