@extends('layouts.app')

@section('content')
<main class="flex-1 flex justify-center px-4 py-12">
  <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-3 gap-8">
    
    {{-- 左側：フォロー中のユーザーリスト --}}
    <div class="md:col-span-2">
      <div class="mb-6">
        <h1 class="text-headline-lg font-bold text-on-surface">Connections</h1>
        <p class="text-body-md text-on-surface-variant mt-1">People you follow</p>
      </div>

      <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-6">
        <h2 class="text-title-md font-bold text-on-surface mb-4">Following ({{ $followings->total() }})</h2>
        
        <ul class="divide-y divide-outline-variant/50">
          @forelse($followings as $following)
            <li class="py-4 flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary text-md">
                  {{ mb_substr($following->name, 0, 1) }}
                </div>
                <div>
                  <p class="text-body-lg font-semibold text-on-surface">{{ $following->name }}</p>
                  <p class="text-label-sm text-on-surface-variant">
                    {{ Auth::user()->isMutualFollow($following->id) ? 'Mutual connection' : 'Following' }}
                  </p>
                </div>
              </div>

              <div class="flex items-center space-x-2 shrink-0">
                {{-- 💡 相互フォローの場合のみChatボタンを表示 --}}
                @if(Auth::user()->isMutualFollow($following->id))
                    <a href="{{ route('partners.chat', $following->id) }}" class="px-3 py-1.5 bg-primary/10 text-primary hover:bg-primary/20 font-bold text-xs rounded-lg transition-colors no-underline">
                        Chat
                    </a>
                @endif
                
                {{-- アンフォローボタン --}}
                <form action="{{ route('unfollow', $following->id) }}" method="POST" onsubmit="return confirm('Stop following this user?');">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-error/10 text-error hover:bg-error/20 font-bold text-xs rounded-lg transition-colors">
                        Unfollow
                    </button>
                </form>
            </div>
            </li>
          @empty
            <li class="py-8 text-center text-on-surface-variant">
              You are not following anyone yet.
            </li>
          @endforelse
        </ul>

        @if($followings->hasPages())
          <div class="mt-6 flex justify-center">
            {{ $followings->links() }}
          </div>
        @endif
      </div>
    </div>

    {{-- 右側：新しいユーザーを探す --}}
    <div class="md:col-span-1">
      <div class="bg-surface-container-low border border-outline-variant rounded-xl p-5 shadow-sm sticky top-6">
        <h2 class="text-title-md font-bold text-on-surface mb-3">Find Buddies</h2>
        
        <form action="{{ route('partners.index') }}" method="GET" class="mb-4 flex items-center gap-1.5">
          <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Name..." class="min-w-0 flex-1 px-3 py-2 text-sm bg-surface-container-lowest border border-outline rounded-lg outline-none focus:border-primary">
          <button type="submit" class="shrink-0 px-2.5 py-2 bg-outline/20 text-on-surface font-semibold text-xs rounded-lg">Search</button>
        </form>

        <ul class="space-y-4">
          @forelse($suggestedUsers as $suggested)
            <li class="flex items-center justify-between gap-2">
              <div class="flex items-center space-x-3 min-w-0 flex-1">
                <div class="shrink-0 w-9 h-9 rounded-full bg-outline/20 flex items-center justify-center font-bold text-on-surface-variant text-sm">
                  {{ mb_substr($suggested->name, 0, 1) }}
                </div>
                <span class="text-body-md font-medium text-on-surface truncate">{{ $suggested->name }}</span>
              </div>
              
              {{-- フォローボタン --}}
              <form action="{{ route('follow', $suggested->id) }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" class="px-3 py-1.5 bg-primary text-on-primary font-bold text-xs rounded-lg hover:bg-primary/90">
                    Follow
                </button>
              </form>
            </li>
          @empty
            <li class="text-sm text-on-surface-variant py-4 text-center">No users found.</li>
          @endforelse
        </ul>
      </div>
    </div>

  </div>
</main>
@endsection