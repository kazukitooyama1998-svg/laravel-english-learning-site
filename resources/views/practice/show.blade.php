{{-- 親ファイル（共通レイアウト）を継承 --}}
@extends('layouts.app')

@section('content')
{{-- タイピング画面専用のスタイル --}}
<style>
  .canvas-shadow {
    box-shadow: 0px 10px 30px rgba(108, 91, 83, 0.05);
  }

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
    <div class="mb-8 p-6 bg-surface-container-low border border-outline-variant rounded-xl shadow-sm">
      <div class="flex items-center justify-between mb-3">
        <p id="practice-title" data-id="{{ $practice->id ?? 1 }}" class="text-label-md text-primary font-bold">
          IELTS Speaking Part 3 Practice
        </p>
        {{-- 現在のステップ表示 (例: Question 1 of 5) --}}
        <span id="question-progress" class="text-sm bg-primary/10 text-primary px-3 py-1 rounded-full font-bold">
          Loading...
        </span>
      </div>
      {{-- 現在の質問文を表示するエリア --}}
      <p id="current-question-text" class="text-headline-md text-on-surface font-semibold leading-snug">
        Loading question...
      </p>
    </div>

    {{-- タイピングエリア --}}
    <div
      id="typing-box"
      class="p-8 bg-surface-container-lowest border border-outline-variant rounded-xl canvas-shadow font-mono-typing text-body-lg leading-relaxed whitespace-pre-wrap break-words overflow-x-hidden min-h-[160px]"
    ></div>

    {{-- 💡 【配置修正】タイピングエリアの下部に、大きめのオレンジ色でQuitボタンを配置 --}}
    <div class="mt-6 flex justify-end">
      <a 
        href="{{ route('dashboard') }}" 
        id="quit-btn"
        class="px-6 py-2.5 bg-orange-600 text-white font-bold rounded-xl shadow-sm hover:bg-orange-700 transition-colors text-base"
      >
        Quit Practice
      </a>
    </div>

  </div>
</main>

{{-- リザルトモーダル --}}
<div id="result-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-[9999]">
  <div class="bg-surface w-full max-w-md rounded-xl p-6 shadow-xl text-center border border-outline-variant">

    <h2 class="text-headline-md text-on-surface mb-4 font-bold">Total Result</h2>

    <div id="result-content" class="text-left space-y-3 text-on-surface-variant font-body-md bg-surface-container-low p-4 rounded-xl border border-outline-variant/30"></div>

    <button
      id="restart-btn"
      class="mt-6 w-full py-3 bg-primary text-on-primary font-label-md rounded-xl hover:opacity-90 transition-colors shadow-sm font-bold text-lg"
    >
      Restart Practice
    </button>

  </div>
</div>

<script>
const rawText = `{!! $typingText ?? "[Question 1]\nWhy do some students perform better than others?\n[Answer]\nI think the main reason is motivation. Students who are interested in a subject are usually willing to spend more time studying it. For example, some students enjoy learning languages and therefore practice regularly outside school.\n\n[Question 2]\nShould schools focus more on practical skills?\n[Answer]\nYes, to some extent. Practical skills such as communication and financial management can help students in their daily lives. However, academic subjects are also important because they provide fundamental knowledge.\n\n[Question 3]\nHow has education changed in recent years?\n[Answer]\nEducation has become more technology-based. Many students now use online learning platforms and digital resources. As a result, learning is more flexible than before.\n\n[Question 4]\nIs university education necessary for everyone?\n[Answer]\nNo, I do not think so. Some people can build successful careers through vocational training or practical experience. It depends on the individual's goals and interests.\n\n[Question 5]\nWhat qualities make a good teacher?\n[Answer]\nA good teacher should be knowledgeable and patient. They should also be able to explain difficult concepts clearly. This helps students stay motivated and understand lessons better." !!}`;

function parseSpeakingText(raw) {
  const qMatches = [...raw.matchAll(/\[Question \d+\]\n([\s\S]*?)\n\[Answer\]\n([\s\S]*?)(?=(?:\[Question \d+\]|$))/g)];
  return qMatches.map(match => ({
    question: match[1].trim(),
    answer: match[2].trim()
  }));
}

const quizData = parseSpeakingText(rawText);

let currentStep = 0;        
let currentSubPhase = 'Q';  
let currentIndex = 0;       
let targetText = "";        

let startTime = null;
let totalDurationMs = 0;  
let totalTyped = 0;       
let correctTyped = 0;     
let totalMistakes = 0;    
let totalWordsCount = 0;  

const typingBox = document.getElementById('typing-box');
const questionProgress = document.getElementById('question-progress');
const currentQuestionText = document.getElementById('current-question-text');

function initStep() {
  if (currentStep >= quizData.length) {
    showFinalResult();
    return;
  }

  questionProgress.innerText = `Question ${currentStep + 1} of ${quizData.length}`;
  currentQuestionText.innerText = quizData[currentStep].question;
  
  if (currentSubPhase === 'Q') {
    targetText = quizData[currentStep].question;
  } else {
    targetText = quizData[currentStep].answer;
  }
  
  currentIndex = 0;
  renderText();
}

function renderText() {
  let html = '';
  for (let i = 0; i < targetText.length; i++) {
    const char = targetText[i];
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

document.addEventListener('keydown', (e) => {
  if (currentStep >= quizData.length) return; 
  if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
  if (e.key === ' ') e.preventDefault();
  if (e.key.length > 1 && e.key !== 'Backspace' && e.key !== 'Enter') return;

  let key = e.key === 'Enter' ? '\n' : e.key;

  if (e.key === 'Backspace') {
    if (currentIndex > 0) currentIndex--;
    renderText();
    return;
  }

  if (currentStep === 0 && currentSubPhase === 'Q' && currentIndex === 0 && startTime === null) {
    startTime = Date.now();
  }

  totalTyped++;

  if (key === targetText[currentIndex]) {
    correctTyped++;
    currentIndex++;
  } else {
    totalMistakes++;
  }

  renderText();

  if (currentIndex === targetText.length) {
    totalWordsCount += targetText.trim().split(/\s+/).length;
    
    if (currentSubPhase === 'Q') {
      currentSubPhase = 'A';
    } else {
      currentSubPhase = 'Q';
      currentStep++;
    }
    initStep();
  }
});

function showFinalResult() {
  const endTime = Date.now();
  totalDurationMs = endTime - startTime;
  
  const timeSec = totalDurationMs / 1000;
  const timeMin = timeSec / 60;
  
  const wpm = Math.round(totalWordsCount / timeMin);
  const accuracy = totalTyped > 0 ? Math.round((correctTyped / totalTyped) * 100) : 0;

  document.getElementById('result-content').innerHTML = `
    <p class="flex justify-between"><span>⏱ <b>Total Time:</b></span> <span>${timeSec.toFixed(1)} sec</span></p>
    <p class="flex justify-between"><span>⚡ <b>Average Speed:</b></span> <span class="text-primary font-bold">${wpm} WPM</span></p>
    <p class="flex justify-between"><span>🎯 <b>Overall Accuracy:</b></span> <span class="text-emerald-600 font-bold">${accuracy}%</span></p>
    <p class="flex justify-between"><span>❌ <b>Total Mistakes:</b></span> <span class="text-error">${totalMistakes} times</span></p>
  `;

  document.getElementById('result-modal').classList.remove('hidden');

  const practiceId = document.getElementById('practice-title').getAttribute('data-id');

  // 💡 ポップアップ(alert)を排除し、ログ記録のみにスッキリさせました
  fetch(`/practice/${practiceId}/result`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json'
    },
    body: JSON.stringify({
      wpm: wpm,
      accuracy: accuracy,
      clear_time: parseFloat(timeSec.toFixed(2)) 
    })
  })
  .then(async response => {
    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(`サーバーエラー (Status: ${response.status})\n内容: ${errorText}`);
    }
    return response.json();
  })
  .then(data => {
    if (data.success) {
      // 💡 成功時はコンソールログのみ（ユーザーの画面には何も邪魔なものを出しません）
      console.log('DB保存成功:', data.message);
    }
  })
  .catch(error => {
    // 💡 万が一の通信エラー時もコンソールに出力するだけに留めます
    console.error('詳細エラー:', error);
  });
}

document.getElementById('quit-btn').addEventListener('click', (e) => {
  if (startTime !== null && currentStep < quizData.length) {
    const confirmQuit = confirm("Are you sure you want to quit? Your practice progress will not be saved.");
    if (!confirmQuit) {
      e.preventDefault();
    }
  }
});

document.getElementById('restart-btn').addEventListener('click', () => {
  currentStep = 0;
  currentSubPhase = 'Q';
  currentIndex = 0;
  startTime = null;
  totalDurationMs = 0;
  totalTyped = 0;
  correctTyped = 0;
  totalMistakes = 0;
  totalWordsCount = 0;
  document.getElementById('result-modal').classList.add('hidden');
  initStep();
});

initStep();
</script>
@endsection