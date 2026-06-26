@extends('layouts.app')

@section('content')
<main class="flex-1 flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-2xl">

    {{-- ヘッダー部分 --}}
    <div class="mb-8 text-center">
      <h1 class="text-headline-lg font-bold text-on-surface">Experience Leaderboard</h1>
      <p class="text-body-md text-on-surface-variant mt-2">Top players by total accumulated XP</p>
    </div>

    {{-- ランキングボード --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-surface-container-low border-b border-outline-variant text-label-md text-on-surface-variant">
            <th class="py-4 px-6 text-center w-20">Rank</th>
            <th class="py-4 px-6">User</th>
            <th class="py-4 px-6 text-right">Total XP</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/50 text-body-md text-on-surface">
          @forelse($rankings as $index => $user)
            @php
              $currentRank = ($rankings->currentPage() - 1) * $rankings->perPage() + $index + 1;
            @endphp
            
            <tr class="{{ $currentRank <= 3 ? 'bg-primary/5 font-semibold' : '' }} hover:bg-surface-container-low/50 transition-colors">
              <td class="py-4 px-6 text-center font-bold">
                @if($currentRank == 1) <span class="text-xl">🥇</span>
                @elseif($currentRank == 2) <span class="text-xl">🥈</span>
                @elseif($currentRank == 3) <span class="text-xl">🥉</span>
                @else <span class="text-on-surface-variant text-sm">{{ $currentRank }}</span>
                @endif
              </td>
              <td class="py-4 px-6">{{ $user->name }}</td>
              <td class="py-4 px-6 text-right text-primary font-bold">
                {{ number_format($user->total_xp) }} <span class="text-xs font-normal text-on-surface-variant">XP</span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="py-8 text-center text-on-surface-variant">No users found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      @if($rankings->hasPages())
        <div class="p-4 bg-surface-container-low border-t border-outline-variant flex justify-center">
          {{ $rankings->links() }}
        </div>
      @endif
    </div>

    <div class="mt-8 flex justify-center">
      <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-outline/10 text-on-surface hover:bg-outline/20 font-bold rounded-xl transition-colors text-base">
        Back to Dashboard
      </a>
    </div>

  </div>
</main>
@endsection