@extends('layouts.app')

@section('title', 'Quiz')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">クイズ</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">クイズ</h1>
        <p class="text-body-md text-on-surface-variant">スペルや語彙の力をクイズで試そう</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl">

        {{-- スペルクイズ --}}
        <a href="/english/quiz/spelling"
           class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-8 flex flex-col gap-4 no-underline group">
            <div class="flex items-start justify-between">
                <div class="p-4 bg-primary/10 rounded-[0.75rem]">
                    <span class="material-symbols-outlined text-primary text-3xl">spellcheck</span>
                </div>
                <div class="text-right">
                    <p class="text-caption text-on-surface-variant">最近のスコア</p>
                    @if($recentSpelling->isNotEmpty())
                    @php $latest = $recentSpelling->first(); @endphp
                    <p class="text-headline-md font-black text-primary">{{ $latest->correct_count }}/{{ $latest->total_questions }}</p>
                    @else
                    <p class="text-body-md text-on-surface-variant">未挑戦</p>
                    @endif
                </div>
            </div>
            <div>
                <h3 class="text-headline-md font-bold text-on-surface mb-2">スペルクイズ</h3>
                <p class="text-body-md text-on-surface-variant">意味のヒントから英単語のスペルを正確に入力して答えよう</p>
            </div>
            <div class="mt-auto flex items-center gap-1 text-primary text-label-md font-semibold group-hover:gap-2 transition-all">
                <span>クイズを始める</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
        </a>

        {{-- 語彙クイズ --}}
        <a href="/english/quiz/vocabulary"
           class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-8 flex flex-col gap-4 no-underline group">
            <div class="flex items-start justify-between">
                <div class="p-4 bg-primary/10 rounded-[0.75rem]">
                    <span class="material-symbols-outlined text-primary text-3xl">psychology</span>
                </div>
                <div class="text-right">
                    <p class="text-caption text-on-surface-variant">最近のスコア</p>
                    @if($recentVocabulary->isNotEmpty())
                    @php $latestV = $recentVocabulary->first(); @endphp
                    <p class="text-headline-md font-black text-primary">{{ $latestV->correct_count }}/{{ $latestV->total_questions }}</p>
                    @else
                    <p class="text-body-md text-on-surface-variant">未挑戦</p>
                    @endif
                </div>
            </div>
            <div>
                <h3 class="text-headline-md font-bold text-on-surface mb-2">英単語クイズ</h3>
                <p class="text-body-md text-on-surface-variant">英単語の意味を4択から選んで回答するクイズ</p>
            </div>
            <div class="mt-auto flex items-center gap-1 text-primary text-label-md font-semibold group-hover:gap-2 transition-all">
                <span>クイズを始める</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
        </a>

    </div>

</div>
@endsection
