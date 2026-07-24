import { post }      from '../utils/api.js';
import { showToast } from '../utils/toast.js';

/**
 * Alpine.data component for Vocabulary Quiz / Quiz section (S22).
 * Local judgment per question → all answers submitted at once at the end.
 *
 * Config:
 *   questions – [{id, word, correct, options: string[]}]
 *   submitUrl – POST /english/quiz/vocabulary/submit
 */
export function quizVocabularyApp(config) {
    return {
        questions:    config.questions ?? [],
        submitUrl:    config.submitUrl,

        currentIndex:   0,
        selectedOption: null,
        isAnswered:     false,
        isCorrect:      false,
        score:          0,
        isComplete:     false,
        isLoading:      false,
        gainedXp:       0,
        startTime:      null,
        answers:        [],

        get current()         { return this.questions[this.currentIndex]; },
        get progressPercent() {
            return this.questions.length > 0
                ? Math.round((this.currentIndex / this.questions.length) * 100)
                : 0;
        },

        selectOption(idx) {
            if (!this.isAnswered) this.selectedOption = idx;
        },

        optionClass(idx) {
            if (this.isAnswered && this.current.options[idx] === this.current.correct) {
                return 'border-green-500 bg-green-50 text-green-800';
            }
            if (this.isAnswered && this.selectedOption === idx && this.current.options[idx] !== this.current.correct) {
                return 'border-error bg-error-container/50 text-error';
            }
            if (this.selectedOption === idx && !this.isAnswered) {
                return 'border-primary bg-primary/10';
            }
            return 'border-outline-variant bg-surface-container-lowest';
        },

        submitAnswer() {
            if (this.selectedOption === null || this.isAnswered) return;
            if (!this.startTime) this.startTime = Date.now();
            this.isAnswered = true;
            this.isCorrect  = this.current.options[this.selectedOption] === this.current.correct;
            if (this.isCorrect) this.score++;
            this.answers.push({
                word_id: this.current.id,
                answer:  this.current.options[this.selectedOption],
            });
        },

        nextQuestion() {
            if (!this.isAnswered) return;
            if (this.currentIndex < this.questions.length - 1) {
                this.currentIndex++;
                this.selectedOption = null;
                this.isAnswered     = false;
                this.isCorrect      = false;
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
            this.currentIndex   = 0;
            this.selectedOption = null;
            this.isAnswered     = false;
            this.isCorrect      = false;
            this.score          = 0;
            this.isComplete     = false;
            this.gainedXp       = 0;
            this.answers        = [];
            this.startTime      = null;
        },
    };
}
