/**
 * English Learning – Frontend Entry Point
 *
 * Registers Alpine.js components and auto-initialises the typing engine
 * when window.__TYPING_CONFIG__ is present on the page.
 */

import { toeicPractice }    from './toeic/practice.js';
import { flashcardApp }     from './vocabulary/flashcard.js';
import { spellingApp }      from './vocabulary/spelling.js';
import { vocabQuizApp }     from './vocabulary/quiz.js';
import { favoritesPage }    from './vocabulary/favorites.js';
import { quizSpellingApp }  from './quiz/spelling.js';
import { quizVocabularyApp } from './quiz/vocabulary.js';
import { initTypingEngine } from './engine/typing.js';

// ── Alpine component registration ────────────────────────────────────────
// alpine:init fires before Alpine initialises, so components are available
// even when Alpine is loaded via CDN with `defer`.
document.addEventListener('alpine:init', () => {
    Alpine.data('toeicPractice',    toeicPractice);
    Alpine.data('flashcardApp',     flashcardApp);
    Alpine.data('spellingApp',      spellingApp);
    Alpine.data('vocabQuizApp',     vocabQuizApp);
    Alpine.data('favoritesPage',    favoritesPage);
    Alpine.data('quizSpellingApp',  quizSpellingApp);
    Alpine.data('quizVocabularyApp', quizVocabularyApp);
});

// ── Typing engine auto-init ───────────────────────────────────────────────
// Pages that need the typing engine set window.__TYPING_CONFIG__ in an
// inline <script> block before this module runs.
document.addEventListener('DOMContentLoaded', () => {
    const cfg = window.__TYPING_CONFIG__;
    if (cfg && document.getElementById('typing-box')) {
        initTypingEngine(cfg);
    }
});
