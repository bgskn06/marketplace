<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-dark">Create Account</h2>
                            <p class="text-secondary small mt-1">Join Marketify to start shopping</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold text-secondary small">{{ __('Full Name') }}</label>
                                <input id="name" type="text" 
                                       class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" 
                                       required autofocus autocomplete="name" 
                                       placeholder="John Doe">
                                
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold text-secondary small">{{ __('Email') }}</label>
                                <input id="email" type="email" 
                                       class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" 
                                       required autocomplete="username" 
                                       placeholder="name@example.com">
                                
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold text-secondary small">{{ __('Password') }}</label>
                                <input id="password" type="password" 
                                       class="form-control form-control-lg fs-6 @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required autocomplete="new-password" 
                                       placeholder="••••••••">
                                
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold text-secondary small">{{ __('Confirm Password') }}</label>
                                <input id="password_confirmation" type="password" 
                                       class="form-control form-control-lg fs-6 @error('password_confirmation') is-invalid @enderror" 
                                       name="password_confirmation" 
                                       required autocomplete="new-password" 
                                       placeholder="••••••••">
                                
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary w-100 py-2 fs-6 fw-bold shadow-sm">
                                    {{ __('Register') }}
                                </button>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <hr class="flex-grow-1 text-secondary">
                                <span class="mx-3 text-secondary small text-uppercase fw-semibold">Or</span>
                                <hr class="flex-grow-1 text-secondary">
                            </div>

                            <div class="text-center">
                                <p class="text-secondary small mb-2">Already have an account?</p>
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 py-2 fs-6 fw-bold">
                                    Log In
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>