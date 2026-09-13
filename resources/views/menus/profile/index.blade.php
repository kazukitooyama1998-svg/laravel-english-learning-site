@extends('layouts.app')

@section('title', $user->name)

@section('content')
<main class="flex-1 flex justify-center px-4 py-12">
    <div class="w-full max-w-2xl">

        <x-english.breadcrumb>
            <a href="/english" class="hover:text-primary transition-colors no-underline">Home</a>
            <span class="mx-1">/</span>
            <span class="text-on-surface font-semibold">Profile</span>
        </x-english.breadcrumb>

        {{-- プロフィールヘッダーエリア --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-8 shadow-sm">
            <div class="flex flex-col items-center text-center gap-6">
                {{-- アイコン（選択済みキャラクター） --}}
                <x-character.badge :character="$user->character" size="xl" />
                {{-- 名前 --}}
                <div class="w-full">
                    <div class="flex items-center justify-center gap-3">
                        <h1 class="text-3xl font-bold text-on-surface">{{ $user->name }}</h1>
                        <a href="{{ route('profile.edit') }}"
                           class="px-4 py-1.5 bg-primary/10 text-primary hover:bg-primary/20 font-bold text-sm rounded-lg transition-colors">
                           Edit
                        </a>
                    </div>
                    <p class="text-body-md text-on-surface-variant mt-1">
                        {{ $user->character['name'] }}（{{ $user->character['species'] }}）と冒険中
                        <a href="{{ route('character.select', ['redirect' => 'profile']) }}" class="text-primary font-bold hover:opacity-80">キャラクターを変える</a>
                    </p>
                </div>
            </div>
        </div>
        {{-- 英語の森（3D アイランド）への入口 --}}
        <a href="{{ route('english.forest') }}"
           class="group relative block mt-5 overflow-hidden rounded-2xl border border-outline-variant bg-gradient-to-br from-[#dff2fb] via-[#eaf3e0] to-[#f6e9cd] p-6 sm:p-8 no-underline shadow-biscuit transition-transform duration-200 hover:-translate-y-1">

            {{-- 背景の島イラスト（原始図形のみで構成） --}}
            <svg class="pointer-events-none absolute -right-6 -bottom-8 w-56 h-56 opacity-70" viewBox="0 0 200 200" aria-hidden="true">
                <ellipse cx="100" cy="150" rx="86" ry="34" fill="#74cfd8"/>
                <ellipse cx="100" cy="143" rx="70" ry="27" fill="#ecdcb4"/>
                <ellipse cx="100" cy="136" rx="55" ry="20" fill="#7fa65c"/>
                <rect x="96" y="96" width="7" height="34" rx="3" fill="#9a7350"/>
                <circle cx="99" cy="92" r="20" fill="#5c7250"/>
                <circle cx="79" cy="102" r="13" fill="#6e9c4f"/>
                <circle cx="119" cy="100" r="12" fill="#6e9c4f"/>
                <rect x="47" y="112" width="5" height="20" rx="2" fill="#a1815f"/>
                <circle cx="49" cy="110" r="11" fill="#4a7350"/>
                <rect x="146" y="114" width="5" height="18" rx="2" fill="#a1815f"/>
                <circle cx="148" cy="112" r="10" fill="#4a7350"/>
            </svg>

            <div class="relative">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/80 text-primary text-caption font-bold">
                    <span class="material-symbols-outlined text-[16px]">forest</span> 3D SPACE
                </span>

                <h2 class="font-display text-headline-md font-bold text-on-surface mt-3">英語の森</h2>
                <p class="text-body-md text-on-surface-variant mt-1 max-w-sm leading-relaxed">
                    小さな島を歩いて、学習メニューへ出かけましょう。<br class="hidden sm:block">
                    島に入れるのはログイン中のあなただけです。
                </p>

                <span class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-label-md shadow-[0_3px_0_#2f496b] transition-transform duration-200 group-hover:-translate-y-0.5">
                    <span class="material-symbols-outlined text-[18px]">sailing</span> 島へ行く
                </span>
            </div>
        </a>

        {{-- 登録情報（Editで編集する項目の表示） --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-8 shadow-sm mt-5">
            <h2 class="text-2xl font-bold text-on-surface text-center pb-8">Profile Information</h2>
            <dl class="divide-y divide-outline-variant">
                <div class="py-4 flex items-center justify-between gap-4">
                    <dt class="text-body-md font-bold text-on-surface-variant">Name</dt>
                    <dd class="text-body-md text-on-surface text-right">{{ $user->name }}</dd>
                </div>
                <div class="py-4 flex items-center justify-between gap-4">
                    <dt class="text-body-md font-bold text-on-surface-variant">E-mail</dt>
                    <dd class="text-body-md text-on-surface text-right">{{ $user->email }}</dd>
                </div>
                <div class="py-4 flex items-start justify-between gap-4">
                    <dt class="text-body-md font-bold text-on-surface-variant shrink-0">Introduction</dt>
                    <dd class="text-body-md text-on-surface text-right">{{ $user->introduction ?? 'No introduction yet.' }}</dd>
                </div>
            </dl>
        </div>

    </div>
</main>
@endsection