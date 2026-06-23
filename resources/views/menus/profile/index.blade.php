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
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                     alt="{{ $user->name }}" 
                     class="w-32 h-32 rounded-full border-4 border-primary/20 object-cover">
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
        <div class="mt-8">
            <h2 class="text-title-lg font-bold text-on-surface mb-6">Recent Study Activity</h2>
            
            <div class="bg-surface-container-low rounded-xl border border-outline-variant p-6 shadow-sm">
                @if(is_countable($studyLogs) && count($studyLogs) > 0)
                    <ul class="space-y-4">
                        @foreach ($studyLogs as $log)
                            <li class="flex items-center justify-between py-3 border-b border-outline-variant last:border-0">
                                <span class="text-body-md font-medium text-on-surface">{{ $log->topic }}</span>
                                <span class="text-label-md text-on-surface-variant">{{ $log->created_at->format('M d, Y') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-10">
                        <p class="text-on-surface-variant">No learning activity yet.</p>
                        <p class="text-sm text-on-surface-variant mt-2">Start your journey today!</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</main>
@endsection