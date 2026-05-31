@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-80px)] flex flex-col md:flex-row max-w-7xl mx-auto w-full bg-surface">

    <div class="hidden md:flex md:w-1/2 p-10 items-center justify-center">
        <div class="relative w-full max-w-md aspect-square rounded-full overflow-hidden border border-outline-variant/30 shadow-lg">
            <img
              class="w-full h-full object-cover"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuAymBPEhE-V1VVSocu23laj4AMSzojWQ_sS8Sn7cV9_hGdccRltr4PbLdvkm2E6PyW9ktQXSyymJo4ifzWs1O6OsyhYYe2rSvW0fFnOb7_g8EiF4EnyAlR7bFwQSUFpjjd6kUH29Co4Asd5Sg5eH4g-tDUsfq0jKfQBWMzhrtfIU2HKsTvrav0VmgA65HJvN6yVuTldXqJv4Iu0Xs4K73gPvPnFtMPS3ysGqkh_9ytqBdN1zsbDvAvljcauMyf7dEEyDYv_Z7JkrfQ"
              alt="learning"
            />
            <div class="absolute inset-0 bg-gradient-to-tr from-primary/20 to-transparent"></div>
        </div>
    </div>

    <div class="w-full md:w-1/2 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md space-y-8 bg-white p-8 rounded-[2rem] shadow-xl border border-outline-variant/20">

            <div class="space-y-2">
              <h1 class="text-3xl font-bold tracking-tight text-on-surface">
                Start Your Journey
              </h1>
              <p class="text-sm text-on-surface-variant">
                Access all features for free. <span class="text-primary font-semibold">Free forever.</span>
              </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-on-surface-variant" for="name">
                            Full Name
                        </label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autocomplete="name"
                            autofocus
                            placeholder="Enter your full name"
                            class="w-full px-4 py-3 bg-[#f8f6f6] border @error('name') border-error @else border-outline-variant @enderror rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all"
                        />
                        @error('name')
                            <p class="text-xs font-semibold text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

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
                            autocomplete="new-password"
                            placeholder="Create a strong password"
                            class="w-full px-4 py-3 bg-[#f8f6f6] border @error('password') border-error @else border-outline-variant @enderror rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all"
                        />
                        @error('password')
                            <p class="text-xs font-semibold text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-on-surface-variant" for="password-confirm">
                            Confirm Password
                        </label>
                        <input
                            id="password-confirm"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Retype your password"
                            class="w-full px-4 py-3 bg-[#f8f6f6] border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all"
                        />
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-on-primary py-4 rounded-xl font-bold hover:opacity-90 transition shadow-md">
                    Create Account
                </button>
            </form>

        </div>
    </div>
</div>
@endsection