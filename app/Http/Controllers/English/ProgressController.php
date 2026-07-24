<?php

namespace App\Http\Controllers\English;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\English\ProgressService;
use App\Services\English\StreakService;
use App\Services\English\StudyLogService;
use App\Services\English\XpService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function __construct(
        private readonly ProgressService $progressService,
        private readonly StudyLogService $studyLogService,
        private readonly StreakService   $streakService,
        private readonly XpService       $xpService,
    ) {}

    /**
     * 学習管理ダッシュボード (S28)
     * GET /english/progress
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 機能別進捗
        $sectionProgress = $this->progressService->getSectionProgress($user);
        $overallProgress = $this->progressService->getOverallProgress($user);

        // 学習時間（users.total_study_time を直接参照）
        $totalStudyTime  = $this->progressService->getTotalStudyTime($user);
        $studyTimeFormatted = ProgressService::formatStudyTime($totalStudyTime);

        // 累計学習日数
        $totalStudyDays = $this->streakService->getTotalStudyDays($user);

        // 最近の学習履歴（10件）
        $recentLogs = $this->studyLogService->getRecentLogs($user, 10);

        // お気に入り単語数
        $favoritesCount = $user->wordFavorites()->count();

        // XP・Level 情報
        $levelInfo = $this->xpService->getLevelInfo($user);

        // 学習カレンダー（?month=YYYY-MM で月を指定、省略時は当月）
        $calendar = $this->buildCalendar($user, $request->query('month'));

        // TOEIC・IELTS試験日までの残り日数
        $examDaysLeft = $this->progressService->getExamDaysLeft($user);

        return view('english.progress.index', compact(
            'user',
            'levelInfo',
            'sectionProgress',
            'overallProgress',
            'totalStudyTime',
            'studyTimeFormatted',
            'totalStudyDays',
            'recentLogs',
            'favoritesCount',
            'calendar',
            'examDaysLeft',
        ));
    }

    /**
     * TOEIC / IELTS 試験日の登録・更新
     * POST /english/progress/exam-date
     */
    public function updateExamDate(Request $request)
    {
        $request->validate([
            'exam_type' => ['required', 'in:toeic,ielts'],
            'exam_date' => ['nullable', 'date'],
        ]);

        $column = $request->string('exam_type') . '_exam_date';

        Auth::user()->update([$column => $request->input('exam_date')]);

        return redirect()->route('english.progress');
    }

    /**
     * 指定月の学習カレンダーデータを組み立てる。
     * 学習日（study_logs.studied_date）と試験日（users.toeic_exam_date / ielts_exam_date）を
     * 各セルにマークする。
     *
     * @return array{month: Carbon, weeks: array, prevMonth: string, nextMonth: string}
     */
    private function buildCalendar(User $user, ?string $monthParam): array
    {
        $month = (is_string($monthParam) && preg_match('/^\d{4}-\d{2}$/', $monthParam))
            ? Carbon::createFromFormat('Y-m-d', $monthParam . '-01')
            : Carbon::now();
        $month->startOfMonth();

        $studiedDates = $user->studyLogs()
            ->whereYear('studied_date', $month->year)
            ->whereMonth('studied_date', $month->month)
            ->pluck('studied_date')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->unique()
            ->all();

        $examDates = array_filter([
            'toeic' => $user->toeic_exam_date?->format('Y-m-d'),
            'ielts' => $user->ielts_exam_date?->format('Y-m-d'),
        ]);

        $firstCell = $month->copy()->startOfWeek(Carbon::SUNDAY);
        $lastCell  = $month->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $weeks  = [];
        $week   = [];
        $cursor = $firstCell->copy();

        while ($cursor->lte($lastCell)) {
            $dateStr = $cursor->format('Y-m-d');

            $week[] = [
                'date'    => $cursor->copy(),
                'inMonth' => $cursor->month === $month->month,
                'isToday' => $cursor->isToday(),
                'studied' => in_array($dateStr, $studiedDates, true),
                'examType' => array_search($dateStr, $examDates, true) ?: null,
            ];

            if ($cursor->dayOfWeek === Carbon::SATURDAY) {
                $weeks[] = $week;
                $week = [];
            }

            $cursor->addDay();
        }

        return [
            'month'     => $month,
            'weeks'     => $weeks,
            'prevMonth' => $month->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $month->copy()->addMonth()->format('Y-m'),
        ];
    }
}
