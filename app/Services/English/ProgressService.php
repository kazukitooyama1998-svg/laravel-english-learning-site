<?php

namespace App\Services\English;

use App\Models\User;
use App\Models\English\UserSectionProgress;
use App\Models\English\UserWordProgress;
use App\Models\English\TypingMaterial;
use App\Models\English\ToeicQuestion;
use App\Models\English\VocabularyWord;

class ProgressService
{
    /**
     * 機能別の進捗データを返す。
     *
     * 全体進捗率 = 4機能の進捗率の平均（機能別平均方式）
     * タイピング教材数は動的取得（TypingMaterial::count()）
     *
     * @return array{
     *   toeic: array{done: int, total: int, percent: int},
     *   ielts: array{done: int, total: int, percent: int},
     *   typing: array{done: int, total: int, percent: int},
     *   vocabulary: array{done: int, total: int, percent: int},
     * }
     */
    public function getSectionProgress(User $user): array
    {
        // 完了済みセクションを section_type ごとにグループ化して取得
        $completed = $user->sectionProgress()
            ->where('is_completed', true)
            ->get()
            ->groupBy('section_type');

        // TOEIC: Part5/6/7 の全問題数に対する回答済みユニーク問題数の割合
        $toeicTotal = ToeicQuestion::whereIn('part', [5, 6, 7])->count();
        $toeicDone  = $user->toeicAnswerLogs()
            ->whereIn('toeic_results.part', [5, 6, 7])
            ->distinct()
            ->count('toeic_answer_logs.question_id');

        // IELTS: 3Part × 3Topic × 4Score × 2種類(slides + typing) = 72 セクション
        $ieltsTotal = 72;
        $ieltsDone  = $completed->get(UserSectionProgress::TYPE_IELTS_SLIDES, collect())->count()
                    + $completed->get(UserSectionProgress::TYPE_IELTS_TYPING, collect())->count();

        // タイピング: 教材数（動的）× 1（typing_material のみ）
        $typingTotal = max(1, TypingMaterial::count());
        $typingDone  = $completed->get(UserSectionProgress::TYPE_TYPING_MATERIAL, collect())->count();

        // 語彙: DBに登録された全単語数に対する学習済み単語数の割合
        $vocabTotal = VocabularyWord::count();
        $vocabDone  = $user->wordProgress()->where('status', UserWordProgress::STATUS_LEARNED)->count();

        return [
            'toeic' => [
                'done'    => $toeicDone,
                'total'   => $toeicTotal,
                'percent' => $this->pct($toeicDone, $toeicTotal),
            ],
            'ielts' => [
                'done'    => $ieltsDone,
                'total'   => $ieltsTotal,
                'percent' => $this->pct($ieltsDone, $ieltsTotal),
            ],
            'typing' => [
                'done'    => $typingDone,
                'total'   => $typingTotal,
                'percent' => $this->pct($typingDone, $typingTotal),
            ],
            'vocabulary' => [
                'done'    => $vocabDone,
                'total'   => $vocabTotal,
                'percent' => $this->pct($vocabDone, $vocabTotal),
            ],
        ];
    }

    /**
     * 全体進捗率（%）を返す。
     * 4機能の進捗率の算術平均。
     */
    public function getOverallProgress(User $user): int
    {
        $progress = $this->getSectionProgress($user);
        $percents = array_column($progress, 'percent');
        return (int) round(array_sum($percents) / count($percents));
    }

    /**
     * 総学習時間（秒）を返す。
     * users.total_study_time を直接参照する（study_logs の集計は行わない）。
     */
    public function getTotalStudyTime(User $user): int
    {
        return (int) $user->total_study_time;
    }

    /**
     * 秒数を「X時間Y分」の配列に変換するユーティリティ。
     *
     * @return array{hours: int, minutes: int}
     */
    public static function formatStudyTime(int $totalSeconds): array
    {
        return [
            'hours'   => intdiv($totalSeconds, 3600),
            'minutes' => intdiv($totalSeconds % 3600, 60),
        ];
    }

    /**
     * TOEIC・IELTS試験日までの残り日数を返す（未設定の場合は null）。
     */
    public function getExamDaysLeft(User $user): array
    {
        return [
            'toeic' => $user->toeic_exam_date
                ? (int) now()->startOfDay()->diffInDays($user->toeic_exam_date, false)
                : null,
            'ielts' => $user->ielts_exam_date
                ? (int) now()->startOfDay()->diffInDays($user->ielts_exam_date, false)
                : null,
        ];
    }

    private function pct(int $done, int $total): int
    {
        if ($total === 0) {
            return 0;
        }
        return (int) round(($done / $total) * 100);
    }
}
