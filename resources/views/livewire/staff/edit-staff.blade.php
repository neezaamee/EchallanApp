<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Edit Staff</h5>
    </div>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form wire:submit.prevent="update">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" wire:model="first_name" class="form-control" placeholder="First Name">
                    @error('first_name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" wire:model="last_name" class="form-control" placeholder="Last Name">
                    @error('last_name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">CNIC <span class="text-danger">*</span></label>
                    <input type="text" wire:model="cnic" class="form-control" placeholder="CNIC">
                    @error('cnic') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" wire:model="email" class="form-control" placeholder="Email">
                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" wire:model="phone" class="form-control" placeholder="Phone">
                    @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Belt No</label>
                    <input type="text" wire:model="belt_no" class="form-control" placeholder="Belt No">
                    @error('belt_no') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gender</label>
                    <select class="form-select" wire:model="gender">
                        <option value="">-- Select Gender --</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    @error('gender') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" wire:model="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                        <option value="retired">Retired</option>
                        <option value="transferred_out">Transferred Out</option>
                    </select>
                    @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Rank</label>
                    <select class="form-select" wire:model="rank_id">
                        <option value="">-- Select Rank --</option>
                        @foreach($ranks as $rank)
                            <option value="{{ $rank->id }}">{{ $rank->name }}</option>
                        @endforeach
                    </select>
                    @error('rank_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Staff
                </button>
            </div>
        </form>
    </div>
</div>
