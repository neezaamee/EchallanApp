<x-falcon.card title="Interactive Roles & Permissions Matrix" bodyClass="p-0">
    <x-slot name="headerActions">
        <div class="d-flex align-items-center">
            <div wire:loading class="spinner-border spinner-border-sm text-primary me-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <x-falcon.button href="{{ route('roles.index') }}" variant="falcon-default" icon="fas fa-list">
                Manage Roles
            </x-falcon.button>
            <x-falcon.button href="{{ route('permissions.index') }}" variant="falcon-default" class="ms-2" icon="fas fa-key">
                Manage Permissions
            </x-falcon.button>
        </div>
    </x-slot>

    @if (session()->has('message'))
        <x-falcon.alert variant="success">
            {{ session('message') }}
        </x-falcon.alert>
    @endif
    
    @if (session()->has('error'))
        <x-falcon.alert variant="danger">
            {{ session('error') }}
        </x-falcon.alert>
    @endif

    <div class="table-responsive scrollbar" style="max-height: 80vh;">
        <table class="table table-sm table-hover table-bordered table-striped align-middle fs--1 mb-0">
            <thead class="bg-200 text-900">
                <tr>
                    <th class="white-space-nowrap align-middle ps-3" style="min-width: 250px; position: sticky; top: 0; z-index: 10; background-color: var(--falcon-200, #edf2f9);">Permissions</th>
                    @foreach($roles as $role)
                        <th class="text-center align-middle" style="position: sticky; top: 0; z-index: 10; background-color: var(--falcon-200, #edf2f9);">
                            <x-falcon.badge variant="primary">
                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                            </x-falcon.badge>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($groupedPermissions as $group => $perms)
                    <tr class="bg-100">
                        <td colspan="{{ count($roles) + 1 }}" class="fw-bold text-700 py-2 ps-3">
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
                                        <input class="form-check-input cursor-pointer shadow-none" type="checkbox" 
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

    <x-slot name="footer">
        <p class="mb-0 fs--2 text-600 py-1">
            <i class="fas fa-info-circle me-1"></i> Changes are saved automatically. Toggling a switch will immediately update the database.
        </p>
    </x-slot>
</x-falcon.card>
