@extends('layouts.app')

@section('title', 'Typing Practice')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">タイピング練習</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">タイピング練習</h1>
        <p class="text-body-md text-on-surface-variant">教材を選んでタイピングスキルを向上させよう</p>
    </div>

    @forelse($categories as $category)
    <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-6 flex flex-col gap-4 mb-6">
        <div>
            <h2 class="text-headline-md font-bold text-on-surface mb-1 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">keyboard</span>
                {{ $category->name }}
            </h2>
            <p class="text-caption text-on-surface-variant mb-3">{{ $category->description }}</p>
            <div class="flex items-center gap-1 text-caption text-on-surface-variant">
                <span class="material-symbols-outlined text-sm">style</span>
                {{ $category->materials_count }} フレーズを収録・毎回ランダムに10個出題されます
            </div>
        </div>
        <div>
            @if($category->materials_count > 0)
            <a href="{{ route('english.typing.category.practice', ['category' => $category->slug]) }}"
               class="inline-flex bg-primary text-on-primary py-2.5 px-6 rounded-[0.75rem] font-label-md text-label-md items-center justify-center gap-2 no-underline hover:opacity-90 transition-all">
                <span class="material-symbols-outlined text-sm">play_arrow</span>
                練習開始
            </a>
            @else
            <p class="text-body-md text-on-surface-variant">このカテゴリに教材はありません</p>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-16">
        <span class="material-symbols-outlined text-6xl text-on-surface-variant/40 mb-4 block">keyboard</span>
        <p class="text-body-lg text-on-surface-variant">タイピング教材がまだ登録されていません</p>
    </div>
    @endforelse

</div>
@endsection
