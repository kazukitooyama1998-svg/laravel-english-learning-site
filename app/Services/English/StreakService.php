<?php

namespace App\Services\English;

use App\Models\User;
use Carbon\Carbon;

class StreakService
{
    /**
     * 学習後に連続学習日数（streak）を更新する。
     * StudyLogService::log() のトランザクション内から呼ばれる。
     *
     * - 前日学習済み  → streak + 1
     * - 当日学習済み  → 変更なし（重複更新を防ぐ）
     * - 2日以上経過   → streak を 1 にリセット
     * - 初回          → streak = 1
     */
    public function update(User $user): void
    {
        $today     = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        $lastDate = $user->last_study_date
            ? (is_string($user->last_study_date) ? $user->last_study_date : $user->last_study_date->toDateString())
            : null;

        if ($lastDate === $today) {
            // 今日すでに学習済み → 変更不要
            return;
        }

        if ($lastDate === $yesterday) {
            // 連続学習
            $user->study_streak += 1;
        } else {
            // 初回 or 2日以上ブランク
            $user->study_streak = 1;
        }

        $user->last_study_date = $today;
        $user->save();
    }

    /**
     * 累計学習日数（重複なし）を返す。
     * ProgressController で「学習した日の合計」の表示に使用する。
     */
    public function getTotalStudyDays(User $user): int
    {
        return $user->studyLogs()
            ->distinct('studied_date')
            ->count('studied_date');
    }
}
