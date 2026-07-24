import { post, submitForm } from '../utils/api.js';
import { showToast }        from '../utils/toast.js';

/**
 * Alpine.data component for TOEIC practice (S04).
 *
 * Config (passed from Blade via x-data):
 *   questions   – array of { id, question_text, explanation, options: [{id, label, option_text}] }
 *   submitUrl   – POST /english/toeic/{part}/answer  → JSON {is_correct, correct_option_id, explanation}
 *   completeUrl – POST /english/toeic/{part}/complete → redirect to result page
 *   resultUrl   – GET  /english/toeic/{part}/result
 */
export function toeicPractice(config) {
    return {
        questions:       config.questions ?? [],
        submitUrl:       config.submitUrl,
        completeUrl:     config.completeUrl,
        resultUrl:       config.resultUrl,

        currentIndex:    0,
        selectedId:      null,   // currently selected option ID (numeric)
        isAnswered:      false,
        isCorrect:       false,
        correctOptionId: null,
        explanation:     '',
        score:           0,
        isComplete:      false,
        isLoading:       false,

        get current()         { return this.questions[this.currentIndex]; },
        get progressPercent() {
            return this.questions.length > 0
                ? Math.round((this.currentIndex / this.questions.length) * 100)
                : 0;
        },

        selectOption(optionId) {
            if (!this.isAnswered) this.selectedId = optionId;
        },

        optionClass(optionId) {
            if (this.isAnswered && optionId === this.correctOptionId) {
                return 'border-green-500 bg-green-50 text-green-800';
            }
            if (this.isAnswered && this.selectedId === optionId && optionId !== this.correctOptionId) {
                return 'border-error bg-error-container/50 text-error';
            }
            if (this.selectedId === optionId && !this.isAnswered) {
                return 'border-primary bg-primary/10';
            }
            return 'border-outline-variant bg-surface-container-lowest';
        },

        async submitAnswer() {
            if (!this.selectedId || this.isAnswered || this.isLoading) return;
            this.isLoading = true;
            try {
                const data = await post(this.submitUrl, {
                    question_id:        this.current.id,
                    selected_option_id: this.selectedId,
                });
                this.isAnswered      = true;
                this.isCorrect       = data.is_correct;
                this.correctOptionId = data.correct_option_id;
                this.explanation     = data.explanation ?? '';
                if (data.is_correct) this.score++;
            } catch (err) {
                showToast(err.status === 422 ? 'バリデーションエラーが発生しました' : '回答の送信に失敗しました', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async nextQuestion() {
            if (!this.isAnswered) return;
            if (this.currentIndex < this.questions.length - 1) {
                this.currentIndex++;
                this.selectedId      = null;
                this.isAnswered      = false;
                this.isCorrect       = false;
                this.correctOptionId = null;
                this.explanation     = '';
            } else {
                this.isComplete = true;
                // Use form submit so the server-side redirect is followed naturally
                submitForm(this.completeUrl);
            }
        },
    };
}
