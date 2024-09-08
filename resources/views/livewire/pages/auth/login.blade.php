<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate(
            [
                'form.email' => 'required|email',
                'form.password' => 'required',
            ],
            [
                'form.email.required' => 'Email tidak boleh kosong!',
                'form.email.email' => 'Format email tidak valid!',
                'form.password.required' => 'Password tidak boleh kosong!',
            ],
        );

        try {
            $this->form->authenticate();
        } catch (ValidationException $e) {
            $this->addError('form.email', 'Email atau kata sandi salah!');
            $this->addError('form.password', 'Email atau kata sandi salah!');
            return;
        }

        Session::regenerate();

        $this->redirectIntended(default: route('select-role', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form> --}}

    <div class="container full-height center-content overflow-hidden">
        <div class="row justify-content-center">
            <div class="col-xxl-8 col-lg-12">
                <div class="card overflow-hidden">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="d-flex flex-column h-100">
                                <div class="auth-brand p-4 text-center">
                                    <a href="index.html" class="logo-light">
                                        <img src="{{ asset('assets/images/logo2.png') }}" alt="logo" height="28">
                                    </a>
                                    <a href="index.html" class="logo-dark">
                                        <img src="{{ asset('assets/images/logo-dark2.png') }}" alt="dark logo"
                                            height="50">
                                    </a>
                                </div>
                                <div class="p-4 text-center">
                                    <h4 class="fs-20">Sign In</h4>
                                    <p class="text-muted mb-4">Enter your email address and password to <br> access
                                        account.
                                    </p>

                                    <!-- form -->
                                    <form action="#" class="text-start">
                                        <div class="mb-3">
                                            <label for="emailaddress" class="form-label">Email address</label>
                                            <input class="form-control @error('form.email') is-invalid @enderror"
                                                wire:model="form.email" id="email" type="email" required autofocus
                                                autocomplete="email" placeholder="Enter your email">
                                            <span class="invalid-feedback">{{ $errors->first('form.email') }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <a href="auth-forgotpw.html" class="text-muted float-end"><small>Forgot
                                                    your
                                                    password?</small></a>
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" wire:model="form.password" name="password"
                                                autocomplete="current-password" id="password"
                                                class="form-control @error('form.password') is-invalid @enderror @error('form.email') is-invalid @enderror"
                                                aria-describedby="signInPassword" placeholder="Enter password">
                                            <span class="invalid-feedback">{{ $errors->first('form.password') }}</span>
                                        </div>
                                        {{-- <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                                <label class="form-check-label" wire:model="form.remember"
                                                    id="remember" type="checkbox" for="checkbox-signin">Remember
                                                    me</label>
                                            </div>
                                        </div> --}}
                                        <div class="mb-0 text-start">
                                            <button class="btn btn-soft-primary w-100" wire:click.prevent='login'
                                                type="submit">
                                                <i class="ri-login-circle-fill me-1"></i>
                                                <span class="fw-bold">Log In</span>
                                                <span wire:loading class="ms-2" wire:target="login">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                    <!-- end form-->
                                </div>
                            </div>
                        </div> <!-- end col -->
                        <div class="col-lg-6 d-none d-lg-block">
                            <img src="{{ asset('assets/images/auth-img.jpg') }}" alt=""
                                class="img-fluid rounded h-100">
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
</div>
