@extends('layouts.app')

@section('title', 'Exam Overview')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">試験概要</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">試験概要</h1>
        <p class="text-body-md text-on-surface-variant">TOEIC・IELTSの試験形式・スコアの見方を確認しよう</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl">

        <a href="/english/overview/ielts"
           class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-8 flex flex-col gap-6 no-underline group">
            <div class="p-4 bg-primary/10 rounded-[0.75rem] w-fit">
                <span class="material-symbols-outlined text-primary text-4xl">record_voice_over</span>
            </div>
            <div>
                <h2 class="text-headline-md font-bold text-on-surface mb-2">IELTS とは</h2>
                <p class="text-body-md text-on-surface-variant">International English Language Testing System の試験形式・バンドスコア・各セクションの詳細を確認する</p>
            </div>
            <div class="flex items-center gap-1 text-primary text-label-md font-semibold group-hover:gap-2 transition-all">
                <span>詳細を見る</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
        </a>

        <a href="/english/overview/toeic"
           class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-8 flex flex-col gap-6 no-underline group">
            <div class="p-4 bg-primary/10 rounded-[0.75rem] w-fit">
                <span class="material-symbols-outlined text-primary text-4xl">menu_book</span>
            </div>
            <div>
                <h2 class="text-headline-md font-bold text-on-surface mb-2">TOEIC とは</h2>
                <p class="text-body-md text-on-surface-variant">Test of English for International Communication の試験形式・スコア換算・Part別の詳細を確認する</p>
            </div>
            <div class="flex items-center gap-1 text-primary text-label-md font-semibold group-hover:gap-2 transition-all">
                <span>詳細を見る</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
        </a>

    </div>

</div>
@endsection
