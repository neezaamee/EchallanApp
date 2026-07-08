<x-falcon.card title="Edit Sector" bodyClass="p-4">
    <x-slot name="headerActions">
        <x-falcon.button href="{{ route('sectors.index') }}" variant="secondary" icon="fas fa-arrow-left">
            Back
        </x-falcon.button>
    </x-slot>

    @if (session()->has('message'))
        <x-falcon.alert variant="success">
            {{ session('message') }}
        </x-falcon.alert>
    @endif

    <form wire:submit.prevent="update">
        <x-falcon.form-group label="Sector Name" name="name" required="true">
            <input type="text" wire:model.blur="name" class="form-control shadow-none" id="name" placeholder="Enter sector name">
        </x-falcon.form-group>

        <x-falcon.form-group label="Slug" name="slug" helpText="Used for URL representation of the sector.">
            <input type="text" wire:model="slug" class="form-control shadow-none" id="slug" placeholder="Optional slug">
        </x-falcon.form-group>

        <x-falcon.form-group label="Circle" name="circle_id" required="true">
            <select class="form-select shadow-none" wire:model="circle_id" id="circle_id">
                <option value="">-- Select Circle --</option>
                @foreach($circles as $circle)
                    <option value="{{ $circle->id }}">{{ $circle->name }} ({{ $circle->city->name ?? '—' }})</option>
                @endforeach
            </select>
        </x-falcon.form-group>

        <div class="d-flex justify-content-end mt-4">
            <x-falcon.button type="submit" variant="primary" icon="fas fa-save" loadingTarget="update">
                Update Sector
            </x-falcon.button>
        </div>
    </form>
</x-falcon.card>
