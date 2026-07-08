@extends('layouts.app')
@section('page-title', 'Pick Up Points')

@section('cms-main-content')
    <div class="card mb-3">
        <div class="card-header bg-light">
            <div class="row flex-between-center">
                <div class="col-auto">
                    <h5 class="mb-0">Pick Up Points</h5>
                </div>
                <div class="col-auto d-flex align-items-center gap-2">
                    <form action="{{ route('pick-up-points.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        @if(request('direction'))
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        @endif
                        <select name="per_page" class="form-select form-select-sm shadow-none w-auto" onchange="this.form.submit()">
                            <option value="20" {{ request('per_page', 50) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 50) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <small class="text-muted">per page</small>
                    </form>
                    @can('pick-up-points:create')
                        <a href="{{ route('pick-up-points.create') }}" class="btn btn-falcon-default btn-sm">
                            <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span> Add New
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0 fs--1" role="alert">
                <span class="fas fa-check-circle me-2"></span>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-body p-0">
            <div class="table-responsive scrollbar">
                <table class="table table-sm table-striped table-hover align-middle mb-0 fs--1">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="ps-3" style="width: 60px;">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => (request('sort') === 'id' && request('direction') === 'asc') ? 'desc' : 'asc', 'page' => 1]) }}" class="text-900 text-decoration-none">
                                    No
                                    @if(request('sort') === 'id')
                                        <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1 text-primary fs--2"></i>
                                    @else
                                        <i class="fas fa-sort ms-1 text-400 fs--2"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => (request('sort') === 'name' && request('direction') === 'asc') ? 'desc' : 'asc', 'page' => 1]) }}" class="text-900 text-decoration-none">
                                    Name
                                    @if(request('sort') === 'name')
                                        <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1 text-primary fs--2"></i>
                                    @else
                                        <i class="fas fa-sort ms-1 text-400 fs--2"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'dumping_point_id', 'direction' => (request('sort') === 'dumping_point_id' && request('direction') === 'asc') ? 'desc' : 'asc', 'page' => 1]) }}" class="text-900 text-decoration-none">
                                    Dumping Point
                                    @if(request('sort') === 'dumping_point_id')
                                        <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1 text-primary fs--2"></i>
                                    @else
                                        <i class="fas fa-sort ms-1 text-400 fs--2"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Location</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'is_active', 'direction' => (request('sort') === 'is_active' && request('direction') === 'asc') ? 'desc' : 'asc', 'page' => 1]) }}" class="text-900 text-decoration-none">
                                    Status
                                    @if(request('sort') === 'is_active')
                                        <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1 text-primary fs--2"></i>
                                    @else
                                        <i class="fas fa-sort ms-1 text-400 fs--2"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pickUpPoints as $point)
                            <tr>
                                <td class="text-muted ps-3">{{ ($pickUpPoints->currentPage() - 1) * $pickUpPoints->perPage() + $loop->iteration }}</td>
                                <td class="fw-bold text-dark">{{ $point->name }}</td>
                                <td>
                                    <span class="badge badge-soft-info text-dark fs--2">
                                        {{ $point->dumpingPoint->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td><small class="text-muted">{{ $point->location ?? '—' }}</small></td>
                                <td>
                                    <span class="badge badge-soft-{{ $point->is_active ? 'success' : 'secondary' }} text-dark fs--2">
                                        {{ $point->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="dropdown font-sans-serif btn-reveal-trigger">
                                        <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal" type="button" data-bs-toggle="dropdown">
                                            <span class="fas fa-ellipsis-h fs--1"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end border py-0 shadow">
                                            <div class="py-2">
                                                @can('pick-up-points:edit')
                                                    <a class="dropdown-item" href="{{ route('pick-up-points.edit', $point->id) }}">
                                                        <span class="fas fa-pencil-alt me-2 text-primary"></span>Edit
                                                    </a>
                                                @endcan
                                                @can('pick-up-points:delete')
                                                    <form action="{{ route('pick-up-points.destroy', $point->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this pick-up point?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <span class="fas fa-trash-alt me-2"></span>Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-4 text-muted">
                                    <span class="fas fa-map-marker-alt me-2 opacity-50"></span>No Pick Up Points Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-light py-2">
            <x-falcon.pagination>
                {{ $pickUpPoints->appends(request()->query())->links() }}
            </x-falcon.pagination>
        </div>
    </div>
@endsection
