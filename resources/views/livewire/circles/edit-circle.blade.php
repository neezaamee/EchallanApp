<div>
    <div class="mb-3">
        <label for="city_id" class="form-label">City</label>
        <select wire:model="city_id" id="city_id" class="form-control select2 city-select">
            <option value="">Select City</option>
            @foreach ($cities as $city)
                <option value="{{ $city->id }}" {{ $city->id == $city_id ? 'selected' : '' }}>
                    {{ $city->name }} — {{ strtoupper($city->province->slug ?? '') }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="name" class="form-label">Circle Name</label>
        <input wire:model="name" type="text" id="name" class="form-control">
    </div>

    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input wire:model="slug" type="text" id="slug" class="form-control">
    </div>

    <button wire:click="update" class="btn btn-primary">Update Circle</button>
</div>

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
