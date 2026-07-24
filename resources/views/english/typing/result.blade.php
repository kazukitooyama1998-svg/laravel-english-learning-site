@extends('layouts.app')

@section('title', 'タイピング - 結果')

@section('content')
@php
$clearTimeSec = (int) $record->clear_time;
$clearTimeFormatted = sprintf('%d:%02d', intdiv($clearTimeSec, 60), $clearTimeSec % 60);
@endphp

<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.typing.index') }}" class="hover:text-primary transition-colors no-underline">タイピング練習</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">結果</span>
    </x-english.breadcrumb>

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- 結果カード --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 text-center">
            <div class="text-5xl mb-3">⌨️</div>
            <h1 class="text-headline-lg font-bold text-on-surface mb-2">{{ $material->category->name }}</h1>
            <p class="text-body-md text-on-surface-variant mb-6">タイピング完了！</p>

            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="text-center p-3 bg-surface-container-low rounded-[0.5rem]">
                    <p class="text-headline-lg font-black text-primary">{{ $record->wpm }}</p>
                    <p class="text-caption text-on-surface-variant">WPM</p>
                </div>
                <div class="text-center p-3 bg-surface-container-low rounded-[0.5rem]">
                    <p class="text-headline-lg font-black text-primary">{{ $record->accuracy }}%</p>
                    <p class="text-caption text-on-surface-variant">正確率</p>
                </div>
                <div class="text-center p-3 bg-surface-container-low rounded-[0.5rem]">
                    <p class="text-headline-lg font-black text-primary">{{ $clearTimeFormatted }}</p>
                    <p class="text-caption text-on-surface-variant">タイム</p>
                </div>
            </div>

            @if($bestWpm > 0)
            <div class="mb-4 text-caption text-on-surface-variant">
                ベスト記録: <span class="font-bold text-on-surface">{{ $bestWpm }} WPM</span>
                @if($record->wpm >= $bestWpm)
                <span class="ml-2 text-primary font-bold">🏆 新記録！</span>
                @endif
            </div>
            @endif

            <div class="bg-primary/10 rounded-[0.5rem] p-4">
                <p class="text-label-md font-bold text-primary mb-1">獲得 XP</p>
                <p class="text-headline-lg font-black text-primary">+{{ $record->xp_gained }} XP</p>
            </div>
        </div>

        {{-- XPバー --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-6">
            <x-english.xp-bar
                :level="$levelInfo['level']"
                :currentXp="$levelInfo['xp_in_level']"
                :nextXp="500" />
        </div>

        {{-- ボタン --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('english.typing.practice', ['id' => $id]) }}"
               class="flex-1 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-on-surface hover:bg-surface-container transition-all no-underline text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">refresh</span>
                もう一度
            </a>
            <a href="{{ route('english.typing.index') }}"
               class="flex-1 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-on-surface hover:bg-surface-container transition-all no-underline text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">list</span>
                別の教材を選ぶ
            </a>
            <a href="{{ route('english.hub') }}"
               class="flex-1 py-3 bg-primary text-on-primary rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all no-underline text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">home</span>
                Homeへ戻る
            </a>
        </div>

    </div>

</div>
@endsection
