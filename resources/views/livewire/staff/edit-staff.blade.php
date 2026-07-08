<x-falcon.card title="Edit Staff" bodyClass="p-4">
    <x-slot name="headerActions">
        <x-falcon.button href="{{ route('staff.index') }}" variant="secondary" icon="fas fa-arrow-left">
            Back
        </x-falcon.button>
    </x-slot>

    @if (session()->has('message'))
        <x-falcon.alert variant="success">
            {{ session('message') }}
        </x-falcon.alert>
    @endif

    <form wire:submit.prevent="update">
        <div class="row">
            <div class="col-md-6">
                <x-falcon.form-group label="First Name" name="first_name" required="true">
                    <input type="text" wire:model="first_name" class="form-control shadow-none" placeholder="First Name">
                </x-falcon.form-group>
            </div>
            <div class="col-md-6">
                <x-falcon.form-group label="Last Name" name="last_name">
                    <input type="text" wire:model="last_name" class="form-control shadow-none" placeholder="Last Name">
                </x-falcon.form-group>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-falcon.form-group label="CNIC" name="cnic" required="true">
                    <input type="text" wire:model="cnic" class="form-control shadow-none" 
                        placeholder="_____________"
                        id="cnic_edit"
                        data-inputmask="'mask': '9999999999999', 'placeholder': '_'"
                        x-init="Inputmask().mask($el)"
                    >
                </x-falcon.form-group>
            </div>
            <div class="col-md-6">
                <x-falcon.form-group label="Email" name="email">
                    <input type="email" wire:model="email" class="form-control shadow-none" placeholder="Email">
                </x-falcon.form-group>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-falcon.form-group label="Phone" name="phone">
                    <input type="text" wire:model="phone" class="form-control shadow-none" 
                        placeholder="0__________"
                        id="phone_edit"
                        data-inputmask="'mask': '09999999999', 'placeholder': '_'"
                        x-init="Inputmask().mask($el)"
                    >
                </x-falcon.form-group>
            </div>
            <div class="col-md-6">
                <x-falcon.form-group label="Belt No" name="belt_no">
                    <input type="text" wire:model="belt_no" class="form-control shadow-none" placeholder="Belt No">
                </x-falcon.form-group>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-falcon.form-group label="Gender" name="gender">
                    <select class="form-select shadow-none" wire:model="gender">
                        <option value="">-- Select Gender --</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </x-falcon.form-group>
            </div>
            <div class="col-md-6">
                <x-falcon.form-group label="Status" name="status">
                    <select class="form-select shadow-none" wire:model="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                        <option value="retired">Retired</option>
                        <option value="transferred_out">Transferred Out</option>
                    </select>
                </x-falcon.form-group>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-falcon.form-group label="Rank" name="rank_id">
                    <select class="form-select shadow-none" wire:model="rank_id">
                        <option value="">-- Select Rank --</option>
                        @foreach($ranks as $rank)
                            <option value="{{ $rank->id }}">{{ $rank->name }}</option>
                        @endforeach
                    </select>
                </x-falcon.form-group>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <x-falcon.button type="submit" variant="primary" icon="fas fa-save" loadingTarget="update">
                Update Staff
            </x-falcon.button>
        </div>
    </form>
</x-falcon.card>
