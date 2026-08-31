@extends('layouts.app')

@section('title', '新規登録')

@section('content')
<div class="min-h-[calc(100vh-80px)] max-w-container-max mx-auto w-full grid lg:grid-cols-2 items-center gap-12 px-margin-mobile md:px-margin-desktop py-12">

    {{-- 左：オリジナルイラスト + ひとこと --}}
    <div class="hidden lg:block">
        <span class="kicker">Study English, a little every day</span>
        <h2 class="font-display text-headline-lg font-bold text-on-surface mt-3 mb-4 leading-snug">
            <span class="hl">毎日少しずつ</span>、<br>続ける英語学習をはじめよう。
        </h2>
        <p class="text-body-md text-on-surface-variant max-w-md leading-relaxed mb-6">
            すべての機能を無料でご利用いただけます。単語・文法・Part別のコツを、毎日すこしずつ。
        </p>
        <ul class="space-y-2 mb-8">
            @foreach(['TOEIC Part 1〜7 の実践問題', 'IELTS スピーキング練習', 'フラッシュカードで語彙強化', '学習の進捗と連続日数を記録'] as $feat)
            <li class="flex items-center gap-2.5 text-body-md text-on-surface-variant">
                <span class="material-symbols-outlined text-[20px] text-secondary">check_circle</span>
                {{ $feat }}
            </li>
            @endforeach
        </ul>
        <div class="frame-card shadow-biscuit max-w-md">
            <div class="overflow-hidden">
                @include('components.illustrations.study-desk')
            </div>
        </div>
    </div>

    {{-- 右：登録フォーム --}}
    <div class="w-full max-w-md mx-auto lg:mx-0">
        <div class="bg-surface-container-lowest p-8 rounded-2xl shadow-biscuit border border-outline-variant">

            <div class="space-y-1.5 mb-8">
                <h1 class="font-display text-2xl font-bold tracking-tight text-on-surface">新規登録</h1>
                <p class="text-caption text-on-surface-variant">
                    すべての機能を<span class="text-primary font-bold">無料</span>でご利用いただけます。
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="block text-caption font-bold text-on-surface-variant" for="name">お名前</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                            placeholder="お名前を入力"
                            class="w-full px-4 py-3 bg-surface-container-low border @error('name') border-error @else border-outline-variant @enderror rounded-xl focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition-all" />
                        @error('name') <p class="text-xs font-semibold text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-caption font-bold text-on-surface-variant" for="email">メールアドレス</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                            placeholder="name@example.com"
                            class="w-full px-4 py-3 bg-surface-container-low border @error('email') border-error @else border-outline-variant @enderror rounded-xl focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition-all" />
                        @error('email') <p class="text-xs font-semibold text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-caption font-bold text-on-surface-variant" for="password">パスワード</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            placeholder="安全なパスワードを設定"
                            class="w-full px-4 py-3 bg-surface-container-low border @error('password') border-error @else border-outline-variant @enderror rounded-xl focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition-all" />
                        @error('password') <p class="text-xs font-semibold text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-caption font-bold text-on-surface-variant" for="password-confirm">パスワード（確認）</label>
                        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                            placeholder="パスワードを再入力"
                            class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition-all" />
                    </div>
                </div>

                <button type="submit" class="biscuit-btn w-full text-label-md">アカウントを作成</button>
            </form>

            <p class="text-caption text-on-surface-variant text-center mt-6">
                すでにアカウントをお持ちの方は
                <a href="{{ route('login') }}" class="font-bold text-primary no-underline hover:opacity-80">ログイン</a>
            </p>
        </div>
    </div>

</div>
@endsection
