<?php

namespace App\Http\Controllers\English\Quiz;

use App\Http\Controllers\Controller;
use App\Models\English\VocabularyWord;
use App\Services\English\StudyLogService;
use App\Services\English\XpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function __construct(
        private readonly XpService       $xpService,
        private readonly StudyLogService $studyLogService,
    ) {}

    /**
     * クイズ メニュー (S20)
     * GET /english/quiz
     */
    public function index()
    {
        $user = Auth::user();

        $recentSpelling = $user->quizResults()
            ->where('quiz_type', 'spelling')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $recentVocabulary = $user->quizResults()
            ->where('quiz_type', 'vocabulary')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('english.quiz.index', compact('recentSpelling', 'recentVocabulary'));
    }

    /**
     * スペルクイズ (S21)
     * GET /english/quiz/spelling
     */
    public function spelling()
    {
        $words = VocabularyWord::inRandomOrder()
            ->take(10)
            ->get(['id', 'word', 'part_of_speech', 'meaning_ja', 'example_sentence', 'example_sentence_ja']);

        return view('english.quiz.spelling', compact('words'));
    }

    /**
     * スペルクイズ提出（JSON）
     * POST /english/quiz/spelling/submit
     */
    public function submitSpelling(Request $request)
    {
        $request->validate([
            'answers'            => ['required', 'array', 'min:1'],
            'answers.*.word_id'  => ['required', 'integer', 'exists:vocabulary_words,id'],
            'answers.*.answer'   => ['required', 'string', 'max:100'],
            'duration_seconds'   => ['nullable', 'integer', 'min:0'],
        ]);

        $user    = Auth::user();
        $timeSec = $request->integer('duration_seconds', 0);
        $answers = $request->input('answers', []);

        $wordIds  = collect($answers)->pluck('word_id')->all();
        $words    = VocabularyWord::whereIn('id', $wordIds)->get()->keyBy('id');

        $results      = [];
        $correctCount = 0;

        foreach ($answers as $ans) {
            $word      = $words->get($ans['word_id']);
            $isCorrect = $word && strtolower(trim($ans['answer'])) === strtolower(trim($word->word));
            if ($isCorrect) {
                $correctCount++;
            }
            $results[] = [
                'word_id'        => $ans['word_id'],
                'is_correct'     => $isCorrect,
                'correct_answer' => $word?->word ?? '',
            ];
        }

        $xp = $this->xpService->calcQuizXp($correctCount);

        $user->quizResults()->create([
            'quiz_type'       => 'spelling',
            'exam_type'       => null,
            'level'           => null,
            'total_questions' => count($answers),
            'correct_count'   => $correctCount,
            'xp_gained'       => $xp,
        ]);

        $this->xpService->addXp($user, $xp);
        $this->studyLogService->log($user, 'quiz', null, $xp, $timeSec);

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
     * 語彙クイズ (S22)
     * GET /english/quiz/vocabulary
     */
    public function vocabulary()
    {
        $correctWords = VocabularyWord::inRandomOrder()->take(10)->get(['id', 'word', 'meaning_ja']);

        $allMeanings = VocabularyWord::whereNotIn('id', $correctWords->pluck('id'))
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

        return view('english.quiz.vocabulary', compact('questions'));
    }

    /**
     * 語彙クイズ提出（JSON）
     * POST /english/quiz/vocabulary/submit
     */
    public function submitVocabulary(Request $request)
    {
        $request->validate([
            'answers'            => ['required', 'array', 'min:1'],
            'answers.*.word_id'  => ['required', 'integer', 'exists:vocabulary_words,id'],
            'answers.*.answer'   => ['required', 'string'],
            'duration_seconds'   => ['nullable', 'integer', 'min:0'],
        ]);

        $user    = Auth::user();
        $timeSec = $request->integer('duration_seconds', 0);
        $answers = $request->input('answers', []);

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
            'exam_type'       => null,
            'level'           => null,
            'total_questions' => count($answers),
            'correct_count'   => $correctCount,
            'xp_gained'       => $xp,
        ]);

        $this->xpService->addXp($user, $xp);
        $this->studyLogService->log($user, 'quiz', null, $xp, $timeSec);

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
}
