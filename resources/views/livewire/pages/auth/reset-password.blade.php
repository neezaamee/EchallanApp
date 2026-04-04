<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.main')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
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
                                <h3>Reset password</h3>
                                
                                <form wire:submit="resetPassword" class="mt-3">
                                    <!-- Email Address -->
                                    <div class="mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input wire:model="email" class="form-control @error('email') is-invalid @enderror" type="email" placeholder="Email address" required readonly />
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label class="form-label" for="password">New Password</label>
                                        <input wire:model="password" class="form-control @error('password') is-invalid @enderror" type="password" id="password" required />
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="mb-3">
                                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                                        <input wire:model="password_confirmation" class="form-control" type="password" id="password_confirmation" required />
                                    </div>

                                    <button class="btn btn-primary d-block w-100 mt-3" type="submit">Set Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
