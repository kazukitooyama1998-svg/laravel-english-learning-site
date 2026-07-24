<?php

namespace App\Http\Controllers\English;

use App\Http\Controllers\Controller;
use App\Services\English\RankingService;
use App\Services\English\XpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    public function __construct(
        private readonly RankingService $rankingService,
        private readonly XpService      $xpService,
    ) {}

    /**
     * ランキング (S29)
     * GET /english/ranking?period=weekly|monthly|total
     */
    public function index(Request $request)
    {
        $period   = $request->string('period', 'total')->toString();
        $allowed  = ['weekly', 'monthly', 'total'];

        if (!in_array($period, $allowed)) {
            $period = 'total';
        }

        $user      = Auth::user();
        $rankings  = $this->rankingService->getRanking($period, 10);
        $myRank    = $this->rankingService->getMyRank($user, $period);
        $levelInfo = $this->xpService->getLevelInfo($user);

        return view('english.ranking.index', compact('rankings', 'period', 'myRank', 'user', 'levelInfo'));
    }
}
