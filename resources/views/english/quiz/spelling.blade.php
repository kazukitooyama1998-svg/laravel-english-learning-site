@extends('layouts.app')

@section('title', 'スペルクイズ')

@section('content')
@php
$wordsJson = $words->map(fn($w) => [
    'id'         => $w->id,
    'word'       => $w->word,
    'hint'       => $w->meaning_ja,
    'pos'        => $w->part_of_speech,
    'example'    => $w->example_sentence,
    'exampleJa'  => $w->example_sentence_ja,
])->values()->all();
@endphp

<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.quiz.index') }}" class="hover:text-primary transition-colors no-underline">クイズ</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">スペルクイズ</span>
    </x-english.breadcrumb>

    <div x-data="quizSpellingApp({
            words:     {{ json_encode($wordsJson) }},
            submitUrl: '{{ route('english.quiz.spelling.submit') }}'
        })">

        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-headline-md font-bold text-on-surface">スペルクイズ</h1>
                <span class="text-label-md text-on-surface-variant"
                      x-text="`問題 ${currentIndex + 1} / ${words.length}`"></span>
            </div>
            <div class="w-full bg-surface-container-high rounded-[0.75rem] h-2 overflow-hidden">
                <div class="bg-primary h-full rounded-[0.75rem] transition-all duration-500"
                     :style="`width: ${progressPercent}%`"></div>
            </div>
        </div>

        <div x-show="!isComplete" class="max-w-2xl mx-auto">
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 mb-6">
                <p class="text-body-md font-bold text-on-surface-variant mb-2">この意味を表す英単語のスペルを入力してください</p>
                <p class="text-headline-md font-bold text-primary mb-4" x-text="current.hint"></p>
                <p class="text-body-md font-bold text-on-surface-variant mb-1">例文（この単語が本文中に使われています。わからない場合は本文から探して入力してもOKです）</p>
                <p class="text-body-md text-on-surface font-mono" x-text="current.example"></p>
            </div>

            <div x-show="!isAnswered" class="space-y-4">
                <input
                    type="text"
                    x-model="userInput"
                    @keydown.enter="submitAnswer()"
                    placeholder="上の意味に合う英単語のスペルを入力..."
                    class="w-full p-4 text-body-lg border-2 border-outline-variant rounded-[0.75rem] bg-surface-container-lowest focus:border-primary focus:outline-none transition-colors"
                />
                <button @click="submitAnswer()"
                        :disabled="!userInput.trim()"
                        :class="userInput.trim()
                            ? 'bg-primary text-on-primary hover:opacity-90'
                            : 'bg-surface-container text-on-surface-variant cursor-not-allowed'"
                        class="w-full py-3 rounded-[0.75rem] font-label-md text-label-md transition-all">
                    回答する
                </button>
            </div>

            <div x-show="isAnswered" class="space-y-4">
                <div :class="isCorrect ? 'bg-green-50 border-green-200' : 'bg-error-container/30 border-error/20'"
                     class="border rounded-[0.5rem] p-6 text-center">
                    <p class="text-3xl mb-2" x-text="isCorrect ? '✅' : '❌'"></p>
                    <p class="font-bold text-lg" :class="isCorrect ? 'text-green-700' : 'text-error'"
                       x-text="isCorrect ? '正解！' : '不正解'"></p>
                    <p x-show="!isCorrect" class="text-body-md text-on-surface mt-2">
                        正解: <strong x-text="current.word"></strong>
                    </p>
                </div>

                {{-- 正解時：意味・品詞・例文（英文/日本語訳）を表示 --}}
                <div x-show="isCorrect" class="bg-surface-container-lowest rounded-[0.5rem] p-6 text-left space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-headline-md font-bold text-primary" x-text="current.word"></span>
                        <span class="text-caption text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full" x-text="current.pos"></span>
                    </div>
                    <p class="text-body-md text-on-surface" x-text="current.hint"></p>
                    <div class="pt-2 border-t border-outline-variant/50">
                        <p class="text-body-md text-on-surface font-mono" x-text="current.example"></p>
                        <p class="text-body-md text-on-surface-variant mt-1" x-text="current.exampleJa"></p>
                    </div>
                </div>

                <button @click="nextQuestion()"
                        :disabled="isLoading"
                        class="w-full py-3 bg-primary text-on-primary rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <span x-show="isLoading" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                    <span x-text="currentIndex < words.length - 1 ? '次の問題' : '結果を見る'"></span>
                    <span x-show="!isLoading" class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('english.quiz.index') }}"
                   class="px-6 py-2.5 bg-orange-600 text-white font-bold rounded-[0.5rem] shadow-sm hover:bg-orange-700 transition-colors text-base no-underline">
                    Quit Practice
                </a>
            </div>
        </div>

        <div x-show="isComplete" class="text-center max-w-md mx-auto">
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 mb-6">
                <div class="text-5xl mb-4">🎉</div>
                <h2 class="text-headline-lg font-bold text-on-surface mb-2">クイズ完了！</h2>
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
                <a href="{{ route('english.quiz.index') }}"
                   class="flex-1 py-3 bg-primary text-on-primary rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all no-underline text-center flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    戻る
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
