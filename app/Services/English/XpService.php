<?php

namespace App\Services\English;

use App\Models\User;

class XpService
{
    /**
     * ユーザーにXPを加算する。
     * Controller が直接 $user->increment() を呼ばず、必ずこのメソッド経由で加算する。
     */
    public function addXp(User $user, int $xp): void
    {
        $user->increment('total_xp', $xp);
    }

    /**
     * TOEIC問題セッションのXPを計算する。
     *
     * XP = (correctCount × 10) + 完了ボーナス
     * 完了ボーナス: 正答率 ≥ 80% → 100 XP、正答率 < 80% → 50 XP
     *
     * ⚠️ submitAnswer() では呼ばない。ToeicController@complete() でのみ呼ぶ（二重加算防止）
     */
    public function calcToeicXp(int $correctCount, int $totalQuestions): int
    {
        $accuracy = $totalQuestions > 0
            ? ($correctCount / $totalQuestions) * 100
            : 0;

        $baseXp = $correctCount * config('english.xp.toeic_per_correct', 10);
        $bonus  = $accuracy >= 80
            ? config('english.xp.toeic_bonus_high', 100)
            : config('english.xp.toeic_bonus_low', 50);

        return $baseXp + $bonus;
    }

    /**
     * IELTSタイピング完了時のXPを正答率から計算する。
     *
     * 正答率 ≥ 90% → 150 XP
     * 正答率 70〜89% → 100 XP
     * 正答率 70% 未満 → 50 XP
     */
    public function calcIeltsTypingXp(float $accuracy): int
    {
        if ($accuracy >= 90) {
            return config('english.xp.ielts_typing_accuracy_high', 150);
        }
        if ($accuracy >= 70) {
            return config('english.xp.ielts_typing_accuracy_mid', 100);
        }
        return config('english.xp.ielts_typing_accuracy_low', 50);
    }

    /**
     * クイズ（スペル・語彙）のXPを正解数から計算する。
     *
     * XP = correctCount × 5
     */
    public function calcQuizXp(int $correctCount): int
    {
        return $correctCount * config('english.xp.quiz_per_correct', 5);
    }

    /**
     * 現在のユーザーのレベル情報を配列で返す（View用ヘルパー）。
     *
     * @return array{level: int, current_xp: int, next_level_xp: int, xp_in_level: int, bar_percent: int}
     */
    public function getLevelInfo(User $user): array
    {
        $totalXp       = (int) $user->total_xp;
        $level         = (int) floor($totalXp / 500) + 1;
        $nextLevelXp   = $level * 500;
        $xpInLevel     = $totalXp % 500;
        $barPercent    = (int) round(($xpInLevel / 500) * 100);

        return [
            'level'         => $level,
            'current_xp'    => $totalXp,
            'next_level_xp' => $nextLevelXp,
            'xp_in_level'   => $xpInLevel,
            'bar_percent'   => $barPercent,
        ];
    }
}
