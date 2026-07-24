<?php

namespace App\Services\English;

use App\Models\User;
use App\Models\English\StudyLog;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RankingService
{
    /**
     * 指定期間のランキングを取得する。
     *
     * @param string $period 'weekly' / 'monthly' / 'total'
     * @param int    $perPage 1ページあたりの件数（デフォルト: 10）
     */
    public function getRanking(string $period, int $perPage = 10): LengthAwarePaginator
    {
        return match ($period) {
            'weekly'  => $this->getWeeklyRanking($perPage),
            'monthly' => $this->getMonthlyRanking($perPage),
            default   => $this->getTotalRanking($perPage),
        };
    }

    /**
     * 自分の順位を返す。
     * 同率順位の扱い: 自分より上位の人数 + 1
     */
    public function getMyRank(User $user, string $period): int
    {
        return match ($period) {
            'weekly'  => $this->calcMyWeeklyRank($user),
            'monthly' => $this->calcMyMonthlyRank($user),
            default   => $this->calcMyTotalRank($user),
        };
    }

    // ──────────────────────────────────────────────────────────────
    //  Total ランキング
    // ──────────────────────────────────────────────────────────────

    private function getTotalRanking(int $perPage): LengthAwarePaginator
    {
        return User::select(['id', 'name', 'total_xp', 'created_at'])
            ->selectSub(
                StudyLog::selectRaw('COUNT(DISTINCT studied_date)')->whereColumn('user_id', 'users.id'),
                'total_study_days'
            )
            ->whereNull('deleted_at')
            ->orderByDesc('total_xp')
            ->orderBy('created_at')
            ->paginate($perPage);
    }

    /**
     * ランキングはTotal XPのみで決まる（同XPの場合は同順位）。
     */
    private function calcMyTotalRank(User $user): int
    {
        $higherCount = User::whereNull('deleted_at')
            ->where('total_xp', '>', $user->total_xp)
            ->count();

        return $higherCount + 1;
    }

    // ──────────────────────────────────────────────────────────────
    //  週間ランキング
    // ──────────────────────────────────────────────────────────────

    private function getWeeklyRanking(int $perPage): LengthAwarePaginator
    {
        [$start, $end] = $this->weekRange();

        return DB::table('study_logs')
            ->join('users', 'study_logs.user_id', '=', 'users.id')
            ->whereBetween('study_logs.studied_date', [$start, $end])
            ->whereNull('users.deleted_at')
            ->select([
                'users.id',
                'users.name',
                'users.total_xp',
                'users.created_at',
                DB::raw('SUM(study_logs.xp_gained) as period_xp'),
                DB::raw('(SELECT COUNT(DISTINCT sl2.studied_date) FROM study_logs sl2 WHERE sl2.user_id = users.id) as total_study_days'),
            ])
            ->groupBy('users.id', 'users.name', 'users.total_xp', 'users.created_at')
            ->having('period_xp', '>', 0)
            ->orderByDesc('period_xp')
            ->orderBy('users.created_at')
            ->paginate($perPage);
    }

    private function calcMyWeeklyRank(User $user): int
    {
        [$start, $end] = $this->weekRange();

        $myXp = StudyLog::where('user_id', $user->id)
            ->whereBetween('studied_date', [$start, $end])
            ->sum('xp_gained');

        $higherCount = DB::table('study_logs')
            ->join('users', 'study_logs.user_id', '=', 'users.id')
            ->whereBetween('study_logs.studied_date', [$start, $end])
            ->whereNull('users.deleted_at')
            ->select('study_logs.user_id')
            ->groupBy('study_logs.user_id')
            ->havingRaw('SUM(study_logs.xp_gained) > ?', [$myXp])
            ->count();

        return $higherCount + 1;
    }

    // ──────────────────────────────────────────────────────────────
    //  月間ランキング
    // ──────────────────────────────────────────────────────────────

    private function getMonthlyRanking(int $perPage): LengthAwarePaginator
    {
        $month = now()->month;
        $year  = now()->year;

        // クエリビルダを構築
        $query = DB::table('study_logs')
            ->join('users', 'study_logs.user_id', '=', 'users.id')
            ->whereMonth('study_logs.studied_date', $month)
            ->whereYear('study_logs.studied_date', $year)
            ->whereNull('users.deleted_at')
            ->select([
                'users.id',
                'users.name',
                'users.total_xp',
                'users.created_at',
                DB::raw('SUM(study_logs.xp_gained) as period_xp'),
                DB::raw('(SELECT COUNT(DISTINCT sl2.studied_date) FROM study_logs sl2 WHERE sl2.user_id = users.id) as total_study_days'),
            ])
            ->groupBy('users.id', 'users.name', 'users.total_xp', 'users.created_at')
            ->having('period_xp', '>', 0)
            ->orderByDesc('period_xp')
            ->orderBy('users.created_at');

        return $query->paginate($perPage);
    }

    private function calcMyMonthlyRank(User $user): int
    {
        $myXp = StudyLog::where('user_id', $user->id)
            ->whereMonth('studied_date', now()->month)
            ->whereYear('studied_date', now()->year)
            ->sum('xp_gained');

        // 解決策: 
        // サブクエリを明示的に作成し、その件数を数える方法をとります。
        $subQuery = DB::table('study_logs')
            ->join('users', 'study_logs.user_id', '=', 'users.id')
            ->whereMonth('study_logs.studied_date', now()->month)
            ->whereYear('study_logs.studied_date', now()->year)
            ->whereNull('users.deleted_at')
            ->select('study_logs.user_id')
            ->groupBy('study_logs.user_id')
            ->havingRaw('SUM(study_logs.xp_gained) > ?', [$myXp]);

        // サブクエリの結果をカウントする
        $higherCount = DB::table(DB::raw("({$subQuery->toSql()}) as temp_table"))
            ->mergeBindings($subQuery) // バインディング（パラメータ）を引き継ぐ
            ->count();

        return $higherCount + 1;
    }

    // ──────────────────────────────────────────────────────────────
    //  ユーティリティ
    // ──────────────────────────────────────────────────────────────

    /** @return array{string, string} [start_date, end_date] */
    private function weekRange(): array
    {
        return [
            Carbon::now()->startOfWeek()->toDateString(),
            Carbon::now()->endOfWeek()->toDateString(),
        ];
    }
}
