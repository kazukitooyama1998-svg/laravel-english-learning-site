@extends('layouts.app')

@section('title', 'キャラクターをえらぶ')

@section('content')
<main class="flex-1 flex justify-center px-4 py-12">
    <div class="w-full max-w-3xl">

        <x-english.breadcrumb>
            <a href="/english" class="hover:text-primary transition-colors no-underline">Home</a>
            <span class="mx-1">/</span>
            <span class="text-on-surface font-semibold">キャラクターをえらぶ</span>
        </x-english.breadcrumb>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-8 shadow-sm">
            <div class="text-center mb-8">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary text-caption font-bold">
                    <span class="material-symbols-outlined text-[16px]">pets</span> CHOOSE YOUR CHARACTER
                </span>
                <h1 class="font-display text-headline-md font-bold text-on-surface mt-3">
                    「英語の森」を歩く、あなたのキャラクターをえらんでください
                </h1>
                <p class="text-body-md text-on-surface-variant mt-2">
                    8匹の中から1匹だけ選べます。プロフィールのアイコンにも使われます。
                </p>
            </div>

            <form action="{{ route('character.select.store') }}" method="post"
                  x-data="{ selected: '{{ old('character_key', $user->character_key) }}' }">
                @csrf
                @if ($redirect)
                    <input type="hidden" name="redirect" value="{{ $redirect }}">
                @endif
                <input type="hidden" name="character_key" x-model="selected">

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                    @foreach ($characters as $key => $c)
                        <button type="button"
                                @click="selected = '{{ $key }}'"
                                class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 transition-all text-center"
                                :class="selected === '{{ $key }}'
                                    ? 'border-primary bg-primary/[0.06] shadow-biscuit -translate-y-0.5'
                                    : 'border-outline-variant hover:border-primary/40 hover:-translate-y-0.5'">
                            <x-character.badge :character="['key' => $key] + $c" size="lg" />
                            <span class="font-bold text-body-md text-on-surface leading-tight">{{ $c['name'] }}</span>
                            <span class="text-caption text-on-surface-variant leading-tight">{{ $c['species'] }}</span>
                            <span class="text-caption text-on-surface-variant/80 leading-snug">{{ $c['blurb'] }}</span>
                        </button>
                    @endforeach
                </div>

                @error('character_key')
                    <p class="text-error text-sm font-semibold text-center mt-4">{{ $message }}</p>
                @enderror

                <button type="submit"
                        :disabled="!selected"
                        :class="selected ? '' : 'opacity-40 pointer-events-none'"
                        class="biscuit-btn w-full text-label-md mt-8">
                    このキャラクターにきめる
                </button>
            </form>
        </div>
    </div>
</main>
@endsection
