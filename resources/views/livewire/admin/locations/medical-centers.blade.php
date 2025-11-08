<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Medical Centers</h4>
        <button class="btn btn-primary" wire:click="resetFields">Add New</button>
    </div>

    <form wire:submit.prevent="save" class="mb-4">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Center Name" wire:model="name">
            </div>
            <div class="col-md-6">
                <select class="form-select" wire:model="circle_id">
                    <option value="">Select Circle</option>
                    @foreach($circles as $circle)
                        <option value="{{ $circle->id }}">{{ $circle->name }} ({{ $circle->city->name }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button class="btn btn-success mt-3">Save</button>
    </form>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Circle</th>
                <th>City</th>
                <th>Province</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($centers as $center)
                <tr>
                    <td>{{ $center->name }}</td>
                    <td>{{ $center->circle->name }}</td>
                    <td>{{ $center->circle->city->name }}</td>
                    <td>{{ $center->circle->city->province->name }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" wire:click="edit({{ $center->id }})">Edit</button>
                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $center->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $centers->links() }}
</div>
