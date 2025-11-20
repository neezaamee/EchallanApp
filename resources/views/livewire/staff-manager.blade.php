<div>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Staff Management</h3>
    <div>
      <button class="btn btn-primary" wire:click="openCreateModal">Add New Staff</button>
    </div>
  </div>

  {{-- Search & perPage --}}
  <div class="row mb-3">
    <div class="col-md-4">
      <input wire:model.debounce.300ms="search" class="form-control" placeholder="Search name / CNIC / email">
    </div>
    <div class="col-md-2 ms-auto">
      <select wire:model="perPage" class="form-select">
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="25">25</option>
      </select>
    </div>
  </div>

  {{-- flash messages --}}
  @if(session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session()->has('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>CNIC</th>
          <th>Email</th>
          <th>Department</th>
          <th>Designation</th>
          <th>Posting</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>


{{--         @forelse($staff as $s)
          <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->name }}</td>
            <td>{{ $s->cnic }}</td>
            <td>{{ $s->email }}</td>
            <td>{{ $s->department }}</td>
            <td>{{ $s->designation }}</td>
            <td>{{ $s->current_posting }}</td>
            <td>{{ $s->roleName() ?? '-' }}</td>
            <td style="white-space:nowrap">
              <button class="btn btn-sm btn-info" wire:click="openEditModal({{ $s->id }})">Edit</button>

              <button class="btn btn-sm btn-danger ms-1" wire:click="confirmDelete({{ $s->id }})">Delete</button>
            </td>
          </tr>
        @empty
          <tr><td colspan="9" class="text-center">No staff found.</td></tr>
        @endforelse --}}
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{-- {{ $staff->links() }} --}}
  </div>


  {{-- Scripts to control modals via browser events --}}
  @push('scripts')
  <script>
    window.addEventListener('show-staff-modal', event => {
      var modal = new bootstrap.Modal(document.getElementById('staffModal'));
      modal.show();
    });
    window.addEventListener('hide-staff-modal', event => {
      var modal = bootstrap.Modal.getInstance(document.getElementById('staffModal'));
      if (modal) modal.hide();
    });
    window.addEventListener('show-delete-confirm', event => {
      var modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
      modal.show();
    });
    window.addEventListener('hide-delete-confirm', event => {
      var modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
      if (modal) modal.hide();
    });
  </script>
  @endpush
</div>
