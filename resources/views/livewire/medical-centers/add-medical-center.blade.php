<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Add New Medical Center</h5>
    </div>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form wire:submit.prevent="save">
            {{-- Province Dropdown --}}
            <div class="mb-3">
                <label class="form-label">Province</label>
                <select class="form-select" wire:model.live="province_id">
                    <option value="">-- Select Province --</option>
                    @foreach($provinces as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
                @error('province_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            {{-- City Dropdown --}}
            @if(!empty($cities))
            <div class="mb-3">
                <label class="form-label">City</label>
                <select class="form-select" wire:model.live="city_id">
                    <option value="">-- Select City --</option>
                    @foreach($cities as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('city_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            @endif

            {{-- Circle Dropdown --}}
            @if(!empty($circles))
            <div class="mb-3">
                <label class="form-label">Circle</label>
                <select class="form-select" wire:model="circle_id">
                    <option value="">-- Select Circle --</option>
                    @foreach($circles as $circle)
                        <option value="{{ $circle->id }}">{{ $circle->name }}</option>
                    @endforeach
                </select>
                @error('circle_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            @endif

            {{-- Medical Center Name --}}
            <div class="mb-3">
                <label class="form-label">Medical Center Name</label>
                <input type="text" wire:model="name" class="form-control" placeholder="Enter medical center name">
                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            {{-- Location (optional) --}}
            <div class="mb-3">
                <label class="form-label">Location (optional)</label>
                <input type="text" wire:model="location" class="form-control" placeholder="Enter location">
                @error('location') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('medical-centers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Add Medical Center
                </button>
            </div>
        </form>
    </div>
</div>
