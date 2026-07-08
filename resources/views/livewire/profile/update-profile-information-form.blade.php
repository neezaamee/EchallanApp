<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <form wire:submit="updateProfileInformation">
        <div class="row g-3 mb-3">
            <div class="col-12">
                <x-falcon.form-group label="Name" name="name" required="true">
                    @if (Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin'))
                        <input type="text" wire:model="name" class="form-control shadow-none" placeholder="Enter your name">
                    @else
                        <input type="text" wire:model="name" class="form-control shadow-none" readonly title="Name cannot be changed by citizen/officer roles.">
                    @endif
                </x-falcon.form-group>
            </div>
            
            <div class="col-12">
                <x-falcon.form-group label="Email" name="email" required="true" helpText="Note: Email is used as your login username.">
                    @if (Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin'))
                        <input type="email" wire:model="email" class="form-control shadow-none" placeholder="Enter email address">
                    @else
                        <input type="email" wire:model="email" class="form-control shadow-none" readonly title="Email cannot be changed by citizen/officer roles.">
                    @endif
                </x-falcon.form-group>
                
                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="mt-2 p-2 border border-warning rounded bg-warning-subtle">
                        <span class="text-warning-emphasis fs--1">
                            <i class="fas fa-exclamation-triangle me-1"></i> Your email address is unverified.
                        </span>
                        <button wire:click.prevent="sendVerification" class="btn btn-link btn-sm p-0 ms-1 fw-semi-bold">
                            Click here to re-send verification email.
                        </button>
                        
                        @if (session('status') === 'verification-link-sent')
                            <div class="text-success fs--2 mt-1">
                                <i class="fas fa-check-circle me-1"></i> A new verification link has been sent to your email address.
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-falcon.button type="submit" variant="primary" icon="fas fa-save" loadingTarget="updateProfileInformation">
                Save Changes
            </x-falcon.button>

            @if (session('status') === 'profile-updated')
                <span class="text-success fs--1" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)">
                    <i class="fas fa-check me-1"></i> Saved.
                </span>
            @endif
            
            <div x-data="{ show: false }" x-on:profile-updated.window="show = true; setTimeout(() => show = false, 2000)">
                <span class="text-success fs--1" x-show="show" style="display: none;">
                    <i class="fas fa-check me-1"></i> Saved successfully.
                </span>
            </div>
        </div>
    </form>
</section>
