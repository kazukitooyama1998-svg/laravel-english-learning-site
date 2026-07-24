<?php

namespace App\Http\Controllers\English\Content;

use App\Http\Controllers\Controller;
use App\Models\English\LearningContent;

class LearningContentController extends Controller
{
    /**
     * 試験概要 メニュー (S23)
     * GET /english/overview
     */
    public function overviewIndex()
    {
        return view('english.overview.index');
    }

    /**
     * 試験概要 詳細 (S24: IELTS / S25: TOEIC)
     * GET /english/overview/{exam}
     */
    public function overviewShow(string $exam)
    {
        // DB に概要コンテンツがある場合は取得
        $content = LearningContent::overview($exam)->published()->ordered()->get();

        $view = match ($exam) {
            'ielts' => 'english.overview.ielts',
            'toeic' => 'english.overview.toeic',
            default => 'english.overview.index',
        };

        return view($view, compact('exam', 'content'));
    }

    /**
     * 学習ストラテジー メニュー (S26)
     * GET /english/strategy
     */
    public function strategyIndex()
    {
        $toeicStrategies = LearningContent::strategy('toeic')
            ->published()
            ->ordered()
            ->get();

        $ieltsStrategies = LearningContent::strategy('ielts')
            ->published()
            ->ordered()
            ->get();

        // DB データを blade が期待するキー構造に変換するクロージャ
        $toArray = fn($c) => [
            'exam'  => strtolower($c->exam_type),
            'level' => $c->target_level,
            'label' => $c->title,
            'desc'  => mb_strimwidth(strip_tags($c->body ?? ''), 0, 40, '...'),
        ];

        // DB 未投入時のフォールバック
        if ($toeicStrategies->isEmpty()) {
            $toeicStrategies = collect([
                ['exam' => 'toeic', 'level' => '600', 'label' => 'TOEIC 600点目標', 'desc' => '基礎文法と頻出語彙を押さえる'],
                ['exam' => 'toeic', 'level' => '700', 'label' => 'TOEIC 700点目標', 'desc' => 'Part5・6の精度を高める'],
                ['exam' => 'toeic', 'level' => '800', 'label' => 'TOEIC 800点目標', 'desc' => 'Part7の速読力を鍛える'],
                ['exam' => 'toeic', 'level' => '900', 'label' => 'TOEIC 900点目標', 'desc' => '満点を目指す総合学習戦略'],
            ]);
        } else {
            $toeicStrategies = $toeicStrategies->map($toArray);
        }

        if ($ieltsStrategies->isEmpty()) {
            $ieltsStrategies = collect([
                ['exam' => 'ielts', 'level' => '5.5', 'label' => 'IELTS 5.5目標', 'desc' => '基本的なコミュニケーション力を養う'],
                ['exam' => 'ielts', 'level' => '6.0', 'label' => 'IELTS 6.0目標', 'desc' => '大学入学水準の英語力を目指す'],
                ['exam' => 'ielts', 'level' => '6.5', 'label' => 'IELTS 6.5目標', 'desc' => '専門的な議論ができる英語力を養う'],
                ['exam' => 'ielts', 'level' => '7.0', 'label' => 'IELTS 7.0目標', 'desc' => '流暢かつ正確な英語使用を目指す'],
            ]);
        } else {
            $ieltsStrategies = $ieltsStrategies->map($toArray);
        }

        return view('english.strategy.index', compact('toeicStrategies', 'ieltsStrategies'));
    }

    /**
     * 学習ストラテジー 詳細 (S27)
     * GET /english/strategy/{exam}/{level}
     */
    public function strategyShow(string $exam, string $level)
    {
        // DB にコンテンツがあれば取得
        $content = LearningContent::strategy($exam, $level)
            ->published()
            ->ordered()
            ->first();

        // DB 未投入時のフォールバック（静的データ）
        if (!$content) {
            $strategy = [
                'exam'  => $exam,
                'level' => $level,
                'title' => strtoupper($exam) . ' ' . ($exam === 'toeic' ? $level . '点' : $level) . ' 攻略ストラテジー',
                'learning_order' => [
                    ['step' => 1, 'title' => '単語力の強化',   'desc' => 'まず頻出語彙100語を完全にマスターすることが基礎となります。'],
                    ['step' => 2, 'title' => '文法基礎の確認', 'desc' => 'Part5の文法問題に対応できるよう、主要文法ポイントを整理します。'],
                    ['step' => 3, 'title' => '読解力の向上',   'desc' => 'Part6・7の長文読解に向けて、速読と精読のバランスを養います。'],
                    ['step' => 4, 'title' => '模擬練習',        'desc' => '実際の問題形式に慣れるため、繰り返し練習問題を解きます。'],
                ],
                'key_points' => [
                    '毎日30分の継続学習が最も効果的です',
                    '間違えた問題は必ず解説を読んで理解してください',
                    '語彙は文脈と一緒に覚えるとより記憶に残ります',
                    'スコアアップには最低2〜3ヶ月の継続学習が必要です',
                ],
                'study_schedule' => [
                    '平日: 単語15分 + 文法10分 + 読解5分',
                    '週末: 練習問題45分 + 復習15分',
                ],
                'common_mistakes' => [
                    '単語の丸暗記に頼り過ぎて文脈理解がおろそかになる',
                    '間違えた問題を放置して同じミスを繰り返す',
                    '焦って量をこなそうとして理解が浅くなる',
                ],
            ];

            return view('english.strategy.show', compact('exam', 'level', 'strategy'));
        }

        return view('english.strategy.show', compact('exam', 'level', 'content'));
    }
}
