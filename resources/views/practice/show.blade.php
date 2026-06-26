@extends('layouts.app')

@section('content')
{{-- タイピング画面専用のスタイル --}}
<style>
  .canvas-shadow { box-shadow: 0px 10px 30px rgba(108, 91, 83, 0.05); }
  .font-mono-typing { font-family: "JetBrains Mono", "monospace"; }
  .current-char { background-color: #fff1ec; border-bottom: 2px solid #a33900; }
  .correct-char { color: #16a34a; }
  .pending-char { opacity: 0.4; }
</style>

<main class="flex-1 flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-4xl">
    <div class="mb-8 p-6 bg-surface-container-low border border-outline-variant rounded-xl shadow-sm">
      <div class="flex items-center justify-between mb-3">
        <p id="practice-title" data-id="{{ $practice->id ?? 1 }}" class="text-label-md text-primary font-bold">
          IELTS Speaking Part 3 Practice
        </p>
        <span id="question-progress" class="text-sm bg-primary/10 text-primary px-3 py-1 rounded-full font-bold">
          Loading...
        </span>
      </div>
      <p id="current-question-text" class="text-headline-md text-on-surface font-semibold leading-snug">
        Loading question...
      </p>
    </div>

    <div id="typing-box" class="p-8 bg-surface-container-lowest border border-outline-variant rounded-xl canvas-shadow font-mono-typing text-body-lg leading-relaxed whitespace-pre-wrap break-words overflow-x-hidden min-h-[160px]"></div>

    <div class="mt-6 flex justify-end">
      <a href="{{ route('dashboard') }}" id="quit-btn" class="px-6 py-2.5 bg-orange-600 text-white font-bold rounded-xl shadow-sm hover:bg-orange-700 transition-colors text-base">
        Quit Practice
      </a>
    </div>
  </div>
</main>

{{-- リザルトモーダル --}}
<div id="result-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-[9999]">
  <div class="bg-surface w-full max-w-md rounded-xl p-6 shadow-xl text-center border border-outline-variant">
    <h2 class="text-headline-md text-on-surface mb-4 font-bold">Practice Completed!</h2>

    {{-- モーダル内コンテンツ：XPとゲージのみ --}}
    <div id="result-content" class="text-left space-y-4 text-on-surface-variant">
      <div class="text-center py-4 bg-primary/10 rounded-xl">
        <p class="text-sm text-primary font-bold">XP GAINED</p>
        <p id="gained-xp-text" class="text-4xl font-black text-primary">Calculating...</p>
      </div>

      <div class="space-y-1">
        <div class="flex justify-between text-xs font-bold text-on-surface-variant">
          <span id="level-text">Level 1</span>
          <span>Next Level</span>
        </div>
        <div class="w-full bg-surface-container-high rounded-full h-3 overflow-hidden">
          <div id="xp-bar" class="bg-primary h-full transition-all duration-1000" style="width: 0%"></div>
        </div>
        <p id="xp-progress-text" class="text-right text-xs text-on-surface-variant">0 / 0 XP</p>
      </div>
    </div>

    <div class="flex gap-3 mt-6">
      <a href="{{ route('dashboard') }}" class="flex-1 py-3 text-center border border-outline rounded-xl font-bold">Dashboard</a>
      <button id="restart-btn" class="flex-1 py-3 bg-primary text-white rounded-xl font-bold">Restart</button>
    </div>
  </div>
</div>

<script>
// (既存の関数群は省略せず、そのまま維持してください)
const rawText = `{!! $typingText !!}`;
function parseSpeakingText(raw) {
  const qMatches = [...raw.matchAll(/\[Question \d+\]\n([\s\S]*?)\n\[Answer\]\n([\s\S]*?)(?=(?:\[Question \d+\]|$))/g)];
  return qMatches.map(match => ({ question: match[1].trim(), answer: match[2].trim() }));
}
const quizData = parseSpeakingText(rawText);
let currentStep = 0, currentSubPhase = 'Q', currentIndex = 0, targetText = "", startTime = null, totalDurationMs = 0, totalTyped = 0, correctTyped = 0, totalMistakes = 0, totalWordsCount = 0;
const typingBox = document.getElementById('typing-box'), questionProgress = document.getElementById('question-progress'), currentQuestionText = document.getElementById('current-question-text');

function initStep() {
  if (currentStep >= quizData.length) { showFinalResult(); return; }
  questionProgress.innerText = `Question ${currentStep + 1} of ${quizData.length}`;
  currentQuestionText.innerText = quizData[currentStep].question;
  targetText = (currentSubPhase === 'Q') ? quizData[currentStep].question : quizData[currentStep].answer;
  currentIndex = 0; renderText();
}

function renderText() {
  let html = '';
  for (let i = 0; i < targetText.length; i++) {
    const char = targetText[i], displayChar = char === ' ' ? '&nbsp;' : char === '\n' ? '<br>' : escapeHtml(char);
    if (char === '\n') { html += '<br>'; continue; }
    if (i < currentIndex) html += `<span class="correct-char">${displayChar}</span>`;
    else if (i === currentIndex) html += `<span class="current-char">${displayChar}</span>`;
    else html += `<span class="pending-char">${displayChar}</span>`;
  }
  typingBox.innerHTML = html;
}

function escapeHtml(char) {
  const map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
  return map[char] || char;
}

document.addEventListener('keydown', (e) => {
  if (currentStep >= quizData.length) return;
  if (e.key === ' ') e.preventDefault();
  if (e.key.length > 1 && e.key !== 'Backspace' && e.key !== 'Enter') return;
  let key = e.key === 'Enter' ? '\n' : e.key;
  if (e.key === 'Backspace') { if (currentIndex > 0) currentIndex--; renderText(); return; }
  if (currentStep === 0 && currentSubPhase === 'Q' && currentIndex === 0 && startTime === null) startTime = Date.now();
  totalTyped++;
  if (key === targetText[currentIndex]) { correctTyped++; currentIndex++; } else { totalMistakes++; }
  renderText();
  if (currentIndex === targetText.length) {
    totalWordsCount += targetText.trim().split(/\s+/).length;
    currentSubPhase = (currentSubPhase === 'Q') ? 'A' : 'Q';
    if (currentSubPhase === 'Q') currentStep++;
    initStep();
  }
});

function showFinalResult() {
  const endTime = Date.now();
  const timeSec = (endTime - startTime) / 1000;
  
  document.getElementById('result-modal').classList.remove('hidden');

  const practiceId = document.getElementById('practice-title').getAttribute('data-id');
  fetch(`/practice/${practiceId}/result`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({
        wpm: Math.round(totalWordsCount / (timeSec / 60)),
        accuracy: totalTyped > 0 ? Math.round((correctTyped / totalTyped) * 100) : 0,
        clear_time: parseFloat(timeSec.toFixed(2)) 
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      document.getElementById('gained-xp-text').innerText = `+${data.gained_xp} XP`;
      const progress = (data.total_xp / data.next_level_xp) * 100;
      document.getElementById('xp-bar').style.width = `${Math.min(progress, 100)}%`;
      document.getElementById('xp-progress-text').innerText = `${data.total_xp} / ${data.next_level_xp} XP`;
      document.getElementById('level-text').innerText = `Level ${data.level}`;
    }
  });
}

document.getElementById('restart-btn').addEventListener('click', () => { window.location.reload(); });
initStep();
</script>
@endsection