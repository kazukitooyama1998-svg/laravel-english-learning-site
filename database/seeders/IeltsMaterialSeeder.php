<?php

namespace Database\Seeders;

use App\Models\English\IeltsMaterial;
use App\Models\English\IeltsTopic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * IELTS タイピング教材 Seeder
 *
 * Part(1/2/3) × Topic(education/technology/environment) × Band(5.5/6.0/6.5/7.0)
 * の全組み合わせ = 36レコードを投入する。
 *
 * 質問(questions)はBand共通、回答(answers)はBandごとに個別の文章を使用し、
 * 同じ Part/Band の Slides で学習した語彙・表現を実際に使う構成になっている。
 * 実際のコンテンツは database/seeders/data/ielts_materials.php で管理する。
 *
 * updateOrCreate を使用しているため、内容を更新して再実行しても安全（重複しない）。
 */
class IeltsMaterialSeeder extends Seeder
{
    use WithoutModelEvents;

    private const TOPIC_LABELS = [
        'education'   => '教育',
        'technology'  => 'テクノロジー',
        'environment' => '環境',
    ];

    private const PART_LABELS = [
        1 => 'Introduction & Interview',
        2 => 'Long Turn (Cue Card)',
        3 => 'Discussion',
    ];

    public function run(): void
    {
        $topics = IeltsTopic::all()->keyBy('slug');
        $data   = require database_path('seeders/data/ielts_materials.php');

        foreach ($data as $part => $topicData) {
            foreach ($topicData as $topicSlug => $d) {
                $topic = $topics->get($topicSlug);

                if (!$topic) {
                    continue;
                }

                $topicLabel = self::TOPIC_LABELS[$topicSlug];
                $partLabel  = self::PART_LABELS[$part];

                foreach ($d['answers'] as $score => $answers) {
                    if ($part === 2) {
                        // Part 2 は1問1回答を1レコードとして登録し、出題時にランダムで1問選ぶ
                        foreach ($answers as $i => $answer) {
                            IeltsMaterial::updateOrCreate(
                                ['part' => $part, 'topic_id' => $topic->id, 'target_score' => $score, 'order_no' => $i + 1],
                                [
                                    'title'  => "IELTS Speaking Part {$part} - {$topicLabel} × {$score} (#" . ($i + 1) . ')',
                                    'prompt' => "Part {$part}（{$partLabel}）の「{$topicLabel}」トピック 模範解答をタイピングしましょう。目標スコア: IELTS {$score}",
                                    'text'   => $this->buildText([$d['questions'][$i]], [$answer]),
                                    'xp'     => $this->calcXp($score),
                                ]
                            );
                        }
                        continue;
                    }

                    IeltsMaterial::updateOrCreate(
                        ['part' => $part, 'topic_id' => $topic->id, 'target_score' => $score, 'order_no' => null],
                        [
                            'title'  => "IELTS Speaking Part {$part} - {$topicLabel} × {$score}",
                            'prompt' => "Part {$part}（{$partLabel}）の「{$topicLabel}」トピック 模範解答をタイピングしましょう。目標スコア: IELTS {$score}",
                            'text'   => $this->buildText($d['questions'], $answers),
                            'xp'     => $this->calcXp($score),
                        ]
                    );
                }
            }
        }
    }

    /**
     * [Question N] / [Answer] ブロックを質問数ぶん連結してタイピング本文を作る。
     *
     * @param string[] $questions
     * @param string[] $answers
     */
    private function buildText(array $questions, array $answers): string
    {
        $blocks = [];

        foreach ($questions as $i => $question) {
            $blocks[] = "[Question " . ($i + 1) . "]\n{$question}\n\n[Answer]\n{$answers[$i]}";
        }

        return implode("\n\n", $blocks);
    }

    private function calcXp(string $score): int
    {
        return match ($score) {
            '5.5'   => 80,
            '6.0'   => 100,
            '6.5'   => 120,
            '7.0'   => 150,
            default => 100,
        };
    }
}
