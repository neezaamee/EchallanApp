<x-falcon.card title="Edit Circle" bodyClass="p-4">
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

    <div>
        <x-falcon.form-group label="City" name="city_id" required="true">
            <select wire:model="city_id" id="city_id" class="form-control select2 city-select shadow-none">
                <option value="">Select City</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}" {{ $city->id == $city_id ? 'selected' : '' }}>
                        {{ $city->name }} — {{ strtoupper($city->province->slug ?? '') }}
                    </option>
                @endforeach
            </select>
        </x-falcon.form-group>

        <x-falcon.form-group label="Circle Name" name="name" required="true">
            <input wire:model="name" type="text" id="name" class="form-control shadow-none">
        </x-falcon.form-group>

        <x-falcon.form-group label="Slug" name="slug">
            <input wire:model="slug" type="text" id="slug" class="form-control shadow-none">
        </x-falcon.form-group>

        <div class="d-flex justify-content-end mt-4">
            <x-falcon.button wire:click="update" variant="primary" icon="fas fa-save" loadingTarget="update">
                Update Circle
            </x-falcon.button>
        </div>
    </div>
</x-falcon.card>

@push('scripts')
<script>
document.addEventListener('livewire:initialized', function () {
    // Initialize Select2
    $('.city-select').select2({
        placeholder: 'Search City...',
        allowClear: true,
        width: '100%'
    });

    // Set initial value
    $('.city-select').val(@this.city_id).trigger('change');

    // Update Livewire when Select2 changes
    $('.city-select').on('change', function(e) {
        @this.set('city_id', $(this).val());
    });

    // Update Select2 when Livewire updates the value
    Livewire.on('cityUpdated', (cityId) => {
        $('.city-select').val(cityId).trigger('change');
    });
});
</script>
@endpush
