<?php

namespace App\Services\English;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StudyLogService
{
    public function __construct(
        private readonly StreakService $streakService
    ) {}

    /**
     * 学習活動を記録し、total_study_time の加算と streak の更新を一括実行する。
     *
     * Controller 内で 1 回のみ呼ぶこと（二重加算防止）。
     * スライド閲覧など時間が不定の活動は $durationSeconds = 0 で渡す。
     *
     * @param string   $activityType  StudyLog::TYPE_* 定数
     * @param int|null $activityId    関連レコードのID（vocabulary 等は null）
     * @param int      $xp            この活動で獲得した XP
     * @param int      $durationSeconds 活動時間（秒）
     */
    public function log(
        User $user,
        string $activityType,
        ?int $activityId,
        int $xp,
        int $durationSeconds
    ): void {
        DB::transaction(function () use ($user, $activityType, $activityId, $xp, $durationSeconds) {
            // 1. study_logs に INSERT
            $user->studyLogs()->create([
                'activity_type'    => $activityType,
                'activity_id'      => $activityId,
                'xp_gained'        => $xp,
                'duration_seconds' => $durationSeconds,
                'studied_date'     => Carbon::today()->toDateString(),
            ]);

            // 2. users.total_study_time を加算
            if ($durationSeconds > 0) {
                $user->increment('total_study_time', $durationSeconds);
            }

            // 3. streak を更新
            $this->streakService->update($user);
        });
    }

    /**
     * 最近の学習履歴を取得する（学習管理ダッシュボード用）。
     */
    public function getRecentLogs(User $user, int $limit = 10): Collection
    {
        return $user->studyLogs()
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
