@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-80px)] flex items-center justify-center px-4 py-12 bg-surface">
    <div class="w-full max-w-md space-y-8 bg-white p-8 rounded-[2rem] shadow-xl border border-outline-variant/20">

        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-bold tracking-tight text-on-surface">
                Welcome Back
            </h1>
            <p class="text-sm text-on-surface-variant">
                Log in to continue your English typing journey.
            </p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-on-surface-variant" for="email">
                        Email Address
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                        placeholder="name@example.com"
                        class="w-full px-4 py-3 bg-[#f8f6f6] border @error('email') border-error @else border-outline-variant @enderror rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all"
                    />
                    @error('email')
                        <p class="text-xs font-semibold text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-on-surface-variant" for="password">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                        class="w-full px-4 py-3 bg-[#f8f6f6] border @error('password') border-error @else border-outline-variant @enderror rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all"
                    />
                    @error('password')
                        <p class="text-xs font-semibold text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 text-on-surface-variant cursor-pointer select-none">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                           class="rounded text-primary focus:ring-primary border-outline-variant size-4">
                    <span>Remember Me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-primary hover:opacity-80 no-underline" href="{{ route('password.request') }}">
                        Forgot Password?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full bg-primary text-on-primary py-4 rounded-xl font-bold hover:opacity-90 transition shadow-md">
                Log In
            </button>
        </form>

    </div>
</div>
@endsection