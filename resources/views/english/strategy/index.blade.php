@extends('layouts.app')

@section('title', 'Exam Overview & Learning Strategy')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">試験概要と学習ストラテジー</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">試験概要と学習ストラテジー</h1>
        <p class="text-body-md text-on-surface-variant">TOEIC・IELTSの試験形式や、目標スコア別の学習プランを確認しよう</p>
    </div>

    {{-- 試験概要 --}}
    <section class="mb-10">
        <h2 class="text-headline-md font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">assignment</span>
            試験概要
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="/english/overview/ielts"
               class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-6 flex items-center gap-4 no-underline group">
                <div class="p-3 bg-primary/10 rounded-[0.75rem] flex-shrink-0">
                    <span class="material-symbols-outlined text-primary">record_voice_over</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-on-surface">IELTS とは</h3>
                    <p class="text-caption text-on-surface-variant">試験形式・バンドスコアの詳細を確認する</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</span>
            </a>

            <a href="/english/overview/toeic"
               class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-6 flex items-center gap-4 no-underline group">
                <div class="p-3 bg-primary/10 rounded-[0.75rem] flex-shrink-0">
                    <span class="material-symbols-outlined text-primary">menu_book</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-on-surface">TOEIC とは</h3>
                    <p class="text-caption text-on-surface-variant">試験形式・スコア換算の詳細を確認する</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</span>
            </a>
        </div>
    </section>

    {{-- 学習ストラテジー --}}
    <h2 class="text-headline-lg font-bold text-on-surface mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">psychology</span>
        学習ストラテジー
    </h2>

    {{-- TOEIC --}}
    <section class="mb-10">
        <h2 class="text-headline-md font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">menu_book</span>
            TOEIC
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($toeicStrategies as $s)
            <a href="/english/strategy/{{ $s['exam'] }}/{{ $s['level'] }}"
               class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-6 flex items-center gap-4 no-underline group">
                <div class="p-3 bg-primary/10 rounded-[0.75rem] flex-shrink-0">
                    <span class="material-symbols-outlined text-primary">lightbulb</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-on-surface">{{ $s['label'] }}</h3>
                    <p class="text-caption text-on-surface-variant">{{ $s['desc'] }}</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</span>
            </a>
            @endforeach
        </div>
    </section>

    {{-- IELTS --}}
    <section>
        <h2 class="text-headline-md font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">record_voice_over</span>
            IELTS
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($ieltsStrategies as $s)
            <a href="/english/strategy/{{ $s['exam'] }}/{{ $s['level'] }}"
               class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-6 flex items-center gap-4 no-underline group">
                <div class="p-3 bg-primary/10 rounded-[0.75rem] flex-shrink-0">
                    <span class="material-symbols-outlined text-primary">lightbulb</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-on-surface">{{ $s['label'] }}</h3>
                    <p class="text-caption text-on-surface-variant">{{ $s['desc'] }}</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</span>
            </a>
            @endforeach
        </div>
    </section>

</div>
@endsection
