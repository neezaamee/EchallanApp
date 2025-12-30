@extends('layout.cms-layout')
@section('page-title', 'Permission Management - ')
@section('cms-main-content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Permission Management</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('permissions.create') }}"> Create New Permission</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($permissions as $key => $permission)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $permission->name }}</td>
                <td>
                    <a class="btn btn-primary" href="{{ route('permissions.edit', $permission->id) }}">Edit</a>
                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                        style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $permissions->render() !!}
@endsection
