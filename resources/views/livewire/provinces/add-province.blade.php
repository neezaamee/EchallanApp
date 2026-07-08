<x-falcon.card title="Add New Province" bodyClass="p-4">
    <x-slot name="headerActions">
        <x-falcon.button href="{{ route('provinces.index') }}" variant="secondary" icon="fas fa-arrow-left">
            Back
        </x-falcon.button>
    </x-slot>

    @if (session()->has('message'))
        <x-falcon.alert variant="success">
            {{ session('message') }}
        </x-falcon.alert>
    @endif

    <form wire:submit.prevent="save">
        <x-falcon.form-group label="Province Name" name="name" required="true">
            <input type="text" wire:model="name" class="form-control shadow-none" placeholder="Enter province name">
        </x-falcon.form-group>

        <x-falcon.form-group label="Province Code" name="code" helpText="Used for identification (e.g., PB, SD).">
            <input type="text" wire:model="code" class="form-control shadow-none" placeholder="Optional code">
        </x-falcon.form-group>

        <div class="d-flex justify-content-end mt-4">
            <x-falcon.button type="submit" variant="success" icon="fas fa-save" loadingTarget="save">
                Add Province
            </x-falcon.button>
        </div>
    </form>
</x-falcon.card>
