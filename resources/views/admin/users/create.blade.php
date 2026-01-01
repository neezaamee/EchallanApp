@extends('layout.cms-layout')
@section('page-title', 'Create New User - ')
@section('cms-main-content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Create New User</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Role:</strong>
                <select name="roles[]" id="roles" class="form-control" multiple>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12" id="staff-section" style="display: none;">
            <div class="form-group">
                <strong>Select Staff:</strong>
                <select name="staff_id" id="staff_id" class="form-control">
                    <option value="">-- Select Staff --</option>
                    @foreach ($unlinkedStaff as $staff)
                        <option value="{{ $staff->id }}" data-name="{{ $staff->fullName() }}"
                            data-email="{{ $staff->email }}" data-cnic="{{ $staff->cnic }}">
                            {{ $staff->fullName() }} ({{ $staff->cnic }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email:</strong>
                <input type="text" name="email" id="email" class="form-control" placeholder="Email">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>CNIC:</strong>
                <input type="text" name="cnic" id="cnic" class="form-control"
                    placeholder="CNIC (e.g. 12345-1234567-1)">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Password:</strong>
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Confirm Password:</strong>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Direct Permissions (Optional):</strong>
                <br />
                <div class="row">
                    @foreach ($permissions as $value)
                        <div class="col-md-3">
                            <label><input type="checkbox" name="permissions[]" value="{{ $value->name }}" class="name">
                                {{ $value->name }}</label>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roles');
            const staffSection = document.getElementById('staff-section');
            const staffSelect = document.getElementById('staff_id');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const cnicInput = document.getElementById('cnic');

            function toggleStaffSection() {
                const selectedRoles = Array.from(roleSelect.selectedOptions).map(option => option.value);
                // Only "Citizen" can be created direct. 
                // If anything ELSE is selected, we require staff.
                if (selectedRoles.length > 0 && !selectedRoles.includes('Citizen')) {
                    staffSection.style.display = 'block';
                } else {
                    staffSection.style.display = 'none';
                    staffSelect.value = '';
                    // Clear fields if returning to direct creation mode
                    nameInput.value = '';
                    emailInput.value = '';
                    cnicInput.value = '';
                    nameInput.readOnly = false;
                    emailInput.readOnly = false;
                    cnicInput.readOnly = false;
                }
            }

            roleSelect.addEventListener('change', toggleStaffSection);

            staffSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    nameInput.value = selectedOption.getAttribute('data-name');
                    emailInput.value = selectedOption.getAttribute('data-email');
                    cnicInput.value = selectedOption.getAttribute('data-cnic');

                    nameInput.readOnly = true;
                    emailInput.readOnly = true;
                    cnicInput.readOnly = true;
                } else {
                    nameInput.value = '';
                    emailInput.value = '';
                    cnicInput.value = '';
                    nameInput.readOnly = false;
                    emailInput.readOnly = false;
                    cnicInput.readOnly = false;
                }
            });

            // Initial state
            toggleStaffSection();
        });
    </script>
@endsection
