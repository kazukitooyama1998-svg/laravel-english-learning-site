<?php

namespace App\Http\Controllers\English;

use App\Http\Controllers\Controller;
use App\Services\English\ProgressService;
use App\Services\English\StreakService;
use App\Services\English\XpService;
use Illuminate\Support\Facades\Auth;

class HubController extends Controller
{
    public function __construct(
        private readonly ProgressService $progressService,
        private readonly StreakService   $streakService,
        private readonly XpService       $xpService,
    ) {}

    /**
     * 英語学習 Hub (S01)
     * GET /english
     */
    public function index()
    {
        $user      = Auth::user();
        $progress  = $this->progressService->getSectionProgress($user);
        $levelInfo = $this->xpService->getLevelInfo($user);

        $featureProgress = [
            'toeic'      => $progress['toeic']['percent'],
            'ielts'      => $progress['ielts']['percent'],
            'vocabulary' => $progress['vocabulary']['percent'],
            'typing'     => $progress['typing']['percent'],
        ];

        $overallProgress = $this->progressService->getOverallProgress($user);

        // 累計学習日数（XP獲得のあった日のみをカウント。閲覧のみでは加算されない）
        $totalStudyDays = $this->streakService->getTotalStudyDays($user);

        // TOEIC・IELTS試験日までの残り日数
        $examDaysLeft = $this->progressService->getExamDaysLeft($user);

        return view('english.hub', compact('user', 'levelInfo', 'featureProgress', 'overallProgress', 'totalStudyDays', 'examDaysLeft'));
    }
}
