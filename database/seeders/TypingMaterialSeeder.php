<?php

namespace Database\Seeders;

use App\Models\English\TypingCategory;
use App\Models\English\TypingMaterial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * タイピング練習 教材 Seeder
 *
 * カテゴリーごとに実際の場面で使うフレーズを1つ1つ登録する。
 * 出題時（TypingController::randomPractice）はこの中から毎回ランダムに1件選ばれる。
 * 実際のコンテンツは database/seeders/data/typing_materials.php で管理する。
 *
 * updateOrCreate を使用しているため、内容を更新して再実行しても安全（重複しない）。
 */
class TypingMaterialSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $categories = TypingCategory::all()->keyBy('slug');
        $data       = require database_path('seeders/data/typing_materials.php');

        foreach ($data as $categorySlug => $phrases) {
            $category = $categories->get($categorySlug);

            if (!$category) {
                continue;
            }

            foreach ($phrases as $i => $p) {
                TypingMaterial::updateOrCreate(
                    ['category_id' => $category->id, 'sort_order' => $i + 1],
                    [
                        'title'  => $p['scene'],
                        'level'  => $p['level'],
                        'prompt' => '実際の場面で使うフレーズをタイピングしましょう。',
                        'text'   => "[Question 1]\n{$p['scene']}\n\n[Answer]\n{$p['phrase']}\n\n[Meaning]\n{$p['phrase_ja']}",
                        'xp'     => 20,
                    ]
                );
            }
        }
    }
}
