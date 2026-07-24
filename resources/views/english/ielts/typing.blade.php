@extends('layouts.app')

@section('title', 'IELTS - Typing Practice')

@section('content')
<style>
  .canvas-shadow { box-shadow: 0px 10px 30px rgba(108, 91, 83, 0.05); }
  .font-mono-typing { font-family: "JetBrains Mono", monospace; }
  .current-char  { background-color: #fff1ec; border-bottom: 2px solid #a33900; }
  .correct-char  { color: #16a34a; }
  .pending-char  { opacity: 0.4; }
</style>

{{-- Pass config to typing engine before DOMContentLoaded fires --}}
<script>
window.__TYPING_CONFIG__ = {
    rawText:   @json($rawText),
    storeUrl:  '{{ route('english.ielts.result.store', [$part, $topic, $score]) }}',
    resultUrl: '{{ route('english.ielts.result', [$part, $topic, $score]) }}',
};
</script>

<div class="flex-1 flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-4xl">

    {{-- IELTS ヘッダー --}}
    <div class="mb-4 text-center">
      <span class="inline-flex items-center gap-2 bg-primary/10 text-primary text-label-md font-bold px-4 py-1.5 rounded-[0.75rem]">
        <span class="material-symbols-outlined text-sm">record_voice_over</span>
        {{ ucfirst($topic) }} × IELTS {{ $score }}
      </span>
    </div>

    <div class="mb-8 p-6 bg-surface-container-low rounded-[0.5rem] shadow-sm">
      <div class="flex items-center justify-between mb-3">
        <p class="text-label-md text-primary font-bold">
          IELTS Speaking Part {{ $part }} - {{ ucfirst($topic) }}
        </p>
        <span id="question-progress" class="text-sm bg-primary/10 text-primary px-3 py-1 rounded-[0.75rem] font-bold">
          Loading...
        </span>
      </div>
      <p id="current-question-text" class="text-headline-md text-on-surface font-semibold leading-snug whitespace-pre-line">
        Loading question...
      </p>
    </div>

    <div id="typing-box"
         class="p-8 bg-surface-container-lowest rounded-[0.5rem] canvas-shadow font-mono-typing text-body-lg leading-relaxed whitespace-pre-wrap break-words overflow-x-hidden min-h-[160px]">
    </div>

    <div class="mt-6 flex justify-end">
      <a href="{{ route('english.ielts.index') }}"
         class="px-6 py-2.5 bg-orange-600 text-white font-bold rounded-[0.5rem] shadow-sm hover:bg-orange-700 transition-colors text-base">
        Quit Practice
      </a>
    </div>
  </div>
</div>

{{-- リザルトモーダル --}}
<div id="result-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-[9999]">
  <div class="bg-surface w-full max-w-md rounded-[0.5rem] p-6 shadow-xl text-center">
    <h2 class="text-headline-md text-on-surface mb-4 font-bold">Practice Completed! 🎉</h2>
    <div class="text-left space-y-4 text-on-surface-variant">

      {{-- Stats --}}
      <div class="grid grid-cols-3 gap-3 text-center">
        <div class="bg-surface-container-low rounded-[0.5rem] p-3">
          <p class="text-caption text-on-surface-variant">WPM</p>
          <p id="result-wpm" class="text-headline-md font-black text-on-surface">-</p>
        </div>
        <div class="bg-surface-container-low rounded-[0.5rem] p-3">
          <p class="text-caption text-on-surface-variant">正答率</p>
          <p id="result-accuracy" class="text-headline-md font-black text-on-surface">-</p>
        </div>
        <div class="bg-surface-container-low rounded-[0.5rem] p-3">
          <p class="text-caption text-on-surface-variant">時間</p>
          <p id="result-time" class="text-headline-md font-black text-on-surface">-</p>
        </div>
      </div>

      {{-- XP --}}
      <div class="text-center py-4 bg-primary/10 rounded-[0.5rem]">
        <p class="text-sm text-primary font-bold">XP GAINED</p>
        <p id="gained-xp-text" class="text-4xl font-black text-primary">Calculating...</p>
      </div>

      {{-- Level bar --}}
      <div class="space-y-1">
        <div class="flex justify-between text-xs font-bold text-on-surface-variant">
          <span id="level-text">Level 1</span>
          <span>Next Level</span>
        </div>
        <div class="w-full bg-surface-container-high rounded-[0.75rem] h-3 overflow-hidden">
          <div id="xp-bar" class="bg-primary h-full transition-all duration-1000" style="width: 0%"></div>
        </div>
        <p id="xp-progress-text" class="text-right text-xs text-on-surface-variant">0 / 500 XP</p>
      </div>
    </div>

    <div class="flex gap-3 mt-6">
      <a href="{{ route('english.ielts.index') }}"
         class="flex-1 py-3 text-center bg-surface-container-lowest rounded-[0.5rem] shadow-sm font-bold no-underline text-on-surface">
        IELTS メニュー
      </a>
      <button id="restart-btn"
              class="flex-1 py-3 bg-surface-container text-on-surface rounded-[0.5rem] font-bold shadow-sm">
        もう一度
      </button>
      <button id="continue-btn"
              class="flex-1 py-3 bg-primary text-white rounded-[0.5rem] font-bold">
        結果を見る
      </button>
    </div>
  </div>
</div>

@endsection
