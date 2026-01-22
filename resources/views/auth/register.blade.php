<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
        <p class="text-sm text-gray-600 mt-1">Join Marketify to start shopping</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full p-2.5" 
                            type="text" name="name" :value="old('name')" 
                            required autofocus autocomplete="name" 
                            placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full p-2.5" 
                            type="email" name="email" :value="old('email')" 
                            required autocomplete="username" 
                            placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full p-2.5"
                            type="password"
                            name="password"
                            required autocomplete="new-password" 
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full p-2.5"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" 
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 text-base">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <div class="relative flex py-5 items-center">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="flex-shrink-0 mx-4 text-gray-400 text-xs uppercase">Or</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <div class="text-center">
            <p class="text-sm text-gray-600 mb-3">Already have an account?</p>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Log In
            </a>
        </div>
    </form>
</x-guest-layout>