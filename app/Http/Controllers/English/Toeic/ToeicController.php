<?php

namespace App\Http\Controllers\English\Toeic;

use App\Http\Controllers\Controller;
use App\Http\Requests\English\StoreToeicAnswerRequest;
use App\Models\English\ToeicAnswerLog;
use App\Models\English\ToeicQuestion;
use App\Models\English\ToeicQuestionOption;
use App\Models\English\ToeicSlide;
use App\Models\English\UserSectionProgress;
use App\Models\User;
use App\Services\English\StudyLogService;
use App\Services\English\XpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ToeicController extends Controller
{
    public function __construct(
        private readonly XpService       $xpService,
        private readonly StudyLogService $studyLogService,
    ) {}

    /**
     * TOEIC メニュー (S02)
     * GET /english/toeic
     */
    public function index()
    {
        $user = Auth::user();

        $partMeta = config('english.toeic_part_meta');

        // 進捗ゲージ = DBに登録された全問題のうち回答済みユニーク問題数の割合（スライド閲覧状況は含めない）
        $parts = collect($partMeta)->map(function ($meta, $partNum) use ($user) {
            $progress = $meta['available']
                ? $this->toeicQuestionsCoveragePercent($user, $partNum)
                : 0;

            return array_merge($meta, [
                'part'     => $partNum,
                'progress' => $progress,
            ]);
        })->values()->all();

        return view('english.toeic.index', compact('parts'));
    }

    /**
     * DBに登録された当該Partの全問題のうち、ユーザーが一度でも回答したユニーク問題数の割合（%）。
     * 全問に一度でも解答すると100%になる。
     */
    private function toeicQuestionsCoveragePercent(User $user, int $part): int
    {
        $total = ToeicQuestion::forPart($part)->count();

        if ($total === 0) {
            return 0;
        }

        $answered = count($this->toeicAnsweredQuestionIds($user, $part));

        return (int) round(min($answered, $total) / $total * 100);
    }

    /**
     * ユーザーが過去のセッションを通じて一度でも回答したことのある問題IDの一覧。
     */
    private function toeicAnsweredQuestionIds(User $user, int $part): array
    {
        return $user->toeicAnswerLogs()
            ->where('toeic_results.part', $part)
            ->distinct()
            ->pluck('toeic_answer_logs.question_id')
            ->all();
    }

    /**
     * TOEIC スライド (S03)
     * GET /english/toeic/{part}/slides/{step?}
     */
    public function slides(int $part, int $step = 1)
    {
        $slides = ToeicSlide::where('part', $part)
            ->orderBy('step_number')
            ->get();

        abort_if($slides->isEmpty(), 404);

        $totalSteps  = $slides->count();
        $step        = max(1, min($step, $totalSteps));
        $slide       = $slides->firstWhere('step_number', $step) ?? $slides->first();

        $user        = Auth::user();
        $sectionKey  = "part_{$part}";

        // 初回閲覧時でも問題へスキップ可能
        $canSkip = true;

        // 最終閲覧ステップを更新
        $user->sectionProgress()->updateOrCreate(
            ['section_type' => UserSectionProgress::TYPE_TOEIC_SLIDES, 'section_key' => $sectionKey],
            ['last_step' => $step]
        );

        return view('english.toeic.slides', compact('part', 'step', 'totalSteps', 'canSkip', 'slide'));
    }

    /**
     * スライド全閲覧完了を記録
     * POST /english/toeic/{part}/slides/complete
     */
    public function completeSlides(Request $request, int $part)
    {
        $sectionKey = "part_{$part}";

        Auth::user()->sectionProgress()->updateOrCreate(
            ['section_type' => UserSectionProgress::TYPE_TOEIC_SLIDES, 'section_key' => $sectionKey],
            ['is_completed' => true, 'completed_at' => now()]
        );

        return redirect()->route('english.toeic.practice', ['part' => $part]);
    }

    /**
     * TOEIC 問題（全問ロード方式）(S04)
     * GET /english/toeic/{part}/practice
     *
     * Part 5 は問題プールから10問を抽出して出題する（セッション中は順序固定）。
     * 未回答の問題があればそれを優先的に抽出し、全問回答済みの場合は完全ランダムに抽出する。
     * Part 6 / 7 は長文（passage）2つ分を1セッションで出題する。
     * 未回答の問題を含むパッセージを優先し、全問回答済みの場合は完全ランダムに2パッセージ選ぶ。
     */
    public function practice(int $part)
    {
        $sessionKey = "toeic_practice_{$part}";
        $questions  = collect();

        if (session()->has($sessionKey)) {
            $ids = session($sessionKey);
            $found = ToeicQuestion::whereIn('id', $ids)->with(['options', 'passage'])->get();

            if ($found->count() === count($ids)) {
                $questions = $found->sortBy(fn($q) => array_search($q->id, $ids))->values();
            } else {
                // 問題データ更新等でセッション内のIDが古くなっている場合は再抽選する
                session()->forget([$sessionKey, "toeic_answers_{$part}"]);
            }
        }

        if ($questions->isEmpty()) {
            $pool = ToeicQuestion::forPart($part)->with(['options', 'passage'])->get();

            abort_if($pool->isEmpty(), 404);

            $user = Auth::user();

            if ($part === 5) {
                // 未回答の問題を優先して出題し、全問回答済みなら完全ランダムに戻す
                $answeredIds = $this->toeicAnsweredQuestionIds($user, $part);
                $unanswered  = $pool->whereNotIn('id', $answeredIds)->values();

                $questions = $unanswered->isNotEmpty()
                    ? $unanswered->shuffle()->take(10)->values()
                    : $pool->shuffle()->take(10)->values();

                if ($questions->count() < 10) {
                    $needed  = 10 - $questions->count();
                    $fillers = $pool->whereNotIn('id', $questions->pluck('id'))->shuffle()->take($needed);
                    $questions = $questions->concat($fillers)->values();
                }
            } elseif (in_array($part, [6, 7], true)) {
                // 未回答の問題を含むパッセージを優先し、パッセージ2つ分を出題する
                $answeredIds          = $this->toeicAnsweredQuestionIds($user, $part);
                $allPassageIds        = $pool->pluck('passage_id')->unique()->values();
                $incompletePassageIds = $pool->whereNotIn('id', $answeredIds)->pluck('passage_id')->unique()->values();

                if ($incompletePassageIds->count() >= 2) {
                    $selectedPassageIds = $incompletePassageIds->shuffle()->take(2);
                } elseif ($incompletePassageIds->count() === 1) {
                    $completePassageIds = $allPassageIds->diff($incompletePassageIds)->values();
                    $selectedPassageIds = $incompletePassageIds->merge($completePassageIds->shuffle()->take(1));
                } else {
                    $selectedPassageIds = $allPassageIds->shuffle()->take(2);
                }

                $questions = $pool->whereIn('passage_id', $selectedPassageIds->all())
                    ->sortBy('sort_order')
                    ->values();
            }

            session([$sessionKey => $questions->pluck('id')->all()]);
        }

        // フロントに正解フラグを露出しないようにオプションから is_correct を除外
        $questionsJson = $questions->map(function ($q) {
            return [
                'id'            => $q->id,
                'question_text' => $q->question_text,
                'explanation'   => $q->explanation ?? '',
                'passage'       => $q->passage ? [
                    'id'        => $q->passage->id,
                    'title'     => $q->passage->title,
                    'documents' => $q->passage->documents,
                ] : null,
                'options'       => $q->options->map(fn($o) => [
                    'id'          => $o->id,
                    'label'       => $o->label,
                    'option_text' => $o->option_text,
                ])->values()->all(),
            ];
        })->values()->all();

        return view('english.toeic.practice', compact('part', 'questionsJson'));
    }

    /**
     * 1問回答（JSON）
     * POST /english/toeic/{part}/answer
     * ⚠️ XP はここでは加算しない（二重加算防止）
     */
    public function submitAnswer(StoreToeicAnswerRequest $request, int $part)
    {
        $questionId       = $request->integer('question_id');
        $selectedOptionId = $request->integer('selected_option_id');

        $correctOption = ToeicQuestionOption::where('question_id', $questionId)
            ->where('is_correct', true)
            ->first();

        abort_if(!$correctOption, 404);

        $isCorrect = ($selectedOptionId === $correctOption->id);

        // セッションに回答を積算
        $answerKey = "toeic_answers_{$part}";
        $answers   = session($answerKey, []);
        $answers[$questionId] = [
            'selected_option_id' => $selectedOptionId,
            'is_correct'         => $isCorrect,
        ];
        session([$answerKey => $answers]);

        return response()->json([
            'is_correct'       => $isCorrect,
            'correct_option_id' => $correctOption->id,
            'explanation'      => ToeicQuestion::find($questionId)?->explanation ?? '',
        ]);
    }

    /**
     * 全問完了 → 保存 + XP付与
     * POST /english/toeic/{part}/complete
     */
    public function complete(Request $request, int $part)
    {
        $user       = Auth::user();
        $answerKey  = "toeic_answers_{$part}";
        $answers    = session($answerKey, []);

        if (empty($answers)) {
            return redirect()->route('english.toeic.practice', ['part' => $part]);
        }

        $correctCount    = collect($answers)->where('is_correct', true)->count();
        $totalQuestions  = count($answers);
        $xp              = $this->xpService->calcToeicXp($correctCount, $totalQuestions);

        DB::transaction(function () use ($user, $part, $answers, $correctCount, $totalQuestions, $xp) {
            // ToeicResult 保存
            $result = $user->toeicResults()->create([
                'part'            => $part,
                'total_questions' => $totalQuestions,
                'correct_count'   => $correctCount,
                'xp_gained'       => $xp,
                'completed_at'    => now(),
            ]);

            // ToeicAnswerLog を一括 INSERT
            $logs = [];
            foreach ($answers as $questionId => $answer) {
                $logs[] = [
                    'result_id'          => $result->id,
                    'question_id'        => $questionId,
                    'selected_option_id' => $answer['selected_option_id'],
                    'is_correct'         => $answer['is_correct'],
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            }
            ToeicAnswerLog::insert($logs);

            // XP 加算（complete() でのみ呼ぶ）
            $this->xpService->addXp($user, $xp);

            // StudyLog 記録（total_study_time + streak も内部で更新）
            $this->studyLogService->log($user, 'toeic', $result->id, $xp, 0);

            // セクション進捗を更新（DBに登録された全問題に一度でも解答したら完了とする）
            $isFullyCovered = $this->toeicQuestionsCoveragePercent($user, $part) >= 100;

            $user->sectionProgress()->updateOrCreate(
                ['section_type' => UserSectionProgress::TYPE_TOEIC_QUESTIONS, 'section_key' => "part_{$part}"],
                ['is_completed' => $isFullyCovered, 'completed_at' => $isFullyCovered ? now() : null]
            );

            // セッションに結果IDを保存してからクリア
            session([
                'toeic_result_id'           => $result->id,
                "toeic_answers_{$part}"     => null,
                "toeic_practice_{$part}"    => null,
            ]);
        });

        return redirect()->route('english.toeic.result', ['part' => $part]);
    }

    /**
     * TOEIC 結果 (S05)
     * GET /english/toeic/{part}/result
     */
    public function result(int $part)
    {
        $resultId = session('toeic_result_id');

        if (!$resultId) {
            return redirect()->route('english.toeic.practice', ['part' => $part]);
        }

        $result = Auth::user()->toeicResults()->with([
            'answerLogs.question.options',
            'answerLogs.selectedOption',
        ])->findOrFail($resultId);

        session()->forget('toeic_result_id');

        $wrongAnswers = $result->answerLogs
            ->where('is_correct', false)
            ->map(fn($log) => [
                'question'        => $log->question?->question_text ?? '',
                'your_answer'     => $log->selectedOption?->option_text ?? '',
                'correct_answer'  => $log->question?->options->firstWhere('is_correct', true)?->option_text ?? '',
                'explanation'     => $log->question?->explanation ?? '',
            ])->values()->all();

        $levelInfo = $this->xpService->getLevelInfo(Auth::user());

        return view('english.toeic.result', compact('part', 'result', 'wrongAnswers', 'levelInfo'));
    }
}
