@extends('layout.cms-layout')
@section('page-title', 'Users Management - ')
@section('cms-main-content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Users Management</h2>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- Header section with Search and Create -->
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <form action="{{ route('users.index') }}" method="GET" class="w-25">
            <input type="text" name="search" class="form-control" placeholder="Search users..."
                value="{{ request('search') }}">
        </form>

        <a class="btn btn-success" href="{{ route('users.create') }}">
            Create New User
        </a>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th style="cursor: pointer;">#</th>
                    <th style="cursor: pointer;">Name</th>
                    <th style="cursor: pointer;">Email</th>
                    <th>Roles</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $key => $user)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if (!empty($user->getRoleNames()))
                                @foreach ($user->getRoleNames() as $v)
                                    <label class="badge badge-success text-dark">{{ $v }}</label>
                                @endforeach
                            @endif
                        </td>
                        <td class="text-end">
                            <a class="btn btn-link p-0 text-info" href="{{ route('users.show', $user->id) }}"
                                title="Show">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a class="btn btn-link p-0 text-primary ms-2" href="{{ route('users.edit', $user->id) }}"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline"
                                onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0 text-danger ms-2" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {!! $users->withQueryString()->links() !!}
        </div>
    </div>
@endsection
