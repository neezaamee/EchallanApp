@extends('layout.cms-layout')
@section('page-title', 'Roles Management - ')
@section('cms-main-content')
<div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">Roles Management</h5>
            <a href="{{ route('roles.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Create New Role
            </a>
        </div>

        <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td class="text-end">
                                <a class="btn btn-link p-0 text-primary me-2" href="{{ route('roles.edit',$role->id) }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0 text-danger" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {!! $roles->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection
