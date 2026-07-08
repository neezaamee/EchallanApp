<x-falcon.card title="Edit Dumping Point" bodyClass="p-4">
    <x-slot name="headerActions">
        <x-falcon.button href="{{ route('dumping-points.index') }}" variant="secondary" icon="fas fa-arrow-left">
            Back
        </x-falcon.button>
    </x-slot>

    @if (session()->has('message'))
        <x-falcon.alert variant="success">
            {{ session('message') }}
        </x-falcon.alert>
    @endif

    <form wire:submit.prevent="update">
        <x-falcon.form-group label="Province" name="province_id" required="true">
            <select class="form-select shadow-none" wire:model.live="province_id">
                <option value="">-- Select Province --</option>
                @foreach($provinces as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </x-falcon.form-group>

        <x-falcon.form-group label="City" name="city_id" required="true">
            <select class="form-select shadow-none" wire:model.live="city_id">
                <option value="">-- Select City --</option>
                @foreach($cities as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </x-falcon.form-group>

        <x-falcon.form-group label="Circle" name="circle_id" required="true">
            <select class="form-select shadow-none" wire:model="circle_id">
                <option value="">-- Select Circle --</option>
                @foreach($circles as $circle)
                    <option value="{{ $circle->id }}">{{ $circle->name }}</option>
                @endforeach
            </select>
        </x-falcon.form-group>

        <x-falcon.form-group label="Dumping Point Name" name="name" required="true">
            <input type="text" wire:model="name" class="form-control shadow-none" placeholder="Enter dumping point name">
        </x-falcon.form-group>

        <x-falcon.form-group label="Location (optional)" name="location">
            <input type="text" wire:model="location" class="form-control shadow-none" placeholder="Enter location">
        </x-falcon.form-group>

        <div class="d-flex justify-content-end mt-4">
            <x-falcon.button type="submit" variant="primary" icon="fas fa-save" loadingTarget="update">
                Update Dumping Point
            </x-falcon.button>
        </div>
    </form>
</x-falcon.card>
