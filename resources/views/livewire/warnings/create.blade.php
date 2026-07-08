<x-falcon.card title="Issue Warning" description="Use this form to issue a non-monetary warning to a violator." bodyClass="p-4">
    <x-slot name="headerActions">
        <x-falcon.button href="{{ route('warnings.index') }}" variant="secondary" icon="fas fa-arrow-left">
            Back
        </x-falcon.button>
    </x-slot>

    @if (session()->has('message'))
        <x-falcon.alert variant="success">
            {{ session('message') }}
        </x-falcon.alert>
    @endif

    @livewire('violator-history')

    <form wire:submit.prevent="save" class="mt-4">
        <div class="row">
            <!-- Violator Name -->
            <div class="col-sm-6">
                <x-falcon.form-group label="Violator Name" name="violator_name" required="true">
                    <input type="text" wire:model="violator_name" id="violator_name" class="form-control shadow-none" placeholder="Enter name">
                </x-falcon.form-group>
            </div>

            <!-- Violator CNIC -->
            <div class="col-sm-6">
                <x-falcon.form-group label="Violator CNIC" name="violator_cnic" required="true">
                    <input type="text" wire:model.live.debounce.500ms="violator_cnic" id="violator_cnic" class="form-control shadow-none" placeholder="12345-1234567-1">
                </x-falcon.form-group>
            </div>

            <!-- Mobile -->
            <div class="col-sm-6">
                <x-falcon.form-group label="Mobile Number (Optional)" name="violator_mobile">
                    <input type="text" wire:model="violator_mobile" id="violator_mobile" class="form-control shadow-none" placeholder="Enter mobile number">
                </x-falcon.form-group>
            </div>

            <!-- Vehicle Type -->
            <div class="col-sm-6">
                <x-falcon.form-group label="Vehicle Type" name="vehicle_type" required="true">
                    <select wire:model="vehicle_type" id="vehicle_type" class="form-select shadow-none">
                        <option value="">Select Type</option>
                        <option value="motorcycle">Motorcycle</option>
                        <option value="car">Car</option>
                        <option value="jeep">Jeep</option>
                        <option value="bus">Bus</option>
                        <option value="truck">Truck</option>
                    </select>
                </x-falcon.form-group>
            </div>

            <!-- Vehicle Number -->
            <div class="col-sm-6">
                <x-falcon.form-group label="Vehicle Number" name="vehicle_number" required="true">
                    <input type="text" wire:model.live.debounce.500ms="vehicle_number" id="vehicle_number" class="form-control shadow-none" placeholder="e.g. ABC-123">
                </x-falcon.form-group>
            </div>

            <!-- Violation Name -->
            <div class="col-sm-6">
                <x-falcon.form-group label="Violation Type" name="violation_name" required="true">
                    <input type="text" wire:model="violation_name" id="violation_name" class="form-control shadow-none" placeholder="e.g. Over speeding">
                </x-falcon.form-group>
            </div>

            <!-- Location -->
            <div class="col-12">
                <x-falcon.form-group label="Location Details" name="location" required="true">
                    <input type="text" wire:model="location" id="location" class="form-control shadow-none" placeholder="Enter location of violation">
                </x-falcon.form-group>
            </div>

            <!-- Remarks -->
            <div class="col-12">
                <x-falcon.form-group label="Additional Remarks" name="remarks">
                    <textarea wire:model="remarks" id="remarks" rows="3" class="form-control shadow-none" placeholder="Any additional notes..."></textarea>
                </x-falcon.form-group>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <x-falcon.button type="submit" variant="primary" icon="fas fa-save" loadingTarget="save">
                Issue Warning
            </x-falcon.button>
        </div>
    </form>
</x-falcon.card>
