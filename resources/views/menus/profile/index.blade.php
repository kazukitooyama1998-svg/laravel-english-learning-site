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
                {{-- アバター --}}
                @if ($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover">
                @else
                    <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>
                @endif
                {{-- 名前 --}}
                <div class="w-full">
                    <div class="flex items-center justify-center gap-3">
                        <h1 class="text-3xl font-bold text-on-surface">{{ $user->name }}</h1>
                        <a href="{{ route('profile.edit') }}"
                           class="px-4 py-1.5 bg-primary/10 text-primary hover:bg-primary/20 font-bold text-sm rounded-lg transition-colors">
                           Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
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