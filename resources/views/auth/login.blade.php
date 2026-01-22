<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-dark">Welcome Back</h2>
                            <p class="text-secondary small mt-1">Sign in to continue to Marketify</p>
                        </div>

                        <x-auth-session-status class="alert alert-success mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold text-secondary small">{{ __('Email') }}</label>
                                <input id="email" type="email" 
                                       class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" 
                                       required autofocus autocomplete="username" 
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
                                       required autocomplete="current-password" 
                                       placeholder="••••••••">

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                    <label class="form-check-label text-secondary small" for="remember_me">
                                        {{ __('Remember me') }}
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small fw-bold" href="{{ route('password.request') }}">
                                        {{ __('Forgot password?') }}
                                    </a>
                                @endif
                            </div>

                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary w-100 py-2 fs-6 fw-bold shadow-sm">
                                    {{ __('Log in') }}
                                </button>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <hr class="flex-grow-1 text-secondary">
                                <span class="mx-3 text-secondary small text-uppercase fw-semibold">Or</span>
                                <hr class="flex-grow-1 text-secondary">
                            </div>

                            <div class="text-center">
                                <p class="text-secondary small mb-2">Don't have an account?</p>
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100 py-2 fs-6 fw-bold">
                                    Create New Account
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>