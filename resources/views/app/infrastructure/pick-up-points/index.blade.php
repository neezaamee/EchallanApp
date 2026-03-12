@extends('layouts.app')
@section('page-title', 'Pick Up Points')
@section('cms-main-content')
    <div class="card mb-3">
        <div class="card-header bg-light">
            <div class="row flex-between-center">
                <div class="col-auto">
                    <h5 class="mb-0">Pick Up Points</h5>
                </div>
                <div class="col-auto">
                    @can('pick-up-points:create')
                        <a href="{{ route('pick-up-points.create') }}" class="btn btn-falcon-default btn-sm">
                            <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span> Add New
                        </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive scrollbar">
                <table class="table table-sm table-striped table-hover align-middle mb-0 fs--1">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="white-space-nowrap" style="width: 50px;"># ID</th>
                            <th>Name</th>
                            <th>Dumping Point</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pickUpPoints as $point)
                            <tr>
                                <td class="text-muted">#{{ $point->id }}</td>
                                <td class="fw-bold text-dark">{{ $point->name }}</td>
                                <td><span class="badge badge-soft-info text-dark fs--2">{{ $point->dumpingPoint->name ?? 'N/A' }}</span></td>
                                <td><small class="text-muted">{{ $point->location ?? '-' }}</small></td>
                                <td>
                                    <span class="badge badge-soft-{{ $point->is_active ? 'success' : 'secondary' }} text-dark fs--2">
                                        {{ $point->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        @can('pick-up-points:edit')
                                            <a href="{{ route('pick-up-points.edit', $point->id) }}" class="btn btn-link p-0 text-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('pick-up-points:delete')
                                            <form action="{{ route('pick-up-points.destroy', $point->id) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-link p-0 text-danger" type="submit" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-4 text-muted">No Pick Up Points Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light py-2">
            <div class="d-flex justify-content-end">
                {{ $pickUpPoints->links() }}
            </div>
        </div>
    </div>
@endsection
