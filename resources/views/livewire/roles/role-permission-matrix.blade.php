<div class="card mb-3">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="fs-0 mb-0">Interactive Roles & Permissions Matrix</h5>
        <div class="d-flex align-items-center">
            <div wire:loading class="spinner-border spinner-border-sm text-primary me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <a href="{{ route('roles.index') }}" class="btn btn-falcon-default btn-sm">
                <i class="fas fa-list me-1"></i> Manage Roles
            </a>
            <a href="{{ route('permissions.index') }}" class="btn btn-falcon-default btn-sm ms-2">
                <i class="fas fa-key me-1"></i> Manage Permissions
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        @if (session()->has('message'))
            <div class="alert alert-success border-2 d-flex align-items-center p-2 mb-0 rounded-0" role="alert">
                <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-9"></span></div>
                <p class="mb-0 flex-1">{{ session('message') }}</p>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger border-2 d-flex align-items-center p-2 mb-0 rounded-0" role="alert">
                <div class="bg-danger me-3 icon-item"><span class="fas fa-times-circle text-white fs-9"></span></div>
                <p class="mb-0 flex-1">{{ session('error') }}</p>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive scrollbar" style="max-height: 80vh;">
            <table class="table table-sm table-hover table-bordered table-striped align-middle fs--1 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="white-space-nowrap align-middle" style="min-width: 250px; position: sticky; top: 0; z-index: 10; background-color: var(--falcon-200, #edf2f9);">Permissions</th>
                        @foreach($roles as $role)
                            <th class="text-center align-middle" style="position: sticky; top: 0; z-index: 10; background-color: var(--falcon-200, #edf2f9);">
                                <span class="badge rounded-pill badge-soft-primary text-light-emphasis">
                                    {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                </span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedPermissions as $group => $perms)
                        <tr class="bg-100">
                            <td colspan="{{ count($roles) + 1 }}" class="fw-bold text-700 py-2">
                                <i class="fas fa-folder-open me-2 text-primary"></i> {{ $group }} Module
                            </td>
                        </tr>
                        @foreach($perms as $permission)
                            <tr>
                                <td class="align-middle ps-4">
                                    <span class="text-800 fw-medium">{{ $permission->name }}</span>
                                </td>
                                @foreach($roles as $role)
                                    <td class="text-center align-middle">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input cursor-pointer" type="checkbox" 
                                                wire:click="togglePermission({{ $role->id }}, '{{ $permission->name }}')"
                                                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                wire:loading.attr="disabled"
                                            >
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light py-2 text-center text-sm-start">
        <p class="mb-0 fs-10 text-600">
            <i class="fas fa-info-circle me-1"></i> Changes are saved automatically. Toggling a switch will immediately update the database.
        </p>
    </div>
</div>
