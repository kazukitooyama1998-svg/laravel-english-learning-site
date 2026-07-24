@extends('layouts.app')

@section('title', 'IELTS - Study Slides')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    {{-- 上部ヘッダー --}}
    <div class="mb-6">
        <x-english.breadcrumb>
            <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
            <span class="mx-1">/</span>
            <a href="{{ route('english.ielts.index') }}" class="hover:text-primary transition-colors no-underline">IELTS</a>
            <span class="mx-1">/</span>
            <span class="text-on-surface font-semibold capitalize">{{ $topicMeta['name'] ?? $topic }} × {{ $score }}</span>
        </x-english.breadcrumb>

        <div class="flex items-center justify-between mb-3">
            <div>
                <h1 class="text-headline-md font-bold text-on-surface capitalize">{{ $topicMeta['name'] ?? $topic }} × IELTS {{ $score }}</h1>
                <p class="text-caption text-on-surface-variant">ステップ {{ $step }} / {{ $totalSteps }}</p>
            </div>
            @if($canSkip)
            <a href="/english/ielts/speaking/{{ $part }}/{{ $topic }}/{{ $score }}/typing"
               class="px-4 py-2 bg-surface-container-lowest rounded-[0.75rem] shadow-sm text-label-md text-on-surface-variant hover:text-primary transition-all no-underline flex items-center gap-1">
                スキップしてタイピングへ
                <span class="material-symbols-outlined text-sm">skip_next</span>
            </a>
            @endif
        </div>
        <x-english.progress-bar :percent="round(($step / $totalSteps) * 100)" :current="$step" :total="$totalSteps" />
    </div>

    {{-- スライドカード --}}
    <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 mb-6 max-w-3xl mx-auto">
        @if($slide)
        <div class="flex items-center gap-2 mb-6">
            <span class="bg-primary/10 text-primary text-caption font-bold px-3 py-1 rounded-[0.75rem] uppercase tracking-wider">
                {{ $slide->slide_type }}
            </span>
            <h2 class="text-headline-md font-bold text-on-surface">{{ $slide->title }}</h2>
        </div>
        <div class="prose prose-sm max-w-none text-on-surface leading-relaxed">
            {!! $slide->content !!}
        </div>
        @else
        <div class="text-center py-12">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant/40 mb-3 block">article</span>
            <p class="text-body-md text-on-surface-variant">このスライドのコンテンツはまだ追加されていません</p>
        </div>
        @endif
    </div>

    {{-- スライド完了ボタン（最終ステップ） --}}
    @if($step >= $totalSteps)
    <form action="/english/ielts/speaking/{{ $part }}/{{ $topic }}/{{ $score }}/slides/complete" method="POST"
          class="max-w-3xl mx-auto mb-4 flex justify-center">
        @csrf
        <button type="submit"
                class="px-8 py-3 bg-primary text-on-primary rounded-[0.75rem] font-label-md text-label-md hover:opacity-90 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            スライド完了・タイピングへ
        </button>
    </form>
    @endif

    {{-- ナビゲーション --}}
    <div class="flex items-center justify-between max-w-3xl mx-auto">
        <a href="/english/ielts/speaking/{{ $part }}/{{ $topic }}/{{ $score }}/slides/{{ $step - 1 }}"
           class="px-6 py-3 bg-surface-container-lowest rounded-[0.75rem] shadow-sm font-label-md text-label-md text-on-surface hover:bg-surface-container transition-all no-underline flex items-center gap-2 {{ $step <= 1 ? 'opacity-40 pointer-events-none' : '' }}">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            前へ
        </a>
        @if($step < $totalSteps)
        <a href="/english/ielts/speaking/{{ $part }}/{{ $topic }}/{{ $score }}/slides/{{ $step + 1 }}"
           class="px-6 py-3 bg-primary text-on-primary rounded-[0.75rem] font-label-md text-label-md flex items-center gap-2 no-underline hover:opacity-90 transition-all">
            次へ
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
        @endif
    </div>

</div>
@endsection
