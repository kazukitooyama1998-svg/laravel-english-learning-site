@extends('layouts.app')

@section('content')
<main class="flex-1 flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-2xl">

    {{-- ヘッダー部分 --}}
    <div class="mb-6 text-center">
      <h1 class="text-headline-lg font-bold text-on-surface">Leaderboard</h1>
      <p class="text-body-md text-on-surface-variant mt-2">Top 50 Typing Records by Topic</p>
    </div>

    {{-- 💡 【追加】教材切り替えプルダウンセクション --}}
    <div class="mb-6 flex justify-end">
      <div class="w-full sm:w-72">
        <label for="practice-selector" class="block text-sm font-medium text-on-surface-variant mb-1.5">Select Topic:</label>
        <select 
          id="practice-selector" 
          class="form-select w-full px-4 py-2.5 bg-surface-container-lowest border border-outline rounded-xl text-body-md text-on-surface shadow-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer"
          onchange="location.href = '{{ route('ranking.index') }}?practice_id=' + this.value;"
        >
          @foreach($practices as $practice)
            <option 
              value="{{ $practice->id }}" 
              {{ $practice->id == $selectedPracticeId ? 'selected' : '' }}
            >
              {{ $practice->title ?? $practice->name }} {{-- カラム名に合わせて調整してください --}}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    {{-- ランキングボード --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-surface-container-low border-b border-outline-variant text-label-md text-on-surface-variant">
            <th class="py-4 px-6 text-center w-20">Rank</th>
            <th class="py-4 px-6">User</th>
            <th class="py-4 px-6 text-right">Speed</th>
            <th class="py-4 px-6 text-right">Accuracy</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/50 text-body-md text-on-surface">
          @forelse($rankings as $index => $rank)
            @php
              $currentRank = ($rankings->currentPage() - 1) * $rankings->perPage() + $index + 1;
            @endphp
            
            <tr class="{{ $currentRank <= 3 ? 'bg-primary/5 font-semibold' : '' }} hover:bg-surface-container-low/50 transition-colors">
              <td class="py-4 px-6 text-center font-bold">
                @if($currentRank == 1)
                  <span class="text-xl">🥇</span>
                @elseif($currentRank == 2)
                  <span class="text-xl">🥈</span>
                @elseif($currentRank == 3)
                  <span class="text-xl">🥉</span>
                @else
                  <span class="text-on-surface-variant text-sm">{{ $currentRank }}</span>
                @endif
              </td>
              <td class="py-4 px-6">
                {{ $rank->user->name ?? 'Unknown User' }}
              </td>
              <td class="py-4 px-6 text-right text-primary font-bold">
                {{ $rank->wpm }} <span class="text-xs font-normal text-on-surface-variant">WPM</span>
              </td>
              <td class="py-4 px-6 text-right text-emerald-600">
                {{ $rank->accuracy }}%
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="py-8 text-center text-on-surface-variant">
                No records found for this topic yet.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      {{-- Bootstrapのページネーションボタン（?practice_id=X も自動保持されます） --}}
      @if($rankings->hasPages())
        <div class="p-4 bg-surface-container-low border-t border-outline-variant d-flex justify-content-center flex justify-center">
          {{ $rankings->links() }}
        </div>
      @endif
    </div>

    {{-- ダッシュボードに戻るボタン --}}
    <div class="mt-8 flex justify-center">
      <a 
        href="{{ route('dashboard') }}" 
        class="px-6 py-2.5 bg-outline/10 text-on-surface hover:bg-outline/20 font-bold rounded-xl transition-colors text-base"
      >
        Back to Dashboard
      </a>
    </div>

  </div>
</main>
@endsection