@extends('layouts.app')

@section('title', 'Learning Progress')

@section('content')
@php
$typeIcons  = ['toeic' => 'menu_book', 'ielts' => 'record_voice_over', 'typing' => 'keyboard', 'vocabulary' => 'translate', 'quiz' => 'quiz'];
$typeLabels = ['toeic' => 'TOEIC', 'ielts' => 'IELTS Speaking', 'typing' => 'タイピング', 'vocabulary' => '英単語', 'quiz' => 'クイズ'];
$sectionLabels = [
    'toeic'      => ['label' => 'TOEIC',      'icon' => 'menu_book'],
    'ielts'      => ['label' => 'IELTS',       'icon' => 'record_voice_over'],
    'typing'     => ['label' => 'タイピング',  'icon' => 'keyboard'],
    'vocabulary' => ['label' => '英単語',      'icon' => 'translate'],
];
@endphp

<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">学習管理</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">学習管理</h1>
        <p class="text-body-md text-on-surface-variant">あなたの学習進捗・履歴を確認しよう</p>
    </div>

    <div class="space-y-6">

        {{-- 上段: XPバー + ストリーク --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-6">
                <h2 class="text-label-md font-bold text-on-surface-variant mb-4">レベル & XP</h2>
                <x-english.xp-bar
                    :level="$levelInfo['level']"
                    :currentXp="$levelInfo['xp_in_level']"
                    :nextXp="500" />
            </div>
            {{-- 連続日数表示（コメントアウト。トータル学習日数表示に変更したため） --}}
            {{--
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-6 flex items-center gap-4">
                <div class="text-coral"><svg class="w-12 h-12" aria-hidden="true"><use href="#m-flame"/></svg></div>
                <div>
                    <p class="text-display font-black text-primary">{{ $user->study_streak }}</p>
                    <p class="text-body-md text-on-surface-variant">日連続学習</p>
                </div>
            </div>
            --}}
            <div class="biscuit-card p-6 flex items-center gap-4">
                <span class="grid place-items-center w-14 h-14 shrink-0 rounded-blob bg-primary-container text-primary shadow-biscuit">
                    <span class="material-symbols-outlined text-2xl">calendar_month</span>
                </span>
                <div>
                    <p class="text-display font-bold text-primary leading-none">{{ $totalStudyDays }}<span class="text-headline-md ml-1">日</span></p>
                    <p class="text-body-md text-on-surface-variant mt-1">総学習日数</p>
                </div>
            </div>
        </div>

        {{-- 学習サマリー --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-5 text-center">
                @if($studyTimeFormatted['hours'] > 0)
                <p class="text-headline-lg font-black text-primary">{{ $studyTimeFormatted['hours'] }}<span class="text-body-md">h</span>{{ $studyTimeFormatted['minutes'] }}<span class="text-body-md">m</span></p>
                @else
                <p class="text-headline-lg font-black text-primary">{{ $studyTimeFormatted['minutes'] }}<span class="text-body-md">m</span></p>
                @endif
                <p class="text-caption text-on-surface-variant">総学習時間</p>
            </div>
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-5 text-center">
                <p class="text-headline-lg font-black text-primary">{{ number_format($levelInfo['current_xp']) }}</p>
                <p class="text-caption text-on-surface-variant">Total XP</p>
            </div>
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-5 text-center">
                <p class="text-headline-lg font-black text-primary">{{ $levelInfo['level'] }}</p>
                <p class="text-caption text-on-surface-variant">現在レベル</p>
            </div>
        </div>

        {{-- 学習カレンダー + 試験日 --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-6">
            <h2 class="text-headline-md font-bold text-on-surface mb-4">学習カレンダー</h2>

            <div class="flex flex-col md:flex-row md:items-start gap-8">
                {{-- カレンダー本体（残りスペース内で中央寄せ） --}}
                <div class="flex-1 flex justify-center">
                    <div class="w-64">
                        <div class="flex items-center justify-center gap-1 mb-3">
                            <a href="{{ route('english.progress', ['month' => $calendar['prevMonth']]) }}"
                                class="p-1.5 rounded-full hover:bg-surface-container transition-colors">
                                <span class="material-symbols-outlined text-on-surface-variant">chevron_left</span>
                            </a>
                            <span class="text-label-md font-bold text-on-surface w-24 text-center">{{ $calendar['month']->format('Y年n月') }}</span>
                            <a href="{{ route('english.progress', ['month' => $calendar['nextMonth']]) }}"
                                class="p-1.5 rounded-full hover:bg-surface-container transition-colors">
                                <span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
                            </a>
                        </div>
                        <div class="grid grid-cols-7 gap-1.5 mb-1.5">
                            @foreach(['日', '月', '火', '水', '木', '金', '土'] as $dow)
                            <div class="text-center text-caption text-on-surface-variant font-bold">{{ $dow }}</div>
                            @endforeach
                        </div>
                        <div class="space-y-1.5">
                            @foreach($calendar['weeks'] as $week)
                            <div class="grid grid-cols-7 gap-1.5">
                                @foreach($week as $cell)
                                <div class="aspect-square rounded-[0.375rem] flex items-center justify-center text-caption
                                    {{ !$cell['inMonth']
                                        ? 'text-on-surface-variant/30'
                                        : ($cell['examType'] === 'toeic'
                                            ? 'bg-[#2f5fdb] text-white font-bold'
                                            : ($cell['examType'] === 'ielts'
                                                ? 'bg-[#84CC16] text-white font-bold'
                                                : ($cell['studied']
                                                    ? 'bg-[#974018] text-white font-bold'
                                                    : 'bg-[#E5E7EB] text-on-surface'))) }}
                                    {{ $cell['isToday'] ? 'ring-2 ring-[#F59E0B]' : '' }}">
                                    {{ $cell['date']->day }}
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-4 text-caption text-on-surface-variant">
                            <span class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-[0.2rem] bg-[#974018] inline-block"></span>
                                学習した日
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-[0.2rem] bg-[#2f5fdb] inline-block"></span>
                                TOEIC試験日
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-[0.2rem] bg-[#84CC16] inline-block"></span>
                                IELTS試験日
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-[0.2rem] bg-[#E5E7EB] ring-2 ring-[#F59E0B] inline-block"></span>
                                今日
                            </span>
                        </div>
                    </div>
                </div>

                {{-- 試験日（残りスペース内で中央寄せ） --}}
                <div class="flex-1 flex justify-center">
                    <div class="flex flex-col gap-4 w-full max-w-xs">
                        @foreach(['toeic' => 'TOEIC', 'ielts' => 'IELTS'] as $examKey => $examLabel)
                        @php
                            $examDate = $user->{$examKey . '_exam_date'};
                            $daysLeft = $examDaysLeft[$examKey];
                            $examColor = $examKey === 'toeic' ? '#2f5fdb' : '#84CC16';
                        @endphp
                        <div class="rounded-[0.75rem] p-5" style="background-color: {{ $examColor }}1A;">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-label-md font-bold text-on-surface-variant">{{ $examLabel }} 試験日</h3>
                                <span class="material-symbols-outlined text-lg" style="color: {{ $examColor }};">event</span>
                            </div>
                            @if($examDate)
                                <p class="text-caption text-on-surface mb-1">{{ $examDate->format('Y年n月j日') }}</p>
                                @if($daysLeft > 0)
                                    <p class="text-headline-md font-black" style="color: {{ $examColor }};">残り {{ $daysLeft }} 日</p>
                                @elseif($daysLeft === 0)
                                    <p class="text-body-md font-bold" style="color: {{ $examColor }};">本日が試験日です</p>
                                @else
                                    <p class="text-caption text-on-surface-variant">試験日を過ぎています</p>
                                @endif
                            @else
                                <p class="text-caption text-on-surface-variant mb-1">未設定</p>
                            @endif
                            <form method="POST" action="{{ route('english.progress.exam-date') }}" class="mt-3 flex items-center gap-2">
                                @csrf
                                <input type="hidden" name="exam_type" value="{{ $examKey }}">
                                <input type="date" name="exam_date" value="{{ $examDate?->format('Y-m-d') }}"
                                    class="flex-1 min-w-0 text-caption border border-outline-variant rounded-[0.5rem] px-2 py-1.5 bg-surface-container-lowest focus:border-primary focus:outline-none">
                                <button type="submit"
                                    class="px-3 py-1.5 text-white rounded-[0.5rem] text-caption font-bold hover:opacity-90 transition-all shrink-0"
                                    style="background-color: {{ $examColor }};">
                                    保存
                                </button>
                                <button type="submit"
                                    onclick="if (!confirm('{{ $examLabel }}の試験日を取り消しますか？')) return false; this.form.exam_date.value = '';"
                                    onmouseover="this.style.backgroundColor = '{{ $examColor }}1A';"
                                    onmouseout="this.style.backgroundColor = 'transparent';"
                                    class="px-3 py-1.5 border rounded-[0.5rem] text-caption font-bold transition-all shrink-0"
                                    style="border-color: {{ $examColor }}; color: {{ $examColor }};">
                                    取り消し
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- 機能別進捗 --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-6">
            <h2 class="text-headline-md font-bold text-on-surface mb-6">機能別進捗</h2>
            <div class="space-y-4">
                @foreach($sectionLabels as $key => $meta)
                @if(isset($sectionProgress[$key]))
                @php $sp = $sectionProgress[$key]; @endphp
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-base w-5 flex-shrink-0">{{ $meta['icon'] }}</span>
                    <span class="text-body-md text-on-surface w-28 flex-shrink-0">{{ $meta['label'] }}</span>
                    <div class="flex-1">
                        <x-english.progress-bar
                            :percent="$sp['percent']"
                            :label="$meta['label']"
                            :current="$sp['done']"
                            :total="$sp['total']"
                        />
                    </div>
                    <span class="text-caption text-on-surface-variant w-16 text-right flex-shrink-0">{{ $sp['done'] }}/{{ $sp['total'] }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>

        {{-- 最近の学習履歴 --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-outline-variant">
                <h2 class="text-headline-md font-bold text-on-surface">最近の学習履歴</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="py-3 px-6 text-label-md text-on-surface-variant">種別</th>
                            <th class="py-3 px-6 text-label-md text-on-surface-variant">日付</th>
                            <th class="py-3 px-6 text-label-md text-on-surface-variant text-right">獲得XP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/50">
                        @forelse($recentLogs as $log)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="py-3 px-6 text-body-md text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-base">{{ $typeIcons[$log->activity_type] ?? 'star' }}</span>
                                {{ $typeLabels[$log->activity_type] ?? $log->activity_type }}
                            </td>
                            <td class="py-3 px-6 text-caption text-on-surface-variant">
                                {{ $log->studied_date instanceof \Carbon\Carbon ? $log->studied_date->format('Y/m/d') : $log->studied_date }}
                            </td>
                            <td class="py-3 px-6 text-right font-bold text-primary">+{{ $log->xp_gained }} XP</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-on-surface-variant text-body-md">まだ学習履歴がありません</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- お気に入り単語 --}}
        <a href="{{ route('english.vocabulary.favorites') }}"
           class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-6 flex items-center gap-4 no-underline group">
            <div class="p-3 bg-red-100 rounded-[0.75rem]">
                <span class="material-symbols-outlined text-red-500 text-2xl">favorite</span>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors">お気に入り単語</h3>
                <p class="text-caption text-on-surface-variant">{{ $favoritesCount }}語のお気に入りが保存されています</p>
            </div>
            <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</span>
        </a>

    </div>

</div>
@endsection
