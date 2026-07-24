<?php

namespace App\Http\Controllers\English\Ielts;

use App\Http\Controllers\Controller;
use App\Http\Requests\English\StoreIeltsResultRequest;
use App\Models\English\IeltsMaterial;
use App\Models\English\IeltsSlide;
use App\Models\English\IeltsTopic;
use App\Models\English\UserSectionProgress;
use App\Models\User;
use App\Services\English\StudyLogService;
use App\Services\English\XpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IeltsController extends Controller
{
    public function __construct(
        private readonly XpService       $xpService,
        private readonly StudyLogService $studyLogService,
    ) {}

    /**
     * IELTS メニュー (S06)
     * GET /english/ielts
     */
    public function index()
    {
        $parts = config('english.ielts_part_meta');
        return view('english.ielts.index', compact('parts'));
    }

    /**
     * IELTS トピック選択 (S07)
     * GET /english/ielts/speaking/{part}/topic
     */
    public function topic(int $part)
    {
        $topics   = IeltsTopic::ordered()->get();
        $partMeta = config('english.ielts_part_meta')[$part] ?? [];

        return view('english.ielts.topic', compact('part', 'topics', 'partMeta'));
    }

    /**
     * IELTS スコア選択 (S08)
     * GET /english/ielts/speaking/{part}/{topic}/score
     */
    public function score(int $part, string $topic)
    {
        $topicModel = IeltsTopic::where('slug', $topic)->firstOrFail();
        $scores     = config('english.ielts_score_meta');
        $partMeta   = config('english.ielts_part_meta')[$part] ?? [];

        return view('english.ielts.score', compact('part', 'topic', 'topicModel', 'scores', 'partMeta'));
    }

    /**
     * IELTS スライド (S09)
     * GET /english/ielts/speaking/{part}/{topic}/{score}/slides/{step?}
     */
    public function slides(int $part, string $topic, string $score, int $step = 1)
    {
        $topicModel = IeltsTopic::where('slug', $topic)->firstOrFail();

        $slides = IeltsSlide::forSession($part, $topicModel->id, $score)->get();

        // スライドが DB にない場合は config ベースのダミーを使用
        if ($slides->isEmpty()) {
            $totalSteps = 1;
            $step       = 1;
            $slide      = null;
        } else {
            $totalSteps = $slides->count();
            $step       = max(1, min($step, $totalSteps));
            $slide      = $slides->firstWhere('step_number', $step) ?? $slides->first();
        }

        $user       = Auth::user();
        $sectionKey = "{$part}_{$topic}_{$score}";

        // 初回閲覧時でもタイピングへスキップ可能
        $canSkip = true;

        $user->sectionProgress()->updateOrCreate(
            ['section_type' => UserSectionProgress::TYPE_IELTS_SLIDES, 'section_key' => $sectionKey],
            ['last_step' => $step]
        );

        $topicMeta = config('english.ielts_topic_meta')[$topic] ?? [];
        $scoreMeta = config('english.ielts_score_meta')[$score] ?? [];
        $partMeta  = config('english.ielts_part_meta')[$part] ?? [];

        return view('english.ielts.slides', compact(
            'part', 'topic', 'score', 'step', 'totalSteps', 'canSkip',
            'slide', 'topicMeta', 'scoreMeta', 'partMeta', 'topicModel'
        ));
    }

    /**
     * スライド全閲覧完了 → XP付与
     * POST /english/ielts/speaking/{part}/{topic}/{score}/slides/complete
     */
    public function completeSlides(Request $request, int $part, string $topic, string $score)
    {
        $user       = Auth::user();
        $sectionKey = "{$part}_{$topic}_{$score}";
        $xp         = config('english.xp.ielts_slides_complete', 20);

        $user->sectionProgress()->updateOrCreate(
            ['section_type' => UserSectionProgress::TYPE_IELTS_SLIDES, 'section_key' => $sectionKey],
            ['is_completed' => true, 'completed_at' => now()]
        );

        $this->xpService->addXp($user, $xp);
        $this->studyLogService->log($user, 'ielts', null, $xp, 0);

        return redirect()->route('english.ielts.typing', compact('part', 'topic', 'score'));
    }

    /**
     * IELTS タイピング (S10)
     * GET /english/ielts/speaking/{part}/{topic}/{score}/typing
     */
    public function typing(int $part, string $topic, string $score)
    {
        $topicModel = IeltsTopic::where('slug', $topic)->firstOrFail();
        // 同じ part/topic/score に複数バリエーションがある場合（Part2など）はランダムに1つ選ぶ
        $material   = IeltsMaterial::forSession($part, $topic, $score)->inRandomOrder()->first();

        if (!$material) {
            // DB 未投入の場合はフォールバック
            $rawText = "[Question 1]\nIELTSスピーキングの質問がここに入ります。\n[Answer]\n回答文がここに入ります。";
        } else {
            $rawText = $material->text;
        }

        session(['ielts_material_id' => $material?->id]);

        $topicMeta = config('english.ielts_topic_meta')[$topic] ?? [];
        $scoreMeta = config('english.ielts_score_meta')[$score] ?? [];

        return view('english.ielts.typing', compact(
            'part', 'topic', 'score', 'rawText', 'material', 'topicMeta', 'scoreMeta', 'topicModel'
        ));
    }

    /**
     * タイピング結果保存・XP付与（Fetch API）
     * POST /english/ielts/speaking/{part}/{topic}/{score}/result
     */
    public function storeResult(StoreIeltsResultRequest $request, int $part, string $topic, string $score)
    {
        $user     = Auth::user();
        $wpm      = $request->integer('wpm');
        $accuracy = $request->float('accuracy');
        $timeSec  = $request->integer('time_seconds');

        // typing() でランダムに選ばれた教材と同じものを結果に紐付ける
        $material = IeltsMaterial::find(session('ielts_material_id'))
            ?? IeltsMaterial::forSession($part, $topic, $score)->first();
        $xp       = $this->xpService->calcIeltsTypingXp($accuracy);

        $record = $user->ieltsRecords()->create([
            'material_id' => $material?->id,
            'wpm'         => $wpm,
            'accuracy'    => $accuracy,
            'clear_time'  => $timeSec,
            'xp_gained'   => $xp,
        ]);

        $this->xpService->addXp($user, $xp);
        $this->studyLogService->log($user, 'ielts', $record->id, $xp, $timeSec);

        // タイピング進捗を完了に更新
        $sectionKey = "{$part}_{$topic}_{$score}";
        $user->sectionProgress()->updateOrCreate(
            ['section_type' => UserSectionProgress::TYPE_IELTS_TYPING, 'section_key' => $sectionKey],
            ['is_completed' => true, 'completed_at' => now()]
        );

        // IELTS Part 全トピック×スコア完了ボーナス判定
        $this->checkIeltsPartBonus($user, $part);

        session(['ielts_record_id' => $record->id]);

        $levelInfo = $this->xpService->getLevelInfo($user->fresh());

        return response()->json([
            'success'       => true,
            'gained_xp'     => $xp,
            'total_xp'      => $levelInfo['current_xp'],
            'level'         => $levelInfo['level'],
            'next_level_xp' => $levelInfo['next_level_xp'],
            'xp_in_level'   => $levelInfo['xp_in_level'],
            'bar_percent'   => $levelInfo['bar_percent'],
            'redirect_url'  => route('english.ielts.result', compact('part', 'topic', 'score')),
        ]);
    }

    /**
     * IELTS 結果 (S11)
     * GET /english/ielts/speaking/{part}/{topic}/{score}/result
     */
    public function result(int $part, string $topic, string $score)
    {
        $recordId = session('ielts_record_id');

        if (!$recordId) {
            return redirect()->route('english.ielts.typing', compact('part', 'topic', 'score'));
        }

        $user   = Auth::user();
        $record = $user->ieltsRecords()->with('material')->findOrFail($recordId);
        session()->forget('ielts_record_id');

        $levelInfo = $this->xpService->getLevelInfo($user);
        $topicMeta = config('english.ielts_topic_meta')[$topic] ?? [];
        $scoreMeta = config('english.ielts_score_meta')[$score] ?? [];

        return view('english.ielts.result', compact(
            'part', 'topic', 'score', 'record', 'levelInfo', 'topicMeta', 'scoreMeta'
        ));
    }

    // ──────────────────────────────────────────────────────────────
    //  内部ヘルパー
    // ──────────────────────────────────────────────────────────────

    /**
     * 1つの Part で全12パターン（3topic × 4score）の typing を完了した場合に 500XP ボーナスを付与する。
     */
    private function checkIeltsPartBonus(User $user, int $part): void
    {
        $topics = config('english.ielts_topics');
        $scores = config('english.ielts_scores');
        $total  = count($topics) * count($scores);

        $completedCount = $user->sectionProgress()
            ->where('section_type', UserSectionProgress::TYPE_IELTS_TYPING)
            ->where('is_completed', true)
            ->where('section_key', 'like', "{$part}_%")
            ->count();

        if ($completedCount >= $total) {
            $this->xpService->addXp($user, 500);
        }
    }
}
