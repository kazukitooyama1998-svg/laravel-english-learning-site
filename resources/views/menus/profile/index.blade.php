@extends('layouts.app')

@section('title', $user->name)

@section('content')
<main class="flex-1 flex justify-center px-4 py-12">
    <div class="w-full max-w-2xl">

        {{-- プロフィールヘッダーエリア --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-8 shadow-sm">
            <div class="flex flex-col items-center text-center gap-6">
                {{-- アバター --}}
                @if ($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover">
                @else
                    <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>
                @endif
                {{-- 名前・自己紹介 --}}
                <div class="w-full">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <h1 class="text-3xl font-bold text-on-surface">{{ $user->name }}</h1>
                        <a href="{{ route('profile.edit') }}" 
                           class="px-4 py-1.5 bg-primary/10 text-primary hover:bg-primary/20 font-bold text-sm rounded-lg transition-colors">
                           Edit
                        </a>
                    </div>
                    <p class="text-body-lg text-on-surface-variant max-w-md mx-auto">
                        {{ $user->introduction ?? 'No introduction yet.' }}
                    </p>
                </div>
            </div>
        </div>
        {{-- 学習履歴セクション --}}
        <ul class="space-y-4 mt-5">
            <div class="mt-8 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">history</span>
                <h2 class="text-title-lg font-bold text-on-surface">Recent Learning Activity</h2>
            </div>

            @forelse ($studyLogs as $log)
                <li class="py-4 border-b border-outline-variant last:border-0 flex justify-between items-center">
                    <div>
                        <span class="block text-body-md font-bold text-on-surface">
                            {{ $log->practice->title ?? 'Unknown Practice' }}
                        </span>
                        <span class="text-xs text-on-surface-variant">
                            Level: {{ $log->practice->level ?? '-' }}
                        </span>
                    </div>
                    <span class="text-label-md text-on-surface-variant">
                        {{ $log->created_at->format('M d, Y') }}
                    </span>
                </li>
            @empty
                {{-- データが0件の時の表示 --}}
                <li class="py-8 text-center text-on-surface-variant border border-dashed border-outline-variant rounded-xl">
                    <span class="material-symbols-outlined text-4xl mb-2 opacity-50">history_off</span>
                    <p>No learning records found yet.</p>
                </li>
            @endforelse
        </ul>

    </div>
</main>
@endsection