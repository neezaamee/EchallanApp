@extends('layouts.app')
@section('page-title', 'Create New User - ')
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
             <h5 class="mb-0">Create New User</h5>
             <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}"> Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
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
                                    <option value="{{ $role }}" {{ (collect(old('roles'))->contains($role)) ? 'selected':'' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                            @error('roles') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12" id="staff-section" style="display: none;">
                        <div class="form-group mb-3">
                            <label class="form-label"><strong>Select Staff: <span class="text-danger" id="staff_asterisk">*</span></strong></label>
                            <select name="staff_id" id="staff_id" class="form-control select2 @error('staff_id') is-invalid @enderror">
                                <option value="">-- Select Staff --</option>
                                @foreach ($unlinkedStaff as $staff)
                                    <option value="{{ $staff->id }}" data-name="{{ $staff->fullName() }}"
                                        data-email="{{ $staff->email }}" data-cnic="{{ $staff->cnic }}" {{ old('staff_id') == $staff->id ? 'selected':'' }}>
                                        {{ $staff->fullName() }} ({{ $staff->cnic }})
                                    </option>
                                @endforeach
                            </select>
                            @error('staff_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">
                                <strong>Name: <span class="text-danger">*</span></strong>
                                <span class="badge bg-secondary ms-2 readonly-badge" style="display: none;">Readonly</span>
                            </label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">
                                <strong>Email: <span class="text-danger">*</span></strong>
                                <span class="badge bg-secondary ms-2 readonly-badge" style="display: none;">Readonly</span>
                            </label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">
                                <strong>CNIC: <span class="text-danger">*</span></strong>
                                <span class="badge bg-secondary ms-2 readonly-badge" style="display: none;">Readonly</span>
                            </label>
                            <input type="text" name="cnic" id="cnic" class="form-control @error('cnic') is-invalid @enderror"
                                placeholder="CNIC (e.g. 12345-1234567-1)" value="{{ old('cnic') }}" required>
                            @error('cnic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label mb-0"><strong>Password: <span class="text-danger">*</span></strong></label>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-generate-password">
                                    <i class="fas fa-magic"></i> Quick Generate
                                </button>
                            </div>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required minlength="8">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label"><strong>Confirm Password: <span class="text-danger">*</span></strong></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password" required minlength="8">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label"><strong>Direct Permissions (Optional):</strong></label>
                            <br />
                            <div class="row">
                                @foreach ($permissions as $value)
                                    <div class="col-md-3">
                                        <label><input type="checkbox" name="permissions[]" value="{{ $value->name }}" class="name" {{ (is_array(old('permissions')) && in_array($value->name, old('permissions'))) ? 'checked' : '' }}>
                                            {{ $value->name }}</label>
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
            $('#staff_id').select2({
                placeholder: "Search staff...",
                allowClear: true
            });

            const roleSelect = $('#roles');
            const staffSection = document.getElementById('staff-section');
            const staffSelectElement = document.getElementById('staff_id');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const cnicInput = document.getElementById('cnic');

            function toggleStaffSection() {
                const selectedRoles = roleSelect.val() || [];
                // Only "Citizen" can be created direct. 
                // If anything ELSE is selected, we require staff.
                if (selectedRoles.length > 0 && !selectedRoles.includes('Citizen')) {
                    staffSection.style.display = 'block';
                    staffSelectElement.setAttribute('required', 'required');
                } else {
                    staffSection.style.display = 'none';
                    staffSelectElement.removeAttribute('required');
                    $('#staff_id').val(null).trigger('change');
                    // Clear fields if returning to direct creation mode
                    nameInput.value = '';
                    emailInput.value = '';
                    cnicInput.value = '';
                    nameInput.readOnly = false;
                    emailInput.readOnly = false;
                    cnicInput.readOnly = false;
                }
            }

            roleSelect.on('change', toggleStaffSection);

            $('#staff_id').on('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const readonlyBadges = document.querySelectorAll('.readonly-badge');
                
                if (selectedOption && selectedOption.value) {
                    nameInput.value = selectedOption.getAttribute('data-name');
                    emailInput.value = selectedOption.getAttribute('data-email');
                    cnicInput.value = selectedOption.getAttribute('data-cnic');

                    nameInput.readOnly = true;
                    emailInput.readOnly = true;
                    cnicInput.readOnly = true;
                    
                    readonlyBadges.forEach(badge => badge.style.display = 'inline-block');
                } else {
                    // Only clear if we actually had a selected staff option previously,
                    // to avoid clearing old() values on initial load if no staff was selected
                    if (nameInput.readOnly) {
                        nameInput.value = '';
                        emailInput.value = '';
                        cnicInput.value = '';
                    }
                    nameInput.readOnly = false;
                    emailInput.readOnly = false;
                    cnicInput.readOnly = false;
                    
                    readonlyBadges.forEach(badge => badge.style.display = 'none');
                }
            });

            // Trigger change on load if old value exists to set readonly states
            if($('#staff_id').val()) {
                $('#staff_id').trigger('change');
            }

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
                
                // Revert to password type after a few seconds or when they focus out?
                // Actually, let's keep it visible until they manually click or just keep it text
                // since they are generating it and need to see it to save it.
            });

            // Initial state
            toggleStaffSection();
        });
    </script>
@endsection
