{{-- 1. ログイン後専用の共通レイアウト（layouts/app.blade.php）を継承 --}}
@extends('layouts.app')

@section('content')
<main class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <section class="mb-12">
        <h1 class="font-display text-display text-on-surface mb-2">
            {{-- 2. ログインしているユーザーの名前を動的に表示 --}}
            Welcome back, {{ Auth::user()->name }}! Select Your Practice
        </h1>

        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">
            Practice professional templates to build muscle memory for the real world.
        </p>
    </section>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">

        {{-- 3. コントローラーから送られてくる練習データ（$practices）をループ処理 --}}
        @forelse ($practices as $practice)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-full p-6 flex flex-col justify-between group hover:shadow-[0px_10px_30px_rgba(108,91,83,0.05)] transition-all duration-300">

                <div>
                    <div class="flex justify-between items-start mb-4">
                        <span class="bg-surface-container-highest text-on-secondary-container px-3 py-1 rounded-lg font-caption text-caption uppercase tracking-wider">
                            {{ $practice->category }}
                        </span>
                        <span class="material-symbols-outlined text-primary">description</span>
                    </div>

                    <h3 class="font-headline-md text-headline-md text-on-surface mb-2">
                        {{ $practice->title }}
                    </h3>

                    <div class="flex items-center gap-2 mb-8">
                        <span class="material-symbols-outlined text-secondary text-base">signal_cellular_alt</span>
                        <span class="font-label-md text-label-md text-on-surface-variant">
                            {{ $practice->level }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('practice.show', $practice->id) }}" class="w-full bg-primary text-on-primary py-3 rounded-full font-label-md text-label-md flex items-center justify-center gap-2 active:scale-95 transition-all decoration-none">
                    Start Practice
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>

            </div>
        @empty
            <div class="col-span-full text-center py-12 text-on-surface-variant">
                <p>No practice templates available at the moment.</p>
            </div>
        @endforelse

    </div>

    {{-- セッションに 'latest_result'（最新の結果）が保存されている時だけ表示する --}}
    @if(session()->has('latest_result'))
        <section class="mt-16">
            <h2 class="font-headline-lg text-headline-lg text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">history</span>
                Recent Activity
            </h2>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-full p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-surface-container-high rounded-xl text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined">keyboard</span>
                        </div>
                        <div>
                            <h4 class="font-headline-md text-base text-on-surface font-semibold">
                                {{-- 実際にプレイしたお題のタイトルを表示 --}}
                                {{ session('latest_result.title') }}
                            </h4>
                            <p class="font-caption text-caption text-on-surface-variant mt-1 flex items-center gap-3">
                                {{-- JavaScriptから送られてきた本物の数値を表示 --}}
                                <span class="flex items-center gap-1">⏱ {{ session('latest_result.time') }} sec</span>
                                <span class="flex items-center gap-1">⚡ {{ session('latest_result.wpm') }} WPM</span>
                                <span class="flex items-center gap-1">🎯 {{ session('latest_result.accuracy') }}% Accuracy</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-1 text-right">
                        <span class="bg-green-100 text-green-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                            Completed
                        </span>
                        <span class="font-caption text-caption text-on-surface-variant opacity-70">
                            Just now
                        </span>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <div class="mt-16 bg-primary-container rounded-full p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8">

        <div class="flex flex-col gap-2 text-center md:text-left">
            <h2 class="font-headline-lg text-headline-lg text-on-primary-container">
                Master the Academic Tone
            </h2>

            <p class="font-body-md text-body-md text-on-primary-container opacity-90">
                Consistent repetition of these structures increases your typing speed and grammatical accuracy for the real test. It also helps you internalize effective sentence patterns, academic and professional expressions, and advanced vocabulary drawn from business correspondence, formal communication, notable speeches by influential figures, and exam preparation materials for standardized tests.
            </p>
        </div>

        <div class="relative w-48 h-48 shrink-0 flex items-center justify-center">
            <svg class="w-full h-full transform -rotate-90">
                <circle class="text-on-primary-container/20" cx="96" cy="96" r="80" stroke="currentColor" stroke-width="8" fill="transparent"></circle>
                <circle class="text-on-primary-container" cx="96" cy="96" r="80" stroke="currentColor"
                    stroke-dasharray="502.4"
                    stroke-dashoffset="125.6"
                    stroke-width="8"
                    fill="transparent"></circle>
            </svg>

            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="font-display text-display text-on-primary-container">{{ $progress ?? '75' }}%</span>
                <span class="font-label-md text-label-md text-on-primary-container">Materials Completed</span>
            </div>
        </div>

    </div>

</main>
@endsection