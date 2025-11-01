<div>
    <div class="mb-3">
        <label for="city_id" class="form-label">City</label>
        <select wire:model="city_id" id="city_id" class="form-control city-select">
            <option value="">Select City</option>
            @foreach ($cities as $city)
                <option value="{{ $city->id }}">
                    {{ $city->name }} — {{ strtoupper($city->province->code ?? '') }}
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
        document.addEventListener('livewire:navigated', () => {
            $('.city-select').select2({
                placeholder: 'Search City...',
                allowClear: true,
                width: '100%'
            });

            $('.city-select').on('change', function(e) {
                Livewire.dispatch('setCity', {
                    cityId: $(this).val()
                });
            });
        });
    </script>
@endpush
