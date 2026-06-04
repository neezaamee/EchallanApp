@extends('layouts.app')
@section('page-title', 'Edit User - ')
@section('add-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--multiple { min-height: 38px; }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0d6efd; border-color: #0d6efd; color: #fff;
        }
    </style>
@endsection
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
                    <!-- Global Error Alert if needed -->
                    @if ($errors->any())
                        <div class="col-12 mb-3">
                            <div class="alert alert-danger mb-0">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label"><strong>Role: <span class="text-danger">*</span></strong></label>
                            <select name="roles[]" id="roles" class="form-control select2 @error('roles') is-invalid @enderror" multiple required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" {{ (collect(old('roles', $userRoles))->contains($role)) ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">
                                <strong>Name: <span class="text-danger">*</span></strong>
                                <span class="badge bg-secondary ms-2 readonly-badge">Readonly</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="Name" readonly required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">
                                <strong>Email: <span class="text-danger">*</span></strong>
                                <span class="badge bg-secondary ms-2 readonly-badge">Readonly</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" readonly required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">
                                <strong>CNIC: <span class="text-danger">*</span></strong>
                                <span class="badge bg-secondary ms-2 readonly-badge">Readonly</span>
                            </label>
                            <input type="text" name="cnic" value="{{ old('cnic', $user->cnic) }}" class="form-control @error('cnic') is-invalid @enderror" placeholder="CNIC" readonly required>
                            @error('cnic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label mb-0"><strong>Password: <small class="text-muted">(Leave blank to keep current)</small></strong></label>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-generate-password">
                                    <i class="fas fa-magic"></i> Quick Generate
                                </button>
                            </div>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" minlength="8">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label"><strong>Confirm Password:</strong></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password" minlength="8">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label"><strong>Direct Permissions (Optional):</strong></label>
                            <br />
                            <div class="row">
                                @foreach ($permissions as $value)
                                    @php
                                        // If validation failed, use old() permissions. Otherwise, use what's in DB.
                                        if (null !== old('permissions')) {
                                            $isDirect = in_array($value->name, old('permissions', []));
                                        } else {
                                            $isDirect = in_array($value->name, $userPermissions);
                                        }
                                        $isInherited = in_array($value->name, $rolePermissions);
                                    @endphp
                                    <div class="col-md-3 mb-2">
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
                            @error('permissions') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('#roles').select2({
                placeholder: "Search and select roles...",
                allowClear: true
            });

            // Password Generator
            document.getElementById('btn-generate-password').addEventListener('click', function() {
                const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
                let password = "";
                for (let i = 0; i < 12; i++) {
                    const randomNumber = Math.floor(Math.random() * chars.length);
                    password += chars.substring(randomNumber, randomNumber + 1);
                }
                
                const pwdInput = document.getElementById('password');
                const confirmPwdInput = document.getElementById('password_confirmation');
                
                pwdInput.value = password;
                confirmPwdInput.value = password;
                
                // Change to text so user can see it
                pwdInput.type = "text";
                confirmPwdInput.type = "text";
            });
        });
    </script>
@endsection
