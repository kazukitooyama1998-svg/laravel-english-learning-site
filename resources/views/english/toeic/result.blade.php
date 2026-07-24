@extends('layouts.app')

@section('title', "TOEIC Part {$part} - 結果")

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.toeic.index') }}" class="hover:text-primary transition-colors no-underline">TOEIC</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">Part {{ $part }} 結果</span>
    </x-english.breadcrumb>

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- スコアサマリーカード --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 text-center">
            <div class="text-5xl mb-4">
                @if($result->accuracy >= 80) 🎉 @elseif($result->accuracy >= 60) 👍 @else 💪 @endif
            </div>
            <h1 class="text-headline-lg font-bold text-on-surface mb-6">Part {{ $part }} 完了！</h1>

            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="text-center">
                    <p class="text-display font-black text-primary">{{ $result->correct_count }}/{{ $result->total_questions }}</p>
                    <p class="text-caption text-on-surface-variant">正答数</p>
                </div>
                <div class="text-center">
                    <p class="text-display font-black text-primary">{{ $result->accuracy }}%</p>
                    <p class="text-caption text-on-surface-variant">正答率</p>
                </div>
                <div class="text-center">
                    <p class="text-display font-black text-primary">+{{ $result->xp_gained }}</p>
                    <p class="text-caption text-on-surface-variant">XP 獲得</p>
                </div>
            </div>

            <div class="bg-primary/10 rounded-[0.5rem] p-4 mb-4">
                <p class="text-label-md font-bold text-primary mb-1">獲得 XP</p>
                <p class="text-headline-lg font-black text-primary">+{{ $result->xp_gained }} XP</p>
            </div>
        </div>

        {{-- XPバー --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-6">
            <x-english.xp-bar
                :level="$levelInfo['level']"
                :currentXp="$levelInfo['xp_in_level']"
                :nextXp="500" />
        </div>

        {{-- 間違えた問題 --}}
        @if(count($wrongAnswers) > 0)
        <div x-data="{ open: false }" class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm overflow-hidden">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between p-6 text-left hover:bg-surface-container transition-colors">
                <span class="font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-error">error_outline</span>
                    間違えた問題 ({{ count($wrongAnswers) }}問)
                </span>
                <span class="material-symbols-outlined text-on-surface-variant transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="open" x-transition class="px-6 pb-6 space-y-4 border-t border-outline-variant">
                @foreach($wrongAnswers as $wq)
                <div class="p-4 bg-error-container/20 rounded-[0.5rem] border border-error/20 mt-4">
                    <p class="text-body-md text-on-surface font-semibold mb-2">{{ $wq['question'] }}</p>
                    <p class="text-caption text-error mb-1">あなたの回答: {{ $wq['your_answer'] }}</p>
                    <p class="text-caption text-green-700 mb-2">正解: {{ $wq['correct_answer'] }}</p>
                    <p class="text-caption text-on-surface-variant">{{ $wq['explanation'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ボタン --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('english.toeic.slides', $part) }}"
               class="flex-1 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-on-surface hover:bg-surface-container transition-all no-underline text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">refresh</span>
                もう一度挑戦
            </a>
            <a href="{{ route('english.toeic.index') }}"
               class="flex-1 py-3 bg-primary text-on-primary rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all no-underline text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Part選択へ戻る
            </a>
        </div>

    </div>

</div>
@endsection
