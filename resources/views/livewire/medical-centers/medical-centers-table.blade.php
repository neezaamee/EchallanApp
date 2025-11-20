<div class="container">
    <h2 class="mb-4">Medical Centers</h2>

    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'save' }}" class="card p-3 mb-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Circle</label>
                <select wire:model="circle_id" class="form-control">
                    <option value="">Select Circle</option>
                    @foreach($circles as $circle)
                        <option value="{{ $circle->id }}">{{ $circle->name }}</option>
                    @endforeach
                </select>
                @error('circle_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label>Name</label>
                <input type="text" wire:model="name" class="form-control">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label>Location</label>
                <input type="text" wire:model="location" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            {{ $isEditMode ? 'Update' : 'Add' }} Center
        </button>
        @if($isEditMode)
            <button type="button" wire:click="resetFields" class="btn btn-secondary">Cancel</button>
        @endif
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Circle</th>
                <th>Name</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($centers as $center)
                <tr>
                    <td>{{ $center->circle->name }}</td>
                    <td>{{ $center->name }}</td>
                    <td>{{ $center->location }}</td>
                    <td>
                        <button wire:click="edit({{ $center->id }})" class="btn btn-sm btn-warning">Edit</button>
                        <button wire:click="delete({{ $center->id }})" class="btn btn-sm btn-danger"
                            onclick="return confirm('Delete this center?')">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
