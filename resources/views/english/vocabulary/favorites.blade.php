@extends('layouts.app')

@section('title', 'お気に入り単語一覧')

@section('content')

@php
$wordsJson = $favorites->map(fn($f) => [
    'id'         => $f->word->id,
    'word'       => $f->word->word,
    'meaning'    => $f->word->meaning_ja,
    'levelLabel' => $f->word->exam_type . ' ' . $f->word->level,
    'levelSlug'  => strtolower($f->word->exam_type) . '-' . str_replace('.', '', $f->word->level),
    'isLearned'  => in_array($f->word->id, $learnedIds),
])->values()->all();
@endphp

<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.vocabulary.index') }}" class="hover:text-primary transition-colors no-underline">英単語</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">お気に入り</span>
    </x-english.breadcrumb>

    <div class="mb-8 flex items-center gap-3">
        <div class="p-3 bg-red-100 rounded-[0.75rem]">
            <span class="material-symbols-outlined text-red-500 text-2xl">favorite</span>
        </div>
        <div>
            <h1 class="text-headline-lg font-bold text-on-surface">お気に入り単語</h1>
            <p class="text-body-md text-on-surface-variant">登録した単語をまとめて確認・管理できます</p>
        </div>
    </div>

    <div x-data="favoritesPage({
            words:            {{ json_encode($wordsJson) }},
            toggleFavoriteUrl: '{{ $toggleFavoriteUrl }}'
        })">

        {{-- 単語がない場合 --}}
        <template x-if="visibleWords.length === 0">
            <div class="text-center py-20 bg-surface-container-lowest rounded-[0.75rem] shadow-sm">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 mb-4 block">favorite_border</span>
                <p class="text-body-lg text-on-surface-variant mb-6">お気に入り単語がありません</p>
                <a href="{{ route('english.vocabulary.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all no-underline">
                    <span class="material-symbols-outlined text-sm">style</span>
                    単語学習へ
                </a>
            </div>
        </template>

        {{-- 単語一覧 --}}
        <template x-if="visibleWords.length > 0">
            <div>
                <p class="text-body-md text-on-surface-variant mb-4">
                    <span x-text="visibleWords.length"></span>語のお気に入り
                </p>

                <div class="space-y-3">
                    <template x-for="word in visibleWords" :key="word.id">
                        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-5 flex flex-col md:flex-row md:items-center gap-4">

                            {{-- 単語情報 --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="text-headline-sm font-bold text-on-surface" x-text="word.word"></span>
                                    <span class="text-caption bg-surface-container-high text-on-surface-variant px-2 py-0.5 rounded-[0.75rem]"
                                          x-text="word.levelLabel"></span>
                                    <span x-show="word.isLearned"
                                          class="text-caption bg-green-100 text-green-700 px-2 py-0.5 rounded-[0.75rem] flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">check_circle</span>
                                        覚えた
                                    </span>
                                </div>
                                <p class="text-body-md text-on-surface-variant" x-text="word.meaning"></p>
                            </div>

                            {{-- アクションボタン --}}
                            <div class="flex items-center gap-2 flex-shrink-0">
                                {{-- フラッシュカードへ --}}
                                <a :href="`/english/vocabulary/${word.levelSlug}/flashcard`"
                                   class="inline-flex items-center gap-1 px-4 py-2 bg-primary text-on-primary rounded-[0.75rem] text-label-md font-label-md hover:opacity-90 transition-all no-underline text-sm">
                                    <span class="material-symbols-outlined text-sm">style</span>
                                    フラッシュカード
                                </a>

                                {{-- お気に入り解除 --}}
                                <button @click="toggleFavorite(word.id)"
                                        class="inline-flex items-center gap-1 px-4 py-2 border border-red-200 text-red-500 rounded-[0.75rem] text-label-md font-label-md hover:bg-red-50 transition-all text-sm">
                                    <span class="material-symbols-outlined text-sm"
                                          :class="isFavorite(word.id) ? 'text-red-500' : 'text-on-surface-variant'"
                                          style="font-variation-settings: 'FILL' 1">favorite</span>
                                    <span x-text="isFavorite(word.id) ? '解除' : '追加'"></span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- フラッシュカード一覧へ戻るリンク --}}
                <div class="mt-8 text-center">
                    <a href="{{ route('english.vocabulary.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-on-surface hover:bg-surface-container transition-all no-underline">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        単語レベル一覧へ
                    </a>
                </div>
            </div>
        </template>

    </div>

</div>
@endsection
