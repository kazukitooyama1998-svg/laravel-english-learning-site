import { post }        from '../utils/api.js';
import { showXpModal } from '../utils/xpModal.js';
import { showToast }   from '../utils/toast.js';

/**
 * Parse "[Question N]\n...\n[Answer]\n...\n[Meaning]\n..." formatted text.
 * The [Meaning] section (Japanese translation of the answer) is optional —
 * IELTS materials don't include it, only typing-practice materials do.
 */
function parseSpeakingText(raw) {
    const pattern = /\[Question \d+\]\n([\s\S]*?)\n\[Answer\]\n([\s\S]*?)(?:\n\[Meaning\]\n([\s\S]*?))?(?=\[Question \d+\]|$)/g;
    const pairs = [];
    let m;
    while ((m = pattern.exec(raw)) !== null) {
        pairs.push({ question: m[1].trim(), answer: m[2].trim(), meaning: m[3] ? m[3].trim() : null });
    }
    return pairs;
}

function escapeHtml(char) {
    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[char] ?? char;
}

let audioCtx = null;

/** Short buzzer beep played when the user mistypes a character. */
function playMistakeSound() {
    try {
        audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
        const osc  = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = 'square';
        osc.frequency.value = 180;
        gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.15);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + 0.15);
    } catch {
        // Web Audio unsupported/blocked — fail silently, the red flash still shows.
    }
}

let mistakeFlashEl = null;

/** Flashes the whole screen red for ~1s when the user mistypes a character. */
function flashMistake() {
    if (!mistakeFlashEl) {
        mistakeFlashEl = document.createElement('div');
        mistakeFlashEl.id = 'typing-mistake-flash';
        Object.assign(mistakeFlashEl.style, {
            position: 'fixed',
            inset: '0',
            backgroundColor: 'rgba(220, 38, 38, 0.35)',
            pointerEvents: 'none',
            zIndex: '9998',
            opacity: '0',
        });
        document.body.appendChild(mistakeFlashEl);
    }
    // Reset without transition first so rapid repeated mistakes re-trigger the flash.
    mistakeFlashEl.style.transition = 'none';
    mistakeFlashEl.style.opacity = '0.6';
    void mistakeFlashEl.offsetWidth; // force reflow
    mistakeFlashEl.style.transition = 'opacity 1s ease-out';
    mistakeFlashEl.style.opacity = '0';
}

/**
 * Initialise the typing engine.
 *
 * @param {Object} config
 * @param {string} config.rawText      Raw typing text (Q&A format)
 * @param {string} config.storeUrl     POST endpoint for storeResult
 * @param {string} config.resultUrl    URL to navigate to after result saved
 * @param {boolean} [config.typeQuestion=true]  Whether the question part must also be typed.
 *                                              Set false when the question is just a
 *                                              (non-English) context label to display, not type.
 */
export function initTypingEngine({ rawText, storeUrl, resultUrl, typeQuestion = true }) {
    const typingBox          = document.getElementById('typing-box');
    const questionProgressEl = document.getElementById('question-progress');
    const questionTextEl     = document.getElementById('current-question-text');
    const meaningEl          = document.getElementById('current-meaning-text');
    const meaningWrapperEl   = document.getElementById('current-meaning-wrapper');
    const restartBtn         = document.getElementById('restart-btn');

    if (!typingBox || !rawText) return;

    const quizData = parseSpeakingText(rawText);
    if (quizData.length === 0) return;

    let currentStep    = 0;
    let subPhase       = typeQuestion ? 'Q' : 'A';   // 'Q' → question, 'A' → answer
    let cursorIdx      = 0;
    let targetText     = '';
    let startTime      = null;
    let totalTyped     = 0;
    let correctTyped   = 0;
    let totalWords     = 0;

    function renderText() {
        let html = '';
        for (let i = 0; i < targetText.length; i++) {
            const ch = targetText[i];
            if (ch === '\n') { html += '<br>'; continue; }
            const disp = ch === ' ' ? '&nbsp;' : escapeHtml(ch);
            if (i < cursorIdx) {
                html += `<span class="correct-char">${disp}</span>`;
            } else if (i === cursorIdx) {
                html += `<span class="current-char">${disp}</span>`;
            } else {
                html += `<span class="pending-char">${disp}</span>`;
            }
        }
        typingBox.innerHTML = html;
    }

    function loadStep() {
        if (currentStep >= quizData.length) { finalize(); return; }

        const pair = quizData[currentStep];
        if (questionProgressEl) {
            if (quizData.length > 1) {
                questionProgressEl.textContent = `Question ${currentStep + 1} of ${quizData.length}`;
            } else {
                questionProgressEl.style.display = 'none';
            }
        }
        if (questionTextEl) {
            questionTextEl.textContent = pair.question;
        }
        if (meaningEl && meaningWrapperEl) {
            meaningEl.textContent = pair.meaning ?? '';
            meaningWrapperEl.style.display = pair.meaning ? '' : 'none';
        }
        targetText = subPhase === 'Q' ? pair.question : pair.answer;
        cursorIdx  = 0;
        renderText();
    }

    async function finalize() {
        const endTime      = Date.now();
        const durationSec  = startTime ? Math.round((endTime - startTime) / 1000) : 0;
        const accuracy     = totalTyped > 0 ? Math.round((correctTyped / totalTyped) * 100) : 0;
        const wpm          = durationSec > 0 ? Math.round((totalWords / durationSec) * 60) : 0;

        try {
            const data = await post(storeUrl, {
                wpm,
                accuracy,
                time_seconds:      durationSec,
                duration_seconds:  durationSec,
            });

            // Merge computed stats into response for modal display
            showXpModal({ ...data, wpm, accuracy, time_seconds: durationSec }, () => {
                window.location.href = data.redirect_url ?? resultUrl;
            });
        } catch {
            showToast('結果の保存に失敗しました。ページを再読み込みしてください。', 'error');
        }
    }

    document.addEventListener('keydown', (e) => {
        if (currentStep >= quizData.length) return;
        if (e.key === ' ') e.preventDefault();
        if (e.key.length > 1 && e.key !== 'Backspace' && e.key !== 'Enter') return;

        const key = e.key === 'Enter' ? '\n' : e.key;

        if (e.key === 'Backspace') {
            if (cursorIdx > 0) cursorIdx--;
            renderText();
            return;
        }

        if (startTime === null) startTime = Date.now();

        totalTyped++;
        if (key === targetText[cursorIdx]) {
            correctTyped++;
            cursorIdx++;
        } else {
            playMistakeSound();
            flashMistake();
        }
        renderText();

        if (cursorIdx === targetText.length) {
            totalWords += targetText.trim().split(/\s+/).filter(Boolean).length;
            if (typeQuestion) {
                subPhase = subPhase === 'Q' ? 'A' : 'Q';
                if (subPhase === 'Q') currentStep++;
            } else {
                currentStep++;
            }
            loadStep();
        }
    });

    if (restartBtn) {
        restartBtn.addEventListener('click', () => window.location.reload());
    }

    loadStep();
}
