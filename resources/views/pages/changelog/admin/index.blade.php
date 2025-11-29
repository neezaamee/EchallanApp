@extends('layout.cms-layout')
@section('page-title', 'Manage Changelog - ')

@section('cms-main-content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Manage Changelog</h4>
            <a href="{{ route('changelog.create') }}" class="btn btn-primary">
                <span class="fas fa-plus me-1"></span>Add Entry
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Version</th>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Release Date</th>
                                <th>Published</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($changelogs as $log)
                                <tr>
                                    <td><strong>{{ $log->version }}</strong></td>
                                    <td><span class="badge bg-{{ $log->type_badge_color }}">{{ ucfirst($log->type) }}</span>
                                    </td>
                                    <td>{{ Str::limit($log->title, 50) }}</td>
                                    <td>{{ $log->release_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge {{ $log->is_published ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $log->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td>{{ $log->order }}</td>
                                    <td>
                                        <a href="{{ route('changelog.edit', $log->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <span class="fas fa-edit"></span>
                                        </a>
                                        <form action="{{ route('changelog.destroy', $log->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure?')">
                                                <span class="fas fa-trash"></span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        No changelog entries. <a href="{{ route('changelog.create') }}">Create one</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($changelogs->hasPages())
                <div class="card-footer">
                    {{ $changelogs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
