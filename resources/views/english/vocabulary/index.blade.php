@extends('layouts.app')

@section('title', 'Vocabulary Learning')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">英単語</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">英単語学習</h1>
        <p class="text-body-md text-on-surface-variant">レベルを選んでフラッシュカードで語彙を学ぼう</p>
    </div>

    {{-- TOEIC セクション --}}
    <section class="mb-10">
        <h2 class="text-headline-md font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">menu_book</span>
            TOEIC
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($toeicLevels as $lvl)
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-5 flex flex-col gap-3">
                <h3 class="font-bold text-on-surface text-base">{{ $lvl['label'] }}</h3>
                <p class="text-caption text-on-surface-variant">学習済み: {{ $lvl['learned'] }}/{{ $lvl['total'] }}語</p>
                <x-english.progress-bar :percent="$lvl['progress']" />
                <a href="/english/vocabulary/{{ $lvl['slug'] }}/flashcard"
                   class="mt-auto w-full bg-primary text-on-primary py-2 rounded-[0.75rem] font-label-md text-label-md flex items-center justify-center gap-1 no-underline hover:opacity-90 transition-all text-center text-sm">
                    <span class="material-symbols-outlined text-sm">style</span>
                    フラッシュカード
                </a>
            </div>
            @endforeach
        </div>
    </section>

    {{-- IELTS セクション --}}
    <section class="mb-10">
        <h2 class="text-headline-md font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">record_voice_over</span>
            IELTS
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($ieltsLevels as $lvl)
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-5 flex flex-col gap-3">
                <h3 class="font-bold text-on-surface text-base">{{ $lvl['label'] }}</h3>
                <p class="text-caption text-on-surface-variant">学習済み: {{ $lvl['learned'] }}/{{ $lvl['total'] }}語</p>
                <x-english.progress-bar :percent="$lvl['progress']" />
                <a href="/english/vocabulary/{{ $lvl['slug'] }}/flashcard"
                   class="mt-auto w-full bg-primary text-on-primary py-2 rounded-[0.75rem] font-label-md text-label-md flex items-center justify-center gap-1 no-underline hover:opacity-90 transition-all text-center text-sm">
                    <span class="material-symbols-outlined text-sm">style</span>
                    フラッシュカード
                </a>
            </div>
            @endforeach
        </div>
    </section>

    {{-- Favorites セクション --}}
    <section>
        <h2 class="text-headline-md font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-red-500">favorite</span>
            お気に入り
        </h2>
        <a href="{{ route('english.vocabulary.favorites') }}"
           class="flex items-center gap-4 bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-5 no-underline group">
            <div class="p-3 bg-red-100 rounded-[0.75rem] flex-shrink-0">
                <span class="material-symbols-outlined text-red-500 text-2xl">favorite</span>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors">お気に入り単語一覧</h3>
                <p class="text-caption text-on-surface-variant">{{ $favoritesCount }}語のお気に入りが保存されています</p>
            </div>
            <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</span>
        </a>
    </section>

</div>
@endsection
