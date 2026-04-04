<div class="card mb-3">
    <div class="card-header bg-light">
        <div class="row flex-between-center">
            <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Role Management</h5>
            </div>
            <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                <div id="table-simple-pagination-actions">
                    @can('roles:create')
                        <a href="{{ route('roles.create') }}" class="btn btn-falcon-default btn-sm" type="button">
                            <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                            <span class="d-none d-sm-inline-block ms-1">New Role</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="p-3 border-bottom bg-light">
            <div class="row justify-content-between align-items-center">
                <div class="col-sm-auto">
                    <div class="position-relative">
                        <input class="form-control form-control-sm shadow-none search" type="search" placeholder="Search roles..." aria-label="search" wire:model.live.debounce.500ms="search" />
                        <span class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-400"></span>
                    </div>
                </div>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success border-2 d-flex align-items-center p-2 mb-0" role="alert">
                <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-3"></span></div>
                <p class="mb-0 flex-1 text-800">{{ session('message') }}</p>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="alert alert-danger border-2 d-flex align-items-center p-2 mb-0" role="alert">
                <div class="bg-danger me-3 icon-item"><span class="fas fa-times-circle text-white fs-3"></span></div>
                <p class="mb-0 flex-1 text-800">{{ session('error') }}</p>
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive scrollbar">
            <table class="table table-sm table-striped table-hover align-middle mb-0 fs--1">
                <thead class="bg-200 text-900">
                    <tr>
                        <th wire:click="sortBy('id')" style="cursor: pointer; width: 50px;" class="ps-3">
                            # @if($sortField === 'id') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                        </th>
                        <th wire:click="sortBy('name')" style="cursor: pointer;">
                            Role Name @if($sortField === 'name') <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i> @endif
                        </th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @forelse ($roles as $role)
                        <tr>
                            <td class="text-muted ps-3">#{{ $role->id }}</td>
                            <td class="fw-bold text-dark">{{ ucwords(str_replace(['_', '-'], ' ', $role->name)) }}</td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('roles.show', $role->id) }}" class="btn btn-link p-0 text-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($role->name !== 'super_admin')
                                        <a href="{{ route('roles.impersonate', $role->id) }}" class="btn btn-link p-0 text-warning ms-2" title="Impersonate Role">
                                            <i class="fas fa-user-secret"></i>
                                        </a>
                                    @endif
                                    
                                    @can('roles:edit')
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-link p-0 text-primary ms-2" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('roles:delete')
                                        @if($role->name !== 'super_admin' && $role->name !== 'admin')
                                            <button type="button" class="btn btn-link p-0 text-danger ms-2" wire:click.prevent="confirmDelete({{ $role->id }})" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center p-4 text-muted">No roles found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light py-2">
        <div class="d-flex justify-content-end">
            {{ $roles->links() }}
        </div>
    </div>

    @if ($confirmingRoleDeletion)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title text-white"><span class="fas fa-exclamation-triangle me-2"></span>Confirm Deletion</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="$set('confirmingRoleDeletion', null)"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-0">Are you sure you want to <strong>permanently delete</strong> this role? This might affect users assigned to it.</p>
                    </div>
                    <div class="modal-footer bg-light">
                        <button class="btn btn-falcon-default btn-sm" wire:click="$set('confirmingRoleDeletion', null)">Cancel</button>
                        <button class="btn btn-danger btn-sm" wire:click="deleteRole">Delete Permanently</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
