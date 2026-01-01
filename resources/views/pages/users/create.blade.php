@extends('layout.cms-layout')
@section('page-title', 'Create User - ')
@section('cms-main-content')
<div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary">
            <h5 class="mb-0 text-white">Create New Department User</h5>
        </div>

        <div class="card-body">
            @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Name:</strong></label>
                        <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Email:</strong></label>
                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Password:</strong></label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Confirm Password:</strong></label>
                        <input type="password" name="confirm-password" class="form-control" placeholder="Confirm Password">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Role:</strong></label>
                        <select class="form-select" name="roles[]" multiple>
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Department Fields -->
                <h5 class="mb-3 text-primary">Staff Details</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Belt No</label>
                        <input type="text" name="belt_no" class="form-control" value="{{ old('belt_no') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Rank</label>
                        <select name="rank_id" class="form-select">
                            <option value="">Select Rank</option>
                            @foreach($ranks as $rank)
                                <option value="{{ $rank->id }}" {{ old('rank_id') == $rank->id ? 'selected' : '' }}>{{ $rank->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Common Fields -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">CNIC</label>
                        <input type="text" name="cnic" class="form-control" value="{{ old('cnic') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
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
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $value->id }}" id="perm-{{ $value->id }}">
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
                        <i class="fas fa-save me-1"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
