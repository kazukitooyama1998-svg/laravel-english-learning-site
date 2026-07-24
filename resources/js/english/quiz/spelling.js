import { post }      from '../utils/api.js';
import { showToast } from '../utils/toast.js';

/**
 * Alpine.data component for Spelling Quiz (S21).
 * Local judgment per question → all answers submitted at once at the end.
 *
 * Config:
 *   words     – [{id, word, hint, example}]
 *   submitUrl – POST /english/quiz/spelling/submit
 */
export function quizSpellingApp(config) {
    return {
        words:     config.words ?? [],
        submitUrl: config.submitUrl,

        currentIndex: 0,
        userInput:    '',
        isAnswered:   false,
        isCorrect:    false,
        score:        0,
        isComplete:   false,
        isLoading:    false,
        gainedXp:     0,
        startTime:    null,
        answers:      [],

        get current()         { return this.words[this.currentIndex]; },
        get progressPercent() {
            return this.words.length > 0
                ? Math.round((this.currentIndex / this.words.length) * 100)
                : 0;
        },

        submitAnswer() {
            if (!this.userInput.trim() || this.isAnswered) return;
            if (!this.startTime) this.startTime = Date.now();
            this.isAnswered = true;
            this.isCorrect  = this.userInput.trim().toLowerCase() === this.current.word.toLowerCase();
            if (this.isCorrect) this.score++;
            this.answers.push({
                word_id: this.current.id,
                answer:  this.userInput.trim(),
            });
        },

        nextQuestion() {
            if (!this.isAnswered) return;
            if (this.currentIndex < this.words.length - 1) {
                this.currentIndex++;
                this.userInput   = '';
                this.isAnswered  = false;
                this.isCorrect   = false;
            } else {
                this.submitAll();
            }
        },

        async submitAll() {
            const durationSeconds = this.startTime
                ? Math.round((Date.now() - this.startTime) / 1000)
                : 0;
            this.isLoading = true;
            try {
                const data = await post(this.submitUrl, {
                    answers:          this.answers,
                    duration_seconds: durationSeconds,
                });
                this.gainedXp = data.xp_gained ?? 0;
            } catch {
                showToast('結果の保存に失敗しました', 'error');
            } finally {
                this.isLoading  = false;
                this.isComplete = true;
            }
        },

        restart() {
            this.currentIndex = 0;
            this.userInput    = '';
            this.isAnswered   = false;
            this.isCorrect    = false;
            this.score        = 0;
            this.isComplete   = false;
            this.gainedXp     = 0;
            this.answers      = [];
            this.startTime    = null;
        },
    };
}
