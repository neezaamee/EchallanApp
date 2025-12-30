@extends('layout.cms-layout')
@section('page-title', 'Show User - ')
@section('cms-main-content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show User</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $user->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email:</strong>
                {{ $user->email }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Roles:</strong>
                @if (!empty($user->getRoleNames()))
                    @foreach ($user->getRoleNames() as $v)
                        <label class="badge badge-success text-reset text-uppercase">{{ $v }}</label>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Direct Permissions:</strong>
                @if ($user->getDirectPermissions()->count() > 0)
                    @foreach ($user->getDirectPermissions() as $v)
                        <label class="badge badge-primary text-reset">{{ $v->name }}</label>
                    @endforeach
                @else
                    <span class="text-muted">No direct permissions.</span>
                @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Permissions via Roles:</strong>
                @if ($user->getPermissionsViaRoles()->count() > 0)
                    @foreach ($user->getPermissionsViaRoles() as $v)
                        <label class="badge badge-info text-reset">{{ $v->name }}</label>
                    @endforeach
                @else
                    <span class="text-muted">No inherited permissions.</span>
                @endif
            </div>
        </div>
    </div>
@endsection
