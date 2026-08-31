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
 *
 * Part3（会話問題）・Part4（トーク問題）のみ、1会話/1トーク（3問）をグループとしてまとめて表示・解答・提出する
 * （groupIndex / conversationGroups 系のプロパティ）。他のPartは1問ずつ進む従来のフロー
 * （currentIndex / current 系のプロパティ）を使う。
 */
export function toeicPractice(config) {
    return {
        questions:       config.questions ?? [],
        part:            config.part,
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

        // Part3・Part4専用（会話/トーク単位でのまとめて解答）
        groupIndex:          0,
        groupAnswers:        {}, // { [questionId]: selectedOptionId }
        groupSubmitted:      false,
        groupResults:        {}, // { [questionId]: { is_correct, correct_option_id, explanation } }
        lastPlayedPassageId: null, // 同じ会話グループでは自動再生を繰り返さない

        get current()         { return this.questions[this.currentIndex]; },
        get progressPercent() {
            return this.questions.length > 0
                ? Math.round((this.currentIndex / this.questions.length) * 100)
                : 0;
        },

        // questionsをpassage.idの連続区間ごとにグループ化する（Part3/4は1会話/1トーク=3問が連続している）
        get conversationGroups() {
            const groups = [];
            let group = null;
            this.questions.forEach(q => {
                const passageId = q.passage?.id ?? null;
                if (!group || group.passageId !== passageId) {
                    group = { passageId, questions: [] };
                    groups.push(group);
                }
                group.questions.push(q);
            });
            return groups;
        },
        get currentConversationGroup() {
            return this.conversationGroups[this.groupIndex] ?? { passageId: null, questions: [] };
        },
        get isGroupComplete() {
            const group = this.currentConversationGroup;
            return group.questions.length > 0 && group.questions.every(q => this.groupAnswers[q.id]);
        },

        init() {
            // ChromeはgetVoices()が非同期に読み込まれ、初回は空配列を返すことがある。
            // その場合は読み込み完了を待ってから1回だけ再生する（即時呼び出しと二重に
            // ならないよう、どちらか一方の経路でのみ autoPlayListening() を呼ぶ）
            if ('speechSynthesis' in window && window.speechSynthesis.getVoices().length === 0) {
                window.speechSynthesis.onvoiceschanged = () => {
                    window.speechSynthesis.onvoiceschanged = null;
                    this.autoPlayListening();
                };
            } else {
                this.autoPlayListening();
            }
            this.$watch('currentIndex', () => this.autoPlayListening());
            this.$watch('groupIndex', () => this.autoPlayListening());
        },

        selectOption(optionId) {
            if (!this.isAnswered) this.selectedId = optionId;
        },

        selectGroupOption(questionId, optionId) {
            if (!this.groupSubmitted) this.groupAnswers[questionId] = optionId;
        },

        getPreferredVoice() {
            return window.speechSynthesis.getVoices().find(voice => voice.name === 'Google US English');
        },

        speak(text) {
            if (!('speechSynthesis' in window)) return;
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            // ブラウザ既定のrate=1は速すぎるため、TOEIC本番相当（約140〜160 WPM）に近づける
            utterance.rate = 0.85;
            utterance.voice = this.getPreferredVoice();
            window.speechSynthesis.speak(utterance);
        },

        stopAudio() {
            if ('speechSynthesis' in window) window.speechSynthesis.cancel();
        },

        // Part1（写真描写問題）・Part2（応答問題）は本番の音声形式に合わせ、選択肢ごとに
        // 文字（A〜C/D）を読み上げてから一呼吸おいて文章を読み上げる。
        // Part2は選択肢の前に、質問文（question_text）を先に読み上げる。
        repeatAudio() {
            if (!('speechSynthesis' in window) || !this.current?.options) return;
            this.stopAudio();
            // Chromeはcancel()の直後にspeak()すると正しく停止・再生できないことがあるため、
            // 少し間を空けてから読み上げを開始する
            setTimeout(() => {
                if (this.part === 2 && this.current.question_text) {
                    this.speak(this.current.question_text);
                }
                this.current.options.forEach(option => {
                    this.speak(`${option.label}...`);
                    this.speak(option.option_text);
                });
            }, 50);
        },

        // Part3（会話問題）・Part4（トーク問題）：現在のグループの音声（passage.documents）を発言順に読み上げる
        repeatConversationAudio() {
            if (!('speechSynthesis' in window)) return;
            const firstQuestion = this.currentConversationGroup.questions[0];
            if (!firstQuestion?.passage?.documents) return;
            this.stopAudio();
            setTimeout(() => {
                firstQuestion.passage.documents.forEach(doc => this.speak(doc.body));
            }, 50);
        },

        autoPlayListening() {
            if (this.part === 3 || this.part === 4) {
                // 同じ会話/トークグループに留まる間は自動再生しない（本番同様、音声は1回だけ流れる想定。
                // 聞き直しはRepeat Audioボタンから可能）
                const passageId = this.currentConversationGroup.passageId;
                if (passageId !== null && passageId !== this.lastPlayedPassageId) {
                    this.lastPlayedPassageId = passageId;
                    this.repeatConversationAudio();
                }
                return;
            }
            if (this.current?.image_url || this.part === 2) this.repeatAudio();
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

        groupOptionClass(questionId, optionId) {
            const result   = this.groupResults[questionId];
            const selected = this.groupAnswers[questionId];
            if (this.groupSubmitted && result && optionId === result.correct_option_id) {
                return 'border-green-500 bg-green-50 text-green-800';
            }
            if (this.groupSubmitted && result && selected === optionId && optionId !== result.correct_option_id) {
                return 'border-error bg-error-container/50 text-error';
            }
            if (!this.groupSubmitted && selected === optionId) {
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

        // Part3・Part4：グループ内の3問をまとめて送信する（1問ずつ既存のsubmitUrlへPOST）
        async submitGroupAnswers() {
            if (!this.isGroupComplete || this.groupSubmitted || this.isLoading) return;
            this.isLoading = true;
            try {
                const group = this.currentConversationGroup;
                for (const q of group.questions) {
                    const data = await post(this.submitUrl, {
                        question_id:        q.id,
                        selected_option_id: this.groupAnswers[q.id],
                    });
                    this.groupResults[q.id] = data;
                    if (data.is_correct) this.score++;
                }
                this.groupSubmitted = true;
            } catch (err) {
                showToast(err.status === 422 ? 'バリデーションエラーが発生しました' : '回答の送信に失敗しました', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async nextQuestion() {
            if (!this.isAnswered) return;
            this.stopAudio();
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

        nextConversation() {
            if (!this.groupSubmitted) return;
            this.stopAudio();
            if (this.groupIndex < this.conversationGroups.length - 1) {
                this.groupIndex++;
                this.groupAnswers   = {};
                this.groupSubmitted = false;
                this.groupResults   = {};
            } else {
                this.isComplete = true;
                // Use form submit so the server-side redirect is followed naturally
                submitForm(this.completeUrl);
            }
        },
    };
}
