<x-falcon.card title="Add New Circle" bodyClass="p-4">
    <x-slot name="headerActions">
        <x-falcon.button href="{{ route('circles.index') }}" variant="secondary" icon="fas fa-arrow-left">
            Back
        </x-falcon.button>
    </x-slot>

    @if (session()->has('message'))
        <x-falcon.alert variant="success">
            {{ session('message') }}
        </x-falcon.alert>
    @endif

    <form wire:submit.prevent="save">
        <x-falcon.form-group label="Circle Name" name="name" required="true">
            <input type="text" wire:model="name" class="form-control shadow-none" placeholder="Enter circle name">
        </x-falcon.form-group>

        <x-falcon.form-group label="Slug" name="slug" helpText="Used for URL representation of the circle.">
            <input type="text" wire:model="slug" class="form-control shadow-none" placeholder="Optional slug">
        </x-falcon.form-group>

        <x-falcon.form-group label="City" name="city_id" required="true">
            <select class="form-select shadow-none" wire:model="city_id">
                <option value="">-- Select City --</option>
                @foreach($cities as $c)
                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->province->name ?? '—' }})</option>
                @endforeach
            </select>
        </x-falcon.form-group>

        <div class="d-flex justify-content-end mt-4">
            <x-falcon.button type="submit" variant="success" icon="fas fa-save" loadingTarget="save">
                Add Circle
            </x-falcon.button>
        </div>
    </form>
</x-falcon.card>
