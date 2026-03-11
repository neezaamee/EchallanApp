@extends('layouts.app')
@section('page-title', 'Show User - ')
@section('cms-main-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
             <h5 class="mb-0">Show User</h5>
             <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}"> Back</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th style="width: 200px;">Name</th>
                            <td>
                                @if($user->staff)
                                    <a href="{{ route('staff.edit', $user->staff->id) }}" title="View Staff Details">
                                        {{ $user->name }} <span class="fas fa-external-link-alt ms-1 fs--2"></span>
                                    </a>
                                @else
                                    {{ $user->name }}
                                @endif
                            </td>
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
