<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Update Profile</h2>

    @if (session('message'))
        <div class="alert alert-success mb-3">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="updateProfile">
        <div class="mb-3">

            @if (Auth::user()->role == 'super_admin')
                <label class="form-label">Name</label>
                <input type="text" wire:model="name" class="form-control">
            @else
                <label class="form-label">Name (readonly)</label>
                <input type="text" wire:model="name" class="form-control" readonly>
            @endif

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email (readonly)</label>
            <input type="email" wire:model="email" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" wire:model="password" class="form-control">
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" wire:model="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
