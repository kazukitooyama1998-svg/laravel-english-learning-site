{{-- 親ファイル（共通レイアウト。例として layouts.app としていますが、実際のファイル名に合わせてください）を継承 --}}
@extends('layouts.app')

@section('content')
{{-- タイピング画面専用のスタイル（フォントや文字色の状態変化） --}}
<style>
  .canvas-shadow {
    box-shadow: 0px 10px 30px rgba(108, 91, 83, 0.05);
  }

  /* タイピングのフォントは親ファイルのJetBrains Monoの設定に合わせます */
  .font-mono-typing {
    font-family: "JetBrains Mono", "monospace";
  }

  .current-char {
    background-color: #fff1ec;
    border-bottom: 2px solid #a33900;
  }

  .correct-char {
    color: #16a34a;
  }

  .pending-char {
    opacity: 0.4;
  }
</style>

<main class="flex-1 flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-4xl">

    {{-- プロンプトセクション --}}
    <div class="mb-8 p-6 bg-surface-container-low border border-outline-variant rounded-full">
      <p class="text-label-md text-primary font-bold mb-2">
        IELTS Writing Task 2 Prompt
      </p>
      <p class="text-body-md text-on-surface">
        {{-- 将来的にコントローラーから動的に渡せるように変数化（未定義ならデフォルト値を表示） --}}
        {{ $prompt ?? 'Some people believe that studying abroad, such as in Cebu, offers more advantages than studying in one’s own country.' }}
      </p>
    </div>

    <div
      id="typing-box"
      class="p-8 bg-surface-container-lowest border border-outline-variant rounded-full canvas-shadow font-mono-typing text-body-lg leading-relaxed whitespace-pre-wrap break-words overflow-x-hidden"
    ></div>

  </div>
</main>

<div id="result-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-surface w-full max-w-md rounded-full p-6 shadow-xl text-center border border-outline-variant">

    <h2 class="text-headline-md text-on-surface mb-4">Result</h2>

    <div id="result-content" class="text-left space-y-2 text-on-surface-variant font-body-md"></div>

    <button
      id="restart-btn"
      class="mt-6 px-6 py-2.5 bg-primary text-on-primary font-label-md rounded-xl hover:opacity-90 transition-colors shadow-sm"
    >
      Restart
    </button>

  </div>
</div>

<script>
// 後からデータベース（コントローラー）と連携しやすいように、JavaScriptの変数にBladeの値を展開できるようにしています。
// ※ 現状は静的なテキストのままでも動くようにフォールバック（?? の後ろ）を入れています。
const text = `{!! $typingText ?? "In recent years, studying abroad has become increasingly popular among students who want to improve their language skills and broaden their perspectives. Cebu in the Philippines is widely recognized as an affordable destination for English learners. While some people believe that studying in one's own country is more practical and comfortable, I strongly agree that studying abroad offers greater benefits in terms of language development and personal growth.\n\nOne major advantage of studying abroad is the opportunity to use English in real-life situations every day. In Cebu, students are surrounded by an English-speaking environment not only in classrooms but also in daily life such as restaurants and public transportation. As a result, they can improve their communication skills more quickly than students who rely only on textbooks. In addition, many schools offer one-on-one lessons, allowing students to focus on their weaknesses effectively.\n\nAnother benefit is personal development through living in a foreign country. Students must manage their daily life independently, adapt to different cultures, and interact with people from various countries such as Korea, Taiwan, and Vietnam. These experiences help build confidence and global awareness, which are useful for future international careers.\n\nIn conclusion, I believe that studying abroad in places such as Cebu provides more valuable opportunities than studying in one's own country." !!}`;

const typingBox = document.getElementById('typing-box');
let currentIndex = 0;
let startTime = null;
let endTime = null;
let totalTyped = 0;
let correctTyped = 0;
let mistakes = 0;

function renderText() {
  let html = '';
  for (let i = 0; i < text.length; i++) {
    const char = text[i];
    const displayChar = char === ' ' ? '&nbsp;' :
                         char === '\n' ? '<br>' :
                         escapeHtml(char);

    if (char === '\n') {
      html += '<br>';
      continue;
    }

    if (i < currentIndex) {
      html += `<span class="correct-char">${displayChar}</span>`;
    } else if (i === currentIndex) {
      html += `<span class="current-char">${displayChar}</span>`;
    } else {
      html += `<span class="pending-char">${displayChar}</span>`;
    }
  }
  typingBox.innerHTML = html;
}

function escapeHtml(char) {
  if (char === '&') return '&amp;';
  if (char === '<') return '&lt;';
  if (char === '>') return '&gt;';
  if (char === '"') return '&quot;';
  if (char === "'") return '&#039;';
  return char;
}

renderText();

document.addEventListener('keydown', (e) => {
  // タイピング対象（inputやtextareaなど）以外への入力制限や、Escキー等での誤動作を防ぐ
  if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
  if (e.key === ' ') e.preventDefault();
  if (e.key.length > 1 && e.key !== 'Backspace' && e.key !== 'Enter') return;

  let key = e.key === 'Enter' ? '\n' : e.key;

  if (e.key === 'Backspace') {
    if (currentIndex > 0) currentIndex--;
    renderText();
    return;
  }

  if (currentIndex === 0 && startTime === null) {
    startTime = Date.now();
  }

  totalTyped++;

  if (key === text[currentIndex]) {
    correctTyped++;
    currentIndex++;
  } else {
    mistakes++;
  }

  renderText();

  if (currentIndex === text.length) {
    endTime = Date.now();
    const timeSec = (endTime - startTime) / 1000;
    const timeMin = timeSec / 60;
    const words = text.trim().split(/\s+/).length;
    const wpm = Math.round(words / timeMin);
    const accuracy = totalTyped > 0 ? Math.round((correctTyped / totalTyped) * 100) : 0;

    document.getElementById('result-content').innerHTML = `
      <p>⏱ <b>Time:</b> ${timeSec.toFixed(1)} sec</p>
      <p>⚡ <b>Speed:</b> ${wpm} WPM</p>
      <p>🎯 <b>Accuracy:</b> ${accuracy}%</p>
      <p>❌ <b>Mistakes:</b> ${mistakes}</p>
    `;

    document.getElementById('result-modal').classList.remove('hidden');
  }
});

document.getElementById('restart-btn').addEventListener('click', () => {
  currentIndex = 0;
  startTime = null;
  endTime = null;
  totalTyped = 0;
  correctTyped = 0;
  mistakes = 0;
  document.getElementById('result-modal').classList.add('hidden');
  renderText();
});
</script>
@endsection