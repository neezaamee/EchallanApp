<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <form wire:submit="updatePassword">
        <div class="row g-3 mb-3">
            <div class="col-12">
                <x-falcon.form-group label="Current Password" name="current_password" required="true">
                    <input type="password" wire:model="current_password" id="update_password_current_password" class="form-control shadow-none" placeholder="Enter current password">
                </x-falcon.form-group>
            </div>

            <div class="col-12">
                <x-falcon.form-group label="New Password" name="password" required="true" helpText="Password must be at least 8 characters long.">
                    <input type="password" wire:model="password" id="update_password_password" class="form-control shadow-none" placeholder="Enter new password">
                </x-falcon.form-group>
            </div>

            <div class="col-12">
                <x-falcon.form-group label="Confirm New Password" name="password_confirmation" required="true">
                    <input type="password" wire:model="password_confirmation" id="update_password_password_confirmation" class="form-control shadow-none" placeholder="Re-type new password">
                </x-falcon.form-group>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-falcon.button type="submit" variant="primary" icon="fas fa-key" loadingTarget="updatePassword">
                Change Password
            </x-falcon.button>

            @if (session('status') === 'password-updated')
                <span class="text-success fs--1" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)">
                    <i class="fas fa-check me-1"></i> Password updated.
                </span>
            @endif
            
            <div x-data="{ show: false }" x-on:password-updated.window="show = true; setTimeout(() => show = false, 2000)">
                <span class="text-success fs--1" x-show="show" style="display: none;">
                    <i class="fas fa-check me-1"></i> Password updated successfully.
                </span>
            </div>
        </div>
    </form>
</section>
