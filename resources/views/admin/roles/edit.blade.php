@extends('layout.cms-layout')
@section('page-title', 'Edit Role - ')
@section('cms-main-content')
    <div class="row mb-3">
        <div class="col">
            <div class="card bg-100 shadow-none border">
                <div class="row gx-0 flex-between-center">
                    <div class="col-sm-auto d-flex align-items-center"><img class="ms-n2"
                            src="{{ asset('assets/img/illustrations/crm-bar-chart.png') }}" alt="" width="90" />
                        <div>
                            <h6 class="text-primary fs-10 mb-0">Security Management</h6>
                            <h4 class="text-primary fw-bold mb-0">
                                Edit Role: {{ $role->name }}
                                <span class="text-danger fw-medium"> - </span><span class="text-info fw-medium"> Modify
                                    Permissions</span>
                            </h4>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <a class="btn btn-falcon-default btn-sm" href="{{ route('roles.index') }}">
                            <span class="fas fa-arrow-left me-1"></span> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger border-2 d-flex align-items-center" role="alert">
            <div class="bg-danger me-3 icon-item"><span class="fas fa-times-circle text-white fs-9"></span></div>
            <p class="mb-0 flex-1">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </p>
        </div>
    @endif

    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Role Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold" for="roleName">Role Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" id="roleName" class="form-control" value="{{ $role->name }}"
                            placeholder="e.g. Manager" required>
                        <div class="form-text">Unique identifier for the role.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Assign Permissions</h5>
                <div class="form-check mb-0">
                    <input class="form-check-input" id="checkAll" type="checkbox" />
                    <label class="form-check-label mb-0" for="checkAll">Select All Permissions</label>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($groupedPermissions as $group => $perms)
                        <div class="col-md-4 col-xxl-3">
                            <div class="border rounded-2 p-3 h-100 bg-light-subtle">
                                <h6 class="fw-bold border-bottom pb-2 mb-3 text-primary">
                                    <span class="fas fa-layer-group me-2"></span>{{ $group }} Module
                                </h6>
                                @foreach ($perms as $permission)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input permission-checkbox" type="checkbox"
                                            name="permission[]" value="{{ $permission->name }}"
                                            id="perm-{{ $permission->id }}"
                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                        <label class="form-check-label fs-10" for="perm-{{ $permission->id }}">
                                            {{ ucwords(str_replace(['read', 'crud', 'delete', 'verify'], '', $permission->name)) }}
                                            <span class="text-400 d-block fs-11">Full name: {{ $permission->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer bg-light text-end">
                <button type="submit" class="btn btn-primary px-5">
                    <span class="fas fa-save me-1"></span> Update Role
                </button>
            </div>
        </div>
    </form>

    <script>
        document.getElementById('checkAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
@endsection
