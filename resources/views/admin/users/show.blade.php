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
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th style="width: 200px;">Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>CNIC</th>
                            <td>{{ $user->cnic ?? 'N/A' }}</td>
                        </tr>
                        @role('super_admin')
                            <tr>
                                <th>Stored Password</th>
                                <td><span class="text-danger">{{ $user->plain_password ?? 'N/A' }}</span></td>
                            </tr>
                        @endrole
                        <tr>
                            <th>Roles</th>
                            <td>
                                @if (!empty($user->getRoleNames()))
                                    @foreach ($user->getRoleNames() as $v)
                                        <label
                                            class="badge badge-success text-reset text-uppercase">{{ $v }}</label>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Direct Permissions</th>
                            <td>
                                @if ($user->getDirectPermissions()->count() > 0)
                                    @foreach ($user->getDirectPermissions() as $v)
                                        <label class="badge badge-primary text-reset">{{ $v->name }}</label>
                                    @endforeach
                                @else
                                    <span class="text-muted">No direct permissions.</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Permissions via Roles</th>
                            <td>
                                @if ($user->getPermissionsViaRoles()->count() > 0)
                                    @foreach ($user->getPermissionsViaRoles() as $v)
                                        <label class="badge badge-info text-reset">{{ $v->name }}</label>
                                    @endforeach
                                @else
                                    <span class="text-muted">No inherited permissions.</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
