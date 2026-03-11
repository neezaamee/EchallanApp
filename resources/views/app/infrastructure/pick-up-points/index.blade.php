@extends('layouts.app')
@section('page-title', 'Pick Up Points')
@section('cms-main-content')
    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h5 class="mb-0">Pick Up Points</h5>
                </div>
                <div class="col-auto ms-auto">
                    @can('pick-up-points:create')
                    <a href="{{ route('pick-up-points.create') }}" class="btn btn-primary btn-sm">
                        <span class="fas fa-plus me-1"></span> Add New
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body bg-light">
            <div class="table-responsive">
                <table class="table table-sm table-dashboard data-table no-wrap mb-0 fs--1 w-100">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort">ID</th>
                            <th class="sort">Name</th>
                            <th class="sort">Dumping Point</th>
                            <th class="sort">Location</th>
                            <th class="sort">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @forelse($pickUpPoints as $point)
                            <tr>
                                <td>{{ $point->id }}</td>
                                <td>{{ $point->name }}</td>
                                <td>{{ $point->dumpingPoint->name ?? 'N/A' }}</td>
                                <td>{{ $point->location ?? '-' }}</td>
                                <td>
                                    @if($point->is_active)
                                        <span class="badge badge-soft-success">Active</span>
                                    @else
                                        <span class="badge badge-soft-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @can('pick-up-points:edit')
                                    <a href="{{ route('pick-up-points.edit', $point->id) }}" class="btn btn-link p-0 ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <span class="fas fa-edit text-500"></span>
                                    </a>
                                    @endcan

                                    @can('pick-up-points:delete')
                                    <form action="{{ route('pick-up-points.destroy', $point->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-link p-0 ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                            <span class="fas fa-trash-alt text-danger"></span>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No Pick Up Points Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $pickUpPoints->links() }}
            </div>
        </div>
    </div>
@endsection
