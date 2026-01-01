@extends('layout.cms-layout')
@section('page-title', 'Database Backups - ')

@section('cms-main-content')
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Database Backups</h2>

                {{-- Only allow creation if authorized --}}
                <form action="{{ route('backups.create') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Create New Backup
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 py-3 border-bottom-0">Filename</th>
                            <th class="py-3 border-bottom-0">Size</th>
                            <th class="py-3 border-bottom-0">Created At</th>
                            <th class="text-end pe-3 py-3 border-bottom-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($backups as $backup)
                            <tr>
                                <td class="ps-3 fw-bold text-dark">
                                    <i class="fas fa-database text-secondary me-2"></i> {{ $backup['filename'] }}
                                </td>
                                <td>{{ $backup['size'] }}</td>
                                <td>{{ $backup['date'] }}</td>
                                <td class="text-end pe-3">
                                    <div class="d-flex justify-content-end gap-2">
                                        {{-- Download --}}
                                        <a href="{{ route('backups.download', $backup['filename']) }}"
                                            class="btn btn-sm btn-outline-info" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('backups.delete', $backup['filename']) }}" method="POST"
                                            onsubmit="return confirm('Delete this backup?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>

                                        {{-- Restore: Super Admin Only --}}
                                        @role('super_admin')
                                            <div class="vr mx-1"></div>
                                            <form action="{{ route('backups.restore', $backup['filename']) }}" method="POST"
                                                onsubmit="return confirm('DANGER: This will OVERWRITE the current database with this backup. Are you absolutely sure?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" title="Restore Database">
                                                    <i class="fas fa-history me-1"></i> Restore
                                                </button>
                                            </form>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle me-2"></i> No backups found. Create one to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 text-muted small">
        <i class="fas fa-info-circle me-1"></i>
        @role('super_admin')
            Restoring a database replaces all current data. Use with caution.
        @else
            Database backups are stored securely. Contact Super Admin for restoration.
        @endrole
    </div>
@endsection
