@extends('layouts.app')

@php
$pageTitle = isset($content)
    ? $content->title
    : ($strategy['title'] ?? (strtoupper($exam) . ' ' . ($exam === 'toeic' ? $level . '点' : $level) . ' 攻略ストラテジー'));
@endphp

@section('title', $pageTitle)

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.strategy.index') }}" class="hover:text-primary transition-colors no-underline">試験概要と学習ストラテジー</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">{{ $pageTitle }}</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <span class="bg-primary/10 text-primary text-label-md font-bold px-3 py-1 rounded-[0.75rem] uppercase">{{ strtoupper($exam) }}</span>
            <span class="bg-surface-container-high text-on-surface-variant text-label-md font-bold px-3 py-1 rounded-[0.75rem]">目標: {{ $level }}{{ $exam === 'toeic' ? '点' : '' }}</span>
        </div>
        <h1 class="text-headline-lg font-bold text-on-surface">{{ $pageTitle }}</h1>
    </div>

    <div class="max-w-3xl space-y-6">

        @if(isset($content))
            {{-- DB に登録されたレベル別の戦略コンテンツ --}}
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8">
                <div class="text-body-md text-on-surface leading-relaxed
                            [&_h2]:text-headline-md [&_h2]:font-bold [&_h2]:text-on-surface [&_h2]:mb-4
                            [&_h3]:text-body-lg [&_h3]:font-bold [&_h3]:text-on-surface [&_h3]:mt-6 [&_h3]:mb-2 [&_h3:first-child]:mt-0
                            [&_p]:mb-3
                            [&_ul]:space-y-2 [&_ul]:mb-4
                            [&_li]:flex [&_li]:items-start [&_li]:gap-2 [&_li]:before:content-['•'] [&_li]:before:text-primary [&_li]:before:font-bold
                            [&_strong]:font-bold [&_strong]:text-on-surface
                            [&_table]:w-full [&_table]:border-collapse [&_table]:my-4">
                    {!! $content->body !!}
                </div>
            </div>
        @else
            {{-- DB 未投入時のフォールバック（静的データ） --}}

            {{-- 1. おすすめ学習順序 --}}
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8">
                <h2 class="text-headline-md font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">format_list_numbered</span>
                    おすすめ学習順序
                </h2>
                <div class="space-y-3">
                    @foreach($strategy['learning_order'] as $item)
                    <div class="flex items-start gap-4 p-4 bg-surface-container-low rounded-[0.5rem]">
                        <div class="bg-primary text-on-primary w-8 h-8 rounded-[0.75rem] flex items-center justify-center font-black text-sm flex-shrink-0">{{ $item['step'] }}</div>
                        <div>
                            <p class="font-bold text-on-surface mb-1">{{ $item['title'] }}</p>
                            <p class="text-body-md text-on-surface-variant">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. 重点学習ポイント --}}
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8">
                <h2 class="text-headline-md font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">star</span>
                    重点学習ポイント
                </h2>
                <ul class="space-y-2">
                    @foreach($strategy['key_points'] as $point)
                    <li class="flex items-start gap-2 text-body-md text-on-surface">
                        <span class="material-symbols-outlined text-primary text-sm mt-0.5">check_circle</span>
                        {{ $point }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- 3. 学習スケジュール --}}
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8">
                <h2 class="text-headline-md font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">schedule</span>
                    学習スケジュール（目安）
                </h2>
                <ul class="space-y-2">
                    @foreach($strategy['study_schedule'] as $line)
                    <li class="text-body-md text-on-surface-variant">{{ $line }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- 4. よくある間違い --}}
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8">
                <h2 class="text-headline-md font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-error">error_outline</span>
                    よくある間違い
                </h2>
                <ul class="space-y-2">
                    @foreach($strategy['common_mistakes'] as $mistake)
                    <li class="flex items-start gap-2 text-body-md text-on-surface">
                        <span class="material-symbols-outlined text-error text-sm mt-0.5">close</span>
                        {{ $mistake }}
                    </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 関連リンク --}}
        <div class="flex gap-3">
            <a href="{{ route('english.' . $exam . '.index') }}"
               class="flex-1 py-3 bg-primary text-on-primary rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all no-underline text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">play_arrow</span>
                {{ strtoupper($exam) }}学習を始める
            </a>
            <a href="{{ route('english.strategy.index') }}"
               class="flex-1 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-on-surface hover:bg-surface-container transition-all no-underline text-center flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                戻る
            </a>
        </div>

    </div>

</div>
@endsection
