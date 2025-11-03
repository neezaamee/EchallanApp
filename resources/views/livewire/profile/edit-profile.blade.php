<div class="container py-4">
    <h4 class="mb-3 fw-bold">Edit Profile</h4>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="updateProfile" class="card shadow-sm p-4">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" wire:model.defer="name">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">CNIC</label>
            <input type="text" class="form-control" wire:model.defer="cnic">
            @error('cnic') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <hr>

        <div class="mb-3">
            <label class="form-label">New Password (optional)</label>
            <input type="password" class="form-control" wire:model.defer="password">
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" wire:model.defer="password_confirmation">
        </div>

        <button class="btn btn-primary w-100" type="submit" wire:loading.attr="disabled">
            <span wire:loading.remove>Update Profile</span>
            <span wire:loading>Updating...</span>
        </button>
    </form>
</div>
