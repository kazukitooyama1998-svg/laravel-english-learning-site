@extends('layouts.app')

@section('title', 'TOEIC Learning')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">TOEIC</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">TOEIC 学習</h1>
        <p class="text-body-md text-on-surface-variant">TOEICの各Partを学習します</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($parts as $part)
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-6 flex flex-col gap-3 {{ !$part['available'] ? 'opacity-60' : '' }}">
            <div class="flex items-start justify-between">
                <div class="p-2 bg-primary/10 rounded-[0.75rem]">
                    <span class="material-symbols-outlined text-primary">
                        {{ $part['available'] ? 'menu_book' : 'headphones' }}
                    </span>
                </div>
                @if(!$part['available'])
                <span class="bg-surface-container-highest text-on-surface-variant text-[10px] font-bold px-2.5 py-1 rounded-[0.75rem] uppercase tracking-wider">Coming Soon</span>
                @endif
            </div>
            <div>
                <h3 class="font-bold text-on-surface text-base mb-0.5">{{ $part['name'] }}</h3>
                <p class="text-caption text-on-surface-variant">{{ $part['desc'] }}</p>
            </div>
            @if($part['available'])
            <div class="mt-auto">
                <x-english.progress-bar :percent="$part['progress']" label="進捗" />
                <a href="/english/toeic/{{ $part['part'] }}/slides/1"
                   class="mt-3 w-full bg-primary text-on-primary py-2.5 rounded-[0.75rem] font-label-md text-label-md flex items-center justify-center gap-2 no-underline hover:opacity-90 transition-all">
                    <span class="material-symbols-outlined text-sm">play_arrow</span>
                    Start
                </a>
            </div>
            @else
            <div class="mt-auto">
                <button disabled class="w-full bg-surface-container py-2.5 rounded-[0.75rem] font-label-md text-label-md text-on-surface-variant cursor-not-allowed flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">lock</span>
                    Coming Soon
                </button>
            </div>
            @endif
        </div>
        @endforeach
    </div>

</div>
@endsection
