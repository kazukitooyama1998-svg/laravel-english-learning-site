import { post }      from '../utils/api.js';
import { showToast } from '../utils/toast.js';

/**
 * Alpine.data component for Spelling Practice (S14).
 * Each answer is validated server-side (updates user_word_progress).
 * XP is awarded once when all questions are done via completeUrl.
 *
 * Config:
 *   words       – [{id, word, hint, example}]
 *   checkUrl    – POST /english/vocabulary/{level}/spelling/check
 *   completeUrl – POST /english/vocabulary/{level}/spelling/complete
 */
export function spellingApp(config) {
    return {
        words:       config.words ?? [],
        checkUrl:    config.checkUrl,
        completeUrl: config.completeUrl,

        currentIndex: 0,
        userInput:    '',
        isAnswered:   false,
        isCorrect:    false,
        correctWord:  '',
        score:        0,
        isComplete:   false,
        isLoading:    false,
        gainedXp:     0,
        startTime:    null,

        get current()         { return this.words[this.currentIndex]; },
        get progressPercent() {
            return this.words.length > 0
                ? Math.round((this.currentIndex / this.words.length) * 100)
                : 0;
        },

        async submitAnswer() {
            if (!this.userInput.trim() || this.isAnswered || this.isLoading) return;
            if (!this.startTime) this.startTime = Date.now();
            this.isLoading = true;
            try {
                const data = await post(this.checkUrl, {
                    word_id: this.current.id,
                    answer:  this.userInput.trim(),
                });
                this.isAnswered  = true;
                this.isCorrect   = data.is_correct;
                this.correctWord = data.correct_word;
                if (data.is_correct) this.score++;
            } catch {
                showToast('回答の送信に失敗しました', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async nextQuestion() {
            if (this.currentIndex < this.words.length - 1) {
                this.currentIndex++;
                this.userInput   = '';
                this.isAnswered  = false;
                this.isCorrect   = false;
                this.correctWord = '';
            } else {
                await this.finalize();
            }
        },

        async finalize() {
            const durationSeconds = this.startTime
                ? Math.round((Date.now() - this.startTime) / 1000)
                : 0;
            try {
                const data = await post(this.completeUrl, {
                    correct_count:    this.score,
                    duration_seconds: durationSeconds,
                });
                this.gainedXp = data.gained_xp ?? 30;
            } catch {
                this.gainedXp = 30;
            }
            this.isComplete = true;
        },

        restart() {
            this.currentIndex = 0;
            this.userInput    = '';
            this.isAnswered   = false;
            this.isCorrect    = false;
            this.correctWord  = '';
            this.score        = 0;
            this.isComplete   = false;
            this.gainedXp     = 0;
            this.startTime    = null;
        },
    };
}
