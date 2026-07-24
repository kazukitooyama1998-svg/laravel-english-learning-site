@extends('layouts.app')

@section('title', 'スペル練習 - ' . strtoupper(explode('-', $level)[0]) . ' ' . explode('-', $level)[1])

@section('content')
@php
$levelLabel = strtoupper(explode('-', $level)[0]) . ' ' . explode('-', $level)[1];
$wordsJson  = $words->map(fn($w) => [
    'id'      => $w->id,
    'word'    => $w->word,
    'hint'    => $w->meaning_ja,
    'example' => $w->example_sentence,
])->values()->all();
@endphp

<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.vocabulary.index') }}" class="hover:text-primary transition-colors no-underline">英単語</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">スペル練習</span>
    </x-english.breadcrumb>

    <div x-data="spellingApp({
            words:       {{ json_encode($wordsJson) }},
            checkUrl:    '{{ route('english.vocabulary.spelling.check',    $level) }}',
            completeUrl: '{{ route('english.vocabulary.spelling.complete', $level) }}'
        })">

        {{-- ヘッダー --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-headline-md font-bold text-on-surface">{{ $levelLabel }} スペル練習</h1>
                <span class="text-label-md text-on-surface-variant"
                      x-text="`問題 ${currentIndex + 1} / ${words.length}`"></span>
            </div>
            <div class="w-full bg-surface-container-high rounded-[0.75rem] h-2 overflow-hidden">
                <div class="bg-primary h-full rounded-[0.75rem] transition-all duration-500"
                     :style="`width: ${progressPercent}%`"></div>
            </div>
        </div>

        {{-- クイズ --}}
        <div x-show="!isComplete" class="max-w-2xl mx-auto">
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 mb-6">
                <p class="text-caption text-on-surface-variant mb-2">意味のヒント</p>
                <p class="text-headline-md font-bold text-primary mb-4" x-text="current.hint"></p>
                <p class="text-caption text-on-surface-variant mb-1">例文</p>
                <p class="text-body-md text-on-surface font-mono" x-text="current.example"></p>
            </div>

            <div x-show="!isAnswered" class="space-y-4">
                <input
                    type="text"
                    x-model="userInput"
                    @keydown.enter="submitAnswer()"
                    :disabled="isLoading"
                    placeholder="英単語を入力してください..."
                    class="w-full p-4 text-body-lg border-2 border-outline-variant rounded-[0.75rem] bg-surface-container-lowest focus:border-primary focus:outline-none transition-colors"
                />
                <button @click="submitAnswer()"
                        :disabled="!userInput.trim() || isLoading"
                        :class="userInput.trim() && !isLoading
                            ? 'bg-primary text-on-primary hover:opacity-90'
                            : 'bg-surface-container text-on-surface-variant cursor-not-allowed'"
                        class="w-full py-3 rounded-[0.75rem] font-label-md text-label-md transition-all flex items-center justify-center gap-2">
                    <span x-show="isLoading" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                    <span x-text="isLoading ? '確認中...' : '回答する'"></span>
                </button>
            </div>

            <div x-show="isAnswered" class="space-y-4">
                <div :class="isCorrect ? 'bg-green-50 border-green-200' : 'bg-error-container/30 border-error/20'"
                     class="border rounded-[0.5rem] p-6 text-center">
                    <p class="text-3xl mb-2" x-text="isCorrect ? '✅' : '❌'"></p>
                    <p class="font-bold text-lg" :class="isCorrect ? 'text-green-700' : 'text-error'"
                       x-text="isCorrect ? '正解！' : '不正解'"></p>
                    <p x-show="!isCorrect" class="text-body-md text-on-surface mt-2">
                        正解: <strong x-text="correctWord"></strong>
                    </p>
                </div>
                <button @click="nextQuestion()"
                        class="w-full py-3 bg-primary text-on-primary rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <span x-text="currentIndex < words.length - 1 ? '次の問題' : '結果を見る'"></span>
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </div>
        </div>

        {{-- 完了 --}}
        <div x-show="isComplete" class="text-center max-w-md mx-auto">
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 mb-6">
                <div class="text-5xl mb-4">🎉</div>
                <h2 class="text-headline-lg font-bold text-on-surface mb-2">完了！</h2>
                <p class="text-body-md text-on-surface-variant mb-2">スコア</p>
                <p class="text-display font-black text-primary" x-text="`${score} / ${words.length}`"></p>
                <p class="text-body-md text-primary font-bold mt-3" x-text="`+${gainedXp} XP 獲得！`"></p>
            </div>
            <div class="flex gap-3">
                <button @click="restart()"
                        class="flex-1 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-on-surface hover:bg-surface-container transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">refresh</span>
                    もう一度
                </button>
                <a href="{{ route('english.vocabulary.index') }}"
                   class="flex-1 py-3 bg-primary text-on-primary rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all no-underline text-center flex items-center justify-center">
                    レベル選択へ
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
