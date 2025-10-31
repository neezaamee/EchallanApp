<div>
    <h2 class="mb-3">Edit City</h2>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="updateCity" class="card p-4 shadow-sm">

        <div class="mb-3">
            <label class="form-label">City Name</label>
            <input type="text" class="form-control" wire:model.live="name" placeholder="Enter city name">
            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" class="form-control" wire:model.live="slug" placeholder="Optional slug">
            @error('slug') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Province</label>
            <select class="form-select" wire:model.live="province_id">
                <option value="">Select Province</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                @endforeach
            </select>
            @error('province_id') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('cities.index') }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Update City</button>
        </div>
    </form>
</div>
