@extends('layouts.app')

@section('content')
<main class="flex-1 flex justify-center px-4 py-12">
  <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-3 gap-8">
    
    {{-- 左側・中央：フレンドリスト (2カラム分) --}}
    <div class="md:col-span-2">
      <div class="mb-6">
        <h1 class="text-headline-lg font-bold text-on-surface">Friends</h1>
        <p class="text-body-md text-on-surface-variant mt-1">Your study buddies</p>
      </div>

      <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-6">
        <h2 class="text-title-md font-bold text-on-surface mb-4">My Friends ({{ $friends->total() }})</h2>
        
        <ul class="divide-y divide-outline-variant/50">
          @forelse($friends as $friend)
            <li class="py-4 flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary text-md">
                  {{ mb_substr($friend->name, 0, 1) }}
                </div>
                <div>
                  <p class="text-body-lg font-semibold text-on-surface">{{ $friend->name }}</p>
                  <p class="text-label-sm text-on-surface-variant">Active user</p>
                </div>
              </div>
              
              {{-- フレンド削除ボタン --}}
              <form action="{{ route('friends.remove', $friend->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this friend?');">
                @csrf
                <button type="submit" class="px-3 py-1.5 bg-error/10 text-error hover:bg-error/20 font-bold text-xs rounded-lg transition-colors">
                  Unfriend
                </button>
              </form>
            </li>
          @empty
            <li class="py-8 text-center text-on-surface-variant">
              No friends added yet. Find some buddies to compete with!
            </li>
          @endforelse
        </ul>

        {{-- Bootstrapページネーション --}}
        @if($friends->hasPages())
          <div class="mt-6 d-flex justify-content-center flex justify-center">
            {{ $friends->links() }}
          </div>
        @endif
      </div>
    </div>

    {{-- 右側：新しくフレンドを探す (1カラム分) --}}
    <div class="md:col-span-1">
      <div class="bg-surface-container-low border border-outline-variant rounded-xl p-5 shadow-sm sticky top-6">
        <h2 class="text-title-md font-bold text-on-surface mb-3">Find Buddies</h2>
        
        {{-- 🔍 ユーザー検索ボックス（ボタンのはみ出しを防止するレイアウトに調整） --}}
        <form action="{{ route('friends.index') }}" method="GET" class="mb-4 flex items-center gap-1.5">
          <input 
            type="text" 
            name="search" 
            value="{{ $search }}"
            placeholder="Name..." 
            class="min-w-0 flex-1 px-3 py-2 text-sm bg-surface-container-lowest border border-outline rounded-lg outline-none focus:border-primary"
          >
          <button type="submit" class="shrink-0 px-2.5 py-2 bg-outline/20 text-on-surface font-semibold text-xs rounded-lg hover:bg-outline/30 transition-colors">
            Search
          </button>
        </form>

        <ul class="space-y-4">
          @forelse($suggestedUsers as $suggested)
            <li class="flex items-center justify-between gap-2">
              {{-- ユーザー名エリア：横幅いっぱいに広がった時に自動で「...」にする設定 --}}
              <div class="flex items-center space-x-3 min-w-0 flex-1">
                <div class="shrink-0 w-9 h-9 rounded-full bg-outline/20 flex items-center justify-center font-bold text-on-surface-variant text-sm">
                  {{ mb_substr($suggested->name, 0, 1) }}
                </div>
                {{-- 💡 truncate クラス（またはCSS）で名前が長い場合は「...」に省略 --}}
                <span class="text-body-md font-medium text-on-surface truncate flex-1" title="{{ $suggested->name }}">
                  {{ $suggested->name }}
                </span>
              </div>
              
              {{-- フレンド追加ボタン：名前が長くてもボタンのサイズをキープする設定 --}}
              <form action="{{ route('friends.add', $suggested->id) }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" class="px-3 py-1.5 bg-primary text-on-primary font-bold text-xs rounded-lg hover:bg-primary/90 transition-colors shadow-sm">
                  Add
                </button>
              </form>
            </li>
          @empty
            <li class="text-sm text-on-surface-variant py-4 text-center">
              No users found.
            </li>
          @endforelse
        </ul>
      </div>
    </div>

  </div>
</main>
@endsection