@extends('layouts.app')

@section('title', 'IELTS - Target Score')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.ielts.index') }}" class="hover:text-primary transition-colors no-underline">IELTS</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.ielts.topic', $part) }}" class="hover:text-primary transition-colors no-underline">Part {{ $part }}</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold capitalize">{{ $topic }}</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">目標スコアを選択</h1>
        <p class="text-body-md text-on-surface-variant">
            <span class="font-semibold text-primary capitalize">{{ $topic }}</span> × IELTS Speaking Part {{ $part }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl">
        @foreach($scores as $scoreVal => $s)
        <a href="/english/ielts/speaking/{{ $part }}/{{ $topic }}/{{ $scoreVal }}/slides/1"
           class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-6 flex flex-col gap-3 no-underline group">
            <div class="flex items-center justify-between">
                <span class="text-display font-black text-primary">{{ $scoreVal }}</span>
                <span class="bg-primary/10 text-primary text-label-md font-bold px-3 py-1 rounded-[0.75rem]">{{ $s['level'] }}</span>
            </div>
            <p class="text-body-md text-on-surface-variant">{{ $s['desc'] }}</p>
            <div class="mt-auto flex items-center gap-1 text-primary text-label-md font-semibold group-hover:gap-2 transition-all">
                <span>このスコアで学ぶ</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
        </a>
        @endforeach
    </div>

</div>
@endsection
