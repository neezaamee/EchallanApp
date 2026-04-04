<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.main')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="row min-vh-100 flex-center g-0">
        <div class="col-lg-8 col-xxl-5 py-3 position-relative">
            <img class="bg-auth-circle-shape" src="{{ asset('assets/img/icons/spot-illustrations/bg-shape.png') }}" alt="" width="250">
            <img class="bg-auth-circle-shape-2" src="{{ asset('assets/img/icons/spot-illustrations/shape-1.png') }}" alt="" width="150">
            
            <div class="card overflow-hidden z-1">
                <div class="card-body p-0">
                    <div class="row g-0 h-100">
                        <div class="col-md-5 text-center bg-card-gradient">
                            <div class="position-relative p-4 pt-md-5 pb-md-7" data-bs-theme="light">
                                <div class="bg-holder bg-auth-card-shape" style="background-image:url({{ asset('assets/img/icons/spot-illustrations/half-circle.png') }});"></div>
                                @include('auth.components.auth-card-heading-sub-heading')
                            </div>
                            <div class="mt-3 mb-4 mt-md-4 mb-md-5" data-bs-theme="light">
                                <p class="mb-0 mt-4 mt-md-5 fs-10 fw-semi-bold text-white opacity-75">Read our <a class="text-decoration-underline text-white" href="#!">terms</a> and <a class="text-decoration-underline text-white" href="#!">conditions </a></p>
                            </div>
                        </div>
                        <div class="col-md-7 d-flex flex-center">
                            <div class="p-4 p-md-5 flex-grow-1">
                                <div class="text-center text-md-start">
                                    <h4 class="mb-0"> Forgot your password?</h4>
                                    <p class="mb-4">Enter your email and we'll send you a reset link.</p>
                                </div>
                                
                                <!-- Session Status -->
                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                <div class="row justify-content-center">
                                    <div class="col-sm-8 col-md">
                                        <form wire:submit="sendPasswordResetLink" class="mb-3">
                                            <input wire:model="email" class="form-control @error('email') is-invalid @enderror" type="email" placeholder="Email address" required autofocus />
                                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            
                                            <button class="btn btn-primary d-block w-100 mt-3" type="submit">
                                                Send Reset Link
                                            </button>
                                        </form>
                                        <a class="fs-10 text-600" href="{{ route('login') }}">Back to Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
