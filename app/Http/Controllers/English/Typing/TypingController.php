<?php

namespace App\Http\Controllers\English\Typing;

use App\Http\Controllers\Controller;
use App\Http\Requests\English\StoreTypingResultRequest;
use App\Models\English\TypingCategory;
use App\Models\English\TypingMaterial;
use App\Models\English\UserSectionProgress;
use App\Services\English\StudyLogService;
use App\Services\English\XpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypingController extends Controller
{
    public function __construct(
        private readonly XpService       $xpService,
        private readonly StudyLogService $studyLogService,
    ) {}

    /**
     * タイピング 教材選択 (S16)
     * GET /english/typing
     */
    public function index()
    {
        $categories = TypingCategory::withCount('materials')
            ->orderBy('sort_order')
            ->get();

        return view('english.typing.index', compact('categories'));
    }

    /**
     * 1セッションあたりの出題フレーズ数
     */
    private const SESSION_PHRASE_COUNT = 10;

    /**
     * カテゴリー内のフレーズからランダムに10個選んでタイピング練習へ
     * GET /english/typing/category/{category}/practice
     */
    public function randomPractice(string $category)
    {
        $materials = TypingMaterial::forSession($category)
            ->inRandomOrder()
            ->limit(self::SESSION_PHRASE_COUNT)
            ->get();

        abort_if($materials->isEmpty(), 404);

        session(['typing_session_ids' => $materials->pluck('id')->all()]);

        return redirect()->route('english.typing.practice', ['id' => $materials->first()->id]);
    }

    /**
     * セッション中のフレーズ一覧（{$id} を含むランダム抽出結果）を出題順で取得する
     */
    private function sessionMaterials(int $id)
    {
        $ids = session('typing_session_ids', []);

        if (!in_array($id, $ids, true)) {
            $ids = [$id];
        }

        return TypingMaterial::whereIn('id', $ids)->get()
            ->sortBy(fn($m) => array_search($m->id, $ids))
            ->values();
    }

    /**
     * タイピング スライド (S17)
     * GET /english/typing/{id}/slides/{step?}
     */
    public function slides(int $id, int $step = 1)
    {
        $material = TypingMaterial::with('slides')->findOrFail($id);
        $slides   = $material->slides;

        $totalSteps = max(1, $slides->count());
        $step       = max(1, min($step, $totalSteps));
        $slide      = $slides->firstWhere('step_number', $step) ?? $slides->first();

        $user        = Auth::user();
        $sectionKey  = (string) $id;

        $sectionProgress = $user->sectionProgress()
            ->where('section_type', UserSectionProgress::TYPE_TYPING_SLIDES)
            ->where('section_key', $sectionKey)
            ->first();

        $canSkip = $sectionProgress && $sectionProgress->is_completed;

        $user->sectionProgress()->updateOrCreate(
            ['section_type' => UserSectionProgress::TYPE_TYPING_SLIDES, 'section_key' => $sectionKey],
            ['last_step' => $step]
        );

        return view('english.typing.slides', compact('id', 'step', 'totalSteps', 'canSkip', 'material', 'slide'));
    }

    /**
     * スライド全閲覧完了 → XP付与
     * POST /english/typing/{id}/slides/complete
     */
    public function completeSlides(Request $request, int $id)
    {
        $user       = Auth::user();
        $sectionKey = (string) $id;
        $xp         = config('english.xp.typing_slides_complete', 20);

        $user->sectionProgress()->updateOrCreate(
            ['section_type' => UserSectionProgress::TYPE_TYPING_SLIDES, 'section_key' => $sectionKey],
            ['is_completed' => true, 'completed_at' => now()]
        );

        $this->xpService->addXp($user, $xp);
        $this->studyLogService->log($user, 'typing', null, $xp, 0);

        return redirect()->route('english.typing.practice', ['id' => $id]);
    }

    /**
     * タイピング練習 (S18)
     * GET /english/typing/{id}/practice
     */
    public function practice(int $id)
    {
        $materials = $this->sessionMaterials($id);
        $material  = $materials->first();
        $rawText   = $materials->pluck('text')->implode("\n\n");

        return view('english.typing.practice', compact('id', 'material', 'rawText'));
    }

    /**
     * タイピング結果保存・XP付与（Fetch API）
     * POST /english/typing/{id}/result
     */
    public function storeResult(StoreTypingResultRequest $request, int $id)
    {
        $user      = Auth::user();
        $materials = $this->sessionMaterials($id);
        $wpm       = $request->integer('wpm');
        $accuracy  = $request->float('accuracy');
        $timeSec   = $request->integer('time_seconds');
        $xp        = (int) $materials->sum('xp');

        $record = $user->typingRecords()->create([
            'material_id' => $id,
            'wpm'         => $wpm,
            'accuracy'    => $accuracy,
            'clear_time'  => $timeSec,
            'xp_gained'   => $xp,
        ]);

        $this->xpService->addXp($user, $xp);
        $this->studyLogService->log($user, 'typing', $record->id, $xp, $timeSec);

        $user->sectionProgress()->updateOrCreate(
            ['section_type' => UserSectionProgress::TYPE_TYPING_MATERIAL, 'section_key' => (string) $id],
            ['is_completed' => true, 'completed_at' => now()]
        );

        session(['typing_record_id' => $record->id]);

        $levelInfo = $this->xpService->getLevelInfo($user->fresh());

        return response()->json([
            'success'       => true,
            'gained_xp'     => $xp,
            'total_xp'      => $levelInfo['current_xp'],
            'level'         => $levelInfo['level'],
            'next_level_xp' => $levelInfo['next_level_xp'],
            'xp_in_level'   => $levelInfo['xp_in_level'],
            'bar_percent'   => $levelInfo['bar_percent'],
            'redirect_url'  => route('english.typing.result', ['id' => $id]),
        ]);
    }

    /**
     * タイピング結果 (S19)
     * GET /english/typing/{id}/result
     */
    public function result(int $id)
    {
        $recordId = session('typing_record_id');

        if (!$recordId) {
            return redirect()->route('english.typing.practice', ['id' => $id]);
        }

        $user = Auth::user();

        $record   = $user->typingRecords()->with('material')->findOrFail($recordId);
        $material = TypingMaterial::findOrFail($id);

        session()->forget('typing_record_id');

        $bestWpm = $user->typingRecords()
            ->where('material_id', $id)
            ->max('wpm') ?? 0;

        $levelInfo = $this->xpService->getLevelInfo($user);

        return view('english.typing.result', compact('id', 'record', 'material', 'bestWpm', 'levelInfo'));
    }
}
