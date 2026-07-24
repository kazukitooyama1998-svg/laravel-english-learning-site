<?php

namespace App\Http\Controllers\English\Vocabulary;

use App\Http\Controllers\Controller;
use App\Models\English\UserSectionProgress;
use App\Models\English\UserWordProgress;
use App\Models\English\VocabularyWord;
use App\Services\English\StudyLogService;
use App\Services\English\XpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VocabularyController extends Controller
{
    public function __construct(
        private readonly XpService       $xpService,
        private readonly StudyLogService $studyLogService,
    ) {}

    /**
     * 英単語 レベル選択 (S12)
     * GET /english/vocabulary
     */
    public function index()
    {
        $user = Auth::user();

        // 各レベルの単語数と学習済み数を集計
        $wordCounts = VocabularyWord::selectRaw('exam_type, level, COUNT(*) as total')
            ->groupBy('exam_type', 'level')
            ->get()
            ->keyBy(fn($r) => strtolower($r->exam_type) . '-' . str_replace('.', '', $r->level));

        // フラッシュカード完了状況
        $completedLevels = $user->sectionProgress()
            ->where('section_type', UserSectionProgress::TYPE_VOCABULARY)
            ->where('is_completed', true)
            ->pluck('section_key')
            ->toArray();

        // 学習済み単語数（user_word_progress.status = 2: 覚えた）
        $learnedByLevel = $user->wordProgress()
            ->where('status', UserWordProgress::STATUS_LEARNED)
            ->join('vocabulary_words', 'user_word_progress.word_id', '=', 'vocabulary_words.id')
            ->selectRaw("CONCAT(LOWER(vocabulary_words.exam_type), '-', REPLACE(vocabulary_words.level, '.', '')) as slug, COUNT(*) as cnt")
            ->groupBy('slug')
            ->pluck('cnt', 'slug');

        $levels     = config('english.vocabulary_levels');
        $levelSlugs = config('english.vocabulary_level_slugs');

        $toeicLevels = [];
        $ieltsLevels = [];

        foreach ($levelSlugs as $slug) {
            $meta    = $levels[$slug];
            $total   = $wordCounts->get($slug)?->total ?? 0;
            $learned = $learnedByLevel->get($slug, 0);
            $done    = in_array($slug, $completedLevels);

            $entry = [
                'slug'      => $slug,
                'label'     => $meta['exam_type'] . ' ' . $meta['level'],
                'total'     => $total,
                'learned'   => $learned,
                'progress'  => $total > 0 ? (int) round($learned / $total * 100) : 0,
                'completed' => $done,
            ];

            if ($meta['exam_type'] === 'TOEIC') {
                $toeicLevels[] = $entry;
            } else {
                $ieltsLevels[] = $entry;
            }
        }

        $favoritesCount = $user->wordFavorites()->count();

        return view('english.vocabulary.index', compact('toeicLevels', 'ieltsLevels', 'favoritesCount'));
    }

    /**
     * お気に入り単語一覧 (S12-F)
     * GET /english/vocabulary/favorites
     */
    public function favorites()
    {
        $user = Auth::user();

        $favorites = $user->wordFavorites()
            ->with('word')
            ->get()
            ->filter(fn($f) => $f->word !== null)
            ->values();

        $learnedIds = $user->wordProgress()
            ->where('status', UserWordProgress::STATUS_LEARNED)
            ->pluck('word_id')
            ->toArray();

        $toggleFavoriteUrl = route('english.vocabulary.favorite');

        return view('english.vocabulary.favorites', compact('favorites', 'learnedIds', 'toggleFavoriteUrl'));
    }

    /**
     * フラッシュカード (S13)
     * GET /english/vocabulary/{level}/flashcard
     */
    public function flashcard(string $level)
    {
        $meta       = $this->parseLevelParam($level);
        $levelLabel = $meta['exam_type'] . ' ' . $meta['level'];
        $user       = Auth::user();

        $words = VocabularyWord::byLevel($meta['exam_type'], $meta['level'])
            ->inRandomOrder()
            ->take(10)
            ->get();

        $favoriteIds = $user->wordFavorites()
            ->whereIn('word_id', $words->pluck('id'))
            ->pluck('word_id')
            ->toArray();

        $learnedIds = $user->wordProgress()
            ->where('status', UserWordProgress::STATUS_LEARNED)
            ->whereIn('word_id', $words->pluck('id'))
            ->pluck('word_id')
            ->toArray();

        return view('english.vocabulary.flashcard', compact('level', 'levelLabel', 'words', 'favoriteIds', 'learnedIds'));
    }

    /**
     * スペル練習 (S14)
     * GET /english/vocabulary/{level}/spelling
     */
    public function spelling(string $level)
    {
        $meta       = $this->parseLevelParam($level);
        $levelLabel = $meta['exam_type'] . ' ' . $meta['level'];

        $words = VocabularyWord::byLevel($meta['exam_type'], $meta['level'])
            ->inRandomOrder()
            ->take(10)
            ->get(['id', 'word', 'meaning_ja', 'example_sentence']);

        return view('english.vocabulary.spelling', compact('level', 'levelLabel', 'words'));
    }

    /**
     * スペル回答チェック（JSON）
     * POST /english/vocabulary/{level}/spelling/check
     * ⚠️ XP はここでは加算しない（10問完了後に completeSpelling() で付与）
     */
    public function checkSpelling(Request $request, string $level)
    {
        $request->validate([
            'word_id' => ['required', 'integer', 'exists:vocabulary_words,id'],
            'answer'  => ['required', 'string', 'max:100'],
        ]);

        $word         = VocabularyWord::findOrFail($request->integer('word_id'));
        $userAnswer   = trim(strtolower($request->string('answer')));
        $correctWord  = strtolower($word->word);
        $isCorrect    = ($userAnswer === $correctWord);

        // 個別問題の学習進捗を更新
        $progress = Auth::user()->wordProgress()->firstOrCreate(
            ['word_id' => $word->id],
            ['correct_count' => 0, 'incorrect_count' => 0, 'status' => UserWordProgress::STATUS_NOT_LEARNED]
        );
        $isCorrect ? $progress->increment('correct_count') : $progress->increment('incorrect_count');

        return response()->json([
            'is_correct'   => $isCorrect,
            'correct_word' => $word->word,
        ]);
    }

    /**
     * スペル練習10問完了・XP付与（JSON）
     * POST /english/vocabulary/{level}/spelling/complete
     */
    public function completeSpelling(Request $request, string $level)
    {
        $request->validate([
            'correct_count'    => ['required', 'integer', 'min:0'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
        ]);

        $this->parseLevelParam($level); // 存在チェック
        $user    = Auth::user();
        $xp      = config('english.xp.spelling_10_complete', 30);
        $timeSec = $request->integer('duration_seconds', 0);

        $this->xpService->addXp($user, $xp);
        $this->studyLogService->log($user, 'vocabulary', null, $xp, $timeSec);

        $levelInfo = $this->xpService->getLevelInfo($user->fresh());

        return response()->json([
            'gained_xp'     => $xp,
            'total_xp'      => $levelInfo['current_xp'],
            'level'         => $levelInfo['level'],
            'next_level_xp' => $levelInfo['next_level_xp'],
            'xp_in_level'   => $levelInfo['xp_in_level'],
            'bar_percent'   => $levelInfo['bar_percent'],
        ]);
    }

    /**
     * 単語クイズ (S15)
     * GET /english/vocabulary/{level}/quiz
     */
    public function quiz(string $level)
    {
        $meta       = $this->parseLevelParam($level);
        $levelLabel = $meta['exam_type'] . ' ' . $meta['level'];

        // 正解単語 10件
        $correctWords = VocabularyWord::byLevel($meta['exam_type'], $meta['level'])
            ->inRandomOrder()
            ->take(10)
            ->get(['id', 'word', 'meaning_ja']);

        // 各問に対してダミー選択肢（他の3件）を付与
        $allMeanings = VocabularyWord::byLevel($meta['exam_type'], $meta['level'])
            ->whereNotIn('id', $correctWords->pluck('id'))
            ->inRandomOrder()
            ->take(30)
            ->pluck('meaning_ja')
            ->all();

        $questions = $correctWords->map(function ($word, $i) use ($allMeanings) {
            $distractors = array_slice($allMeanings, $i * 3, 3);
            $options     = array_merge([$word->meaning_ja], $distractors);
            shuffle($options);

            return [
                'id'      => $word->id,
                'word'    => $word->word,
                'correct' => $word->meaning_ja,
                'options' => $options,
            ];
        })->values()->all();

        return view('english.vocabulary.quiz', compact('level', 'levelLabel', 'questions'));
    }

    /**
     * 単語クイズ提出（JSON）
     * POST /english/vocabulary/{level}/quiz/submit
     */
    public function submitQuiz(Request $request, string $level)
    {
        $request->validate([
            'answers'            => ['required', 'array'],
            'answers.*.word_id'  => ['required', 'integer', 'exists:vocabulary_words,id'],
            'answers.*.answer'   => ['required', 'string'],
            'duration_seconds'   => ['nullable', 'integer', 'min:0'],
        ]);

        $meta    = $this->parseLevelParam($level);
        $user    = Auth::user();
        $timeSec = $request->integer('duration_seconds', 0);

        $answers  = $request->input('answers', []);
        $wordIds  = collect($answers)->pluck('word_id')->all();
        $words    = VocabularyWord::whereIn('id', $wordIds)->get()->keyBy('id');

        $results      = [];
        $correctCount = 0;

        foreach ($answers as $ans) {
            $word      = $words->get($ans['word_id']);
            $isCorrect = $word && strtolower(trim($ans['answer'])) === strtolower(trim($word->meaning_ja));
            if ($isCorrect) {
                $correctCount++;
            }
            $results[] = [
                'word_id'        => $ans['word_id'],
                'is_correct'     => $isCorrect,
                'correct_answer' => $word?->meaning_ja ?? '',
            ];
        }

        $xp = $this->xpService->calcQuizXp($correctCount);

        $user->quizResults()->create([
            'quiz_type'       => 'vocabulary',
            'exam_type'       => $meta['exam_type'],
            'level'           => $meta['level'],
            'total_questions' => count($answers),
            'correct_count'   => $correctCount,
            'xp_gained'       => $xp,
        ]);

        $this->xpService->addXp($user, $xp);
        $this->studyLogService->log($user, 'vocabulary', null, $xp, $timeSec);

        $levelInfo = $this->xpService->getLevelInfo($user->fresh());

        return response()->json([
            'score'         => $correctCount,
            'total'         => count($answers),
            'xp_gained'     => $xp,
            'total_xp'      => $levelInfo['current_xp'],
            'level'         => $levelInfo['level'],
            'next_level_xp' => $levelInfo['next_level_xp'],
            'xp_in_level'   => $levelInfo['xp_in_level'],
            'bar_percent'   => $levelInfo['bar_percent'],
            'results'       => $results,
        ]);
    }

    /**
     * お気に入りトグル（JSON）
     * POST /english/vocabulary/favorite
     */
    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'word_id' => ['required', 'integer', 'exists:vocabulary_words,id'],
        ]);

        $user   = Auth::user();
        $wordId = $request->integer('word_id');

        $existing = $user->wordFavorites()->where('word_id', $wordId)->first();

        if ($existing) {
            $existing->delete();
            $isFavorite = false;
        } else {
            $user->wordFavorites()->create(['word_id' => $wordId]);
            $isFavorite = true;
        }

        return response()->json(['is_favorite' => $isFavorite, 'word_id' => $wordId]);
    }

    /**
     * フラッシュカード進捗更新（JSON）
     * POST /english/vocabulary/progress
     */
    public function updateProgress(Request $request)
    {
        $request->validate([
            'level'              => ['required', 'string', 'in:' . implode(',', config('english.vocabulary_level_slugs'))],
            'is_completed'       => ['required', 'boolean'],
            'learned_word_ids'   => ['nullable', 'array'],
            'learned_word_ids.*' => ['integer', 'exists:vocabulary_words,id'],
        ]);

        $level       = $request->string('level');
        $isCompleted = $request->boolean('is_completed');
        $user        = Auth::user();

        $user->sectionProgress()->updateOrCreate(
            ['section_type' => UserSectionProgress::TYPE_VOCABULARY, 'section_key' => $level],
            ['is_completed' => $isCompleted, 'completed_at' => $isCompleted ? now() : null]
        );

        // 「覚えた」マークをつけた単語を学習済みとして記録
        $learnedIds = $request->input('learned_word_ids', []);
        foreach (array_unique($learnedIds) as $wordId) {
            $user->wordProgress()->updateOrCreate(
                ['word_id' => (int) $wordId],
                ['status' => UserWordProgress::STATUS_LEARNED, 'last_reviewed_at' => now()]
            );
        }

        if ($isCompleted) {
            $xp = config('english.xp.flashcard_set_complete', 30);
            $this->xpService->addXp($user, $xp);
            $this->studyLogService->log($user, 'vocabulary', null, $xp, 0);
        }

        return response()->json(['success' => true]);
    }

    // ──────────────────────────────────────────────────────────────
    //  内部ヘルパー
    // ──────────────────────────────────────────────────────────────

    /**
     * URL パラメータ {level} を DB クエリ用の配列に変換する。
     * 不正な値の場合は 404 を返す。
     *
     * @return array{exam_type: string, level: string}
     */
    private function parseLevelParam(string $level): array
    {
        $map = config('english.vocabulary_levels');
        abort_if(!isset($map[$level]), 404);
        return $map[$level];
    }
}
