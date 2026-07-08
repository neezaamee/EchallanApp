<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public bool $confirmingUserDeletion = false;
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section>
    <p class="fs--2 text-muted mb-3">
        Once your account is deleted, all of its data will be permanently deleted. This action cannot be undone.
    </p>

    <x-falcon.button wire:click="$set('confirmingUserDeletion', true)" variant="danger" icon="fas fa-trash-alt" class="w-100">
        Delete Account
    </x-falcon.button>

    @if($confirmingUserDeletion)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.42); z-index: 1050;" role="dialog" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered">
                <form wire:submit="deleteUser" class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-danger py-2 px-3">
                        <h5 class="modal-title text-white">
                            <span class="fas fa-exclamation-triangle me-2"></span>Delete Account
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="$set('confirmingUserDeletion', false)"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-3 text-800 fs--1">
                            Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                        </p>
                        
                        <x-falcon.form-group label="Password" name="password" required="true">
                            <input type="password" wire:model="password" class="form-control shadow-none" placeholder="Enter password to confirm">
                        </x-falcon.form-group>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-falcon-default btn-sm" wire:click="$set('confirmingUserDeletion', false)">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash me-1"></i> Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</section>
