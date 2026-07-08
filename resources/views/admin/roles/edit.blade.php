@extends('layouts.app')

@section('page-title', 'Edit Role - ')

@section('cms-main-content')
    <div class="container-fluid mt-2">
        @if (count($errors) > 0)
            <x-falcon.alert variant="danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-falcon.alert>
        @endif

        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <x-falcon.card title="Edit Role: {{ $role->name }}" bodyClass="p-4">
                <x-slot name="headerActions">
                    <x-falcon.button href="{{ route('roles.index') }}" variant="secondary" icon="fas fa-arrow-left">
                        Back to List
                    </x-falcon.button>
                </x-slot>

                <div class="row">
                    <div class="col-md-6">
                        <x-falcon.form-group label="Role Name" name="name" required="true" helpText="Unique identifier for the role.">
                            <input type="text" name="name" id="roleName" class="form-control shadow-none" value="{{ $role->name }}" placeholder="e.g. Manager" required>
                        </x-falcon.form-group>
                    </div>
                </div>
            </x-falcon.card>

            <x-falcon.card title="Assign Permissions" bodyClass="p-4" headerClass="bg-light">
                <x-slot name="headerActions">
                    <div class="form-check mb-0">
                        <input class="form-check-input cursor-pointer" id="checkAll" type="checkbox" />
                        <label class="form-check-label mb-0 fw-bold fs--1 text-dark" for="checkAll">Select All Permissions</label>
                    </div>
                </x-slot>

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
                                        <label class="form-check-label fs-10 text-800" for="perm-{{ $permission->id }}">
                                            {{ ucwords(str_replace(['read', 'crud', 'delete', 'verify'], '', $permission->name)) }}
                                            <span class="text-400 d-block fs-11">Full: {{ $permission->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <x-slot name="footer">
                    <div class="text-end">
                        <x-falcon.button type="submit" variant="primary" icon="fas fa-save">
                            Update Role
                        </x-falcon.button>
                    </div>
                </x-slot>
            </x-falcon.card>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            if (checkAll) {
                checkAll.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.permission-checkbox');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                });
            }
        });
    </script>
@endsection
