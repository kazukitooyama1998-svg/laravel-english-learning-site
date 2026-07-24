@extends('layouts.app')

@section('title', 'IELTS Speaking')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">IELTS</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">IELTS Speaking</h1>
        <p class="text-body-md text-on-surface-variant">スピーキングの各パートをタイピングで練習します</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($parts as $partNum => $p)
        <a href="/english/ielts/speaking/{{ $partNum }}/topic"
           class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-6 flex flex-col gap-4 no-underline group">
            <div class="flex items-start justify-between">
                <div class="p-3 bg-primary/10 rounded-[0.75rem]">
                    <span class="material-symbols-outlined text-primary text-2xl">{{ $p['icon'] }}</span>
                </div>
                @if($p['badge'])
                <span class="bg-surface-container-highest text-on-surface-variant text-[10px] font-bold px-2.5 py-1 rounded-[0.75rem] uppercase tracking-wider">{{ $p['badge'] }}</span>
                @endif
            </div>
            <div>
                <h3 class="text-headline-md font-bold text-on-surface mb-2">{{ $p['name'] }}</h3>
                <p class="text-body-md text-on-surface-variant">{{ $p['desc'] }}</p>
            </div>
            <div class="mt-auto flex items-center gap-1 text-primary text-label-md font-semibold group-hover:gap-2 transition-all">
                <span>始める</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
        </a>
        @endforeach
    </div>

</div>
@endsection
