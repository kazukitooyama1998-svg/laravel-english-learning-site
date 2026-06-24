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
            @foreach ($studyLogs as $log)
                <li class="py-4 border-b border-outline-variant last:border-0">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            {{-- タイトルとレベル --}}
                            <span class="block text-body-md font-bold text-on-surface">
                                {{ $log->practice->title ?? 'Unknown Practice' }}
                            </span>
                            <span class="text-xs px-2 py-0.5 bg-surface-variant rounded-full text-on-surface-variant">
                                Level: {{ $log->practice->level ?? '-' }}
                            </span>
                        </div>
                        {{-- 日時 --}}
                        <span class="text-label-md text-on-surface-variant">
                            {{ $log->created_at->format('M d, Y') }}
                        </span>
                    </div>
                    
                    {{-- パラメータ類 --}}
                    <div class="flex gap-4 mt-2 text-sm text-on-surface-variant">
                        <span>WPM: <strong class="text-on-surface">{{ $log->wpm }}</strong></span>
                        <span>Accuracy: <strong class="text-on-surface">{{ $log->accuracy }}%</strong></span>
                        <span>Time: <strong class="text-on-surface">{{ $log->clear_time }}s</strong></span>
                    </div>
                </li>
            @endforeach
        </ul>

    </div>
</main>
@endsection