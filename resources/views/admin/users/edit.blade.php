@extends('layouts.app')
@section('page-title', 'Edit User - ')
@section('cms-main-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
             <h5 class="mb-0">Edit User</h5>
             <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}"> Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" placeholder="Name"
                        readonly>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email:</strong>
                    <input type="text" name="email" value="{{ $user->email }}" class="form-control"
                        placeholder="Email" readonly>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>CNIC:</strong>
                    <input type="text" name="cnic" value="{{ $user->cnic }}" class="form-control" placeholder="CNIC"
                        readonly>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Password:</strong>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
            </div>
            @role('super_admin')
                {{-- Password hidden in edit, available in show --}}
            @endrole
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Confirm Password:</strong>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Role:</strong>
                    <select name="roles[]" class="form-control" multiple>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ in_array($role, $userRoles) ? 'selected' : '' }}>
                                {{ $role }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Direct Permissions (Optional):</strong>
                    <br />
                    <div class="row">
                        @foreach ($permissions as $value)
                            @php
                                $isInherited = in_array($value->name, $rolePermissions);
                                $isDirect = in_array($value->name, $userPermissions);
                            @endphp
                            <div class="col-md-3">
                                <label class="{{ $isInherited || $isDirect ? 'text-success fw-bold' : '' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $value->name }}" class="name"
                                        {{ $isInherited || $isDirect ? 'checked' : '' }}
                                        {{ $isInherited ? 'disabled' : '' }}>
                                    {{ $value->name }}
                                    @if ($isInherited)
                                        <span class="text-success small">(via Role)</span>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    </div>
</div>
@endsection
