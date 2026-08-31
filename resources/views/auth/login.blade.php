@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="min-h-[calc(100vh-80px)] max-w-container-max mx-auto w-full grid lg:grid-cols-2 items-center gap-12 px-margin-mobile md:px-margin-desktop py-12">

    {{-- 左：オリジナルイラスト + ひとこと --}}
    <div class="hidden lg:block">
        <span class="kicker">Welcome back</span>
        <h2 class="font-display text-headline-lg font-bold text-on-surface mt-3 mb-4 leading-snug">
            今日も<span class="hl">コツコツ</span>、<br>続きを進めていきましょう。
        </h2>
        <p class="text-body-md text-on-surface-variant max-w-md leading-relaxed mb-8">
            少しずつでも大丈夫。続けた分だけ、英語は必ず力になります。
        </p>
        <div class="frame-card shadow-biscuit max-w-md">
            <div class="overflow-hidden">
                @include('components.illustrations.study-desk')
            </div>
        </div>
    </div>

    {{-- 右：ログインフォーム --}}
    <div class="w-full max-w-md mx-auto lg:mx-0">
        <div class="bg-surface-container-lowest p-8 rounded-2xl shadow-biscuit border border-outline-variant">

            <div class="space-y-1.5 mb-8">
                <h1 class="font-display text-2xl font-bold tracking-tight text-on-surface">ログイン</h1>
                <p class="text-caption text-on-surface-variant">
                    ログインして、英語学習を続けましょう。
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="block text-caption font-bold text-on-surface-variant" for="email">メールアドレス</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                            placeholder="name@example.com"
                            class="w-full px-4 py-3 bg-surface-container-low border @error('email') border-error @else border-outline-variant @enderror rounded-xl focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition-all" />
                        @error('email') <p class="text-xs font-semibold text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-caption font-bold text-on-surface-variant" for="password">パスワード</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="パスワードを入力"
                            class="w-full px-4 py-3 bg-surface-container-low border @error('password') border-error @else border-outline-variant @enderror rounded-xl focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition-all" />
                        @error('password') <p class="text-xs font-semibold text-error mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between text-caption">
                    <label class="flex items-center gap-2 text-on-surface-variant cursor-pointer select-none">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                               class="rounded text-primary focus:ring-primary/40 border-outline-variant size-4">
                        <span>ログイン状態を保持する</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="font-bold text-primary hover:opacity-80 no-underline" href="{{ route('password.request') }}">
                            パスワードをお忘れですか？
                        </a>
                    @endif
                </div>

                <button type="submit" class="biscuit-btn w-full text-label-md">ログイン</button>
            </form>

            @if (Route::has('register'))
            <p class="text-caption text-on-surface-variant text-center mt-6">
                アカウントをお持ちでない方は
                <a href="{{ route('register') }}" class="font-bold text-primary no-underline hover:opacity-80">新規登録</a>
            </p>
            @endif
        </div>
    </div>

</div>
@endsection
