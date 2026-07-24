<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 語彙単語 Seeder
 *
 * database/seeders/data/vocab_{exam}_{level}.php から読み込み、
 * 各レベル100語（TOEIC 4レベル + IELTS 4レベル = 合計800語）を投入する。
 *
 * 各データファイルは易→難の進行を保つため、同一 exam_type 内で
 * 既に採用済みの単語（大文字小文字を無視）は下位レベルを優先して除外する。
 */
class VocabularyWordSeeder extends Seeder
{
    use WithoutModelEvents;

    private const WORDS_PER_LEVEL = 100;

    /** @var array<string, array{exam_type: string, level: string, file: string}> */
    private const LEVELS = [
        ['exam_type' => 'TOEIC', 'level' => '600', 'file' => 'vocab_toeic_600.php'],
        ['exam_type' => 'TOEIC', 'level' => '700', 'file' => 'vocab_toeic_700.php'],
        ['exam_type' => 'TOEIC', 'level' => '800', 'file' => 'vocab_toeic_800.php'],
        ['exam_type' => 'TOEIC', 'level' => '900', 'file' => 'vocab_toeic_900.php'],
        ['exam_type' => 'IELTS', 'level' => '5.5', 'file' => 'vocab_ielts_55.php'],
        ['exam_type' => 'IELTS', 'level' => '6.0', 'file' => 'vocab_ielts_60.php'],
        ['exam_type' => 'IELTS', 'level' => '6.5', 'file' => 'vocab_ielts_65.php'],
        ['exam_type' => 'IELTS', 'level' => '7.0', 'file' => 'vocab_ielts_70.php'],
    ];

    public function run(): void
    {
        DB::table('vocabulary_words')->delete();

        /** @var array<string, array<string, true>> $seenByExamType 既に採用した単語（小文字化）を exam_type ごとに記録 */
        $seenByExamType = [];

        foreach (self::LEVELS as $levelDef) {
            $path = database_path('seeders/data/' . $levelDef['file']);

            if (!file_exists($path)) {
                $this->command?->warn("スキップ: {$path} が見つかりません");
                continue;
            }

            $entries = require $path;
            $examType = $levelDef['exam_type'];
            $seen     = $seenByExamType[$examType] ??= [];

            $rows = [];
            $sortOrder = 1;

            foreach ($entries as $entry) {
                if (count($rows) >= self::WORDS_PER_LEVEL) {
                    break;
                }

                $key = mb_strtolower(trim($entry['word']));

                if (isset($seen[$key])) {
                    continue; // 下位レベルで既に採用済みの単語はスキップ
                }

                $seen[$key] = true;

                $rows[] = [
                    'word'                 => $entry['word'],
                    'part_of_speech'       => $entry['part_of_speech'],
                    'meaning_ja'           => $entry['meaning_ja'],
                    'meaning_en'           => $entry['meaning_en'] ?? null,
                    'example_sentence'     => $entry['example_sentence'] ?? null,
                    'example_sentence_ja'  => $entry['example_sentence_ja'] ?? null,
                    'exam_type'            => $examType,
                    'level'                => $levelDef['level'],
                    'sort_order'           => $sortOrder++,
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ];
            }

            $seenByExamType[$examType] = $seen;

            if (count($rows) < self::WORDS_PER_LEVEL) {
                $this->command?->warn(sprintf(
                    '%s %s: %d語のみ確保（目標%d語）。データファイルの語数を増やしてください。',
                    $examType, $levelDef['level'], count($rows), self::WORDS_PER_LEVEL
                ));
            }

            foreach (array_chunk($rows, 100) as $chunk) {
                DB::table('vocabulary_words')->insert($chunk);
            }
        }
    }
}
