@extends('layout.cms-layout')
@section('page-title', 'Edit User - ')
@section('cms-main-content')
<div class="container mt-4">
    
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Edit Form -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">Edit User Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Name:</strong></label>
                                <input type="text" name="name" value="{{ $user->name }}" class="form-control" placeholder="Name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Role:</strong></label>
                                <select class="form-select" name="roles[]" multiple>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}" {{ in_array($role, $userRole) ? 'selected' : '' }}>{{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        @if($user->is_department_user && $user->staff)
                        <!-- Department Fields -->
                        <h5 class="mb-3 text-primary">Staff Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $user->staff->first_name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $user->staff->last_name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Belt No</label>
                                <input type="text" name="belt_no" class="form-control" value="{{ $user->staff->belt_no }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rank</label>
                                <select name="rank_id" class="form-select">
                                    <option value="">Select Rank</option>
                                    @foreach($ranks as $rank)
                                        <option value="{{ $rank->id }}" {{ $user->staff->rank_id == $rank->id ? 'selected' : '' }}>{{ $rank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        <!-- Common Fields -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CNIC</label>
                                <input type="text" name="cnic" class="form-control" value="{{ $user->cnic }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $user->staff ? $user->staff->phone : ($user->citizen ? $user->citizen->phone : '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    @php
                                        $gender = $user->staff ? $user->staff->gender : ($user->citizen ? $user->citizen->gender : '');
                                    @endphp
                                    <option value="male" {{ $gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ $gender == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Direct Permissions -->
                        <h5 class="mb-3 text-primary">Direct Permissions (Optional)</h5>
                        <div class="row">
                            @foreach($permissions as $value)
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $value->id }}" id="perm-{{ $value->id }}"
                                        {{ $user->hasDirectPermission($value->name) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm-{{ $value->id }}">
                                            {{ $value->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Side Actions -->
        <div class="col-md-4">
            <!-- Change Password -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 text-white">Change Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.change-password', $user->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm-password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Update Password</button>
                    </form>
                </div>
            </div>

            <!-- Change Email -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info">
                    <h5 class="mb-0 text-white">Change Email</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.change-email', $user->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Current Email</label>
                            <input type="text" class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-info w-100 text-white">Update Email</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
