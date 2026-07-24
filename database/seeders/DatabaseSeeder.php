<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 *
 * 実行順序は外部キー制約の依存関係に従う（英語学習DB設計書 §7 参照）
 *
 * php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ===== 既存シーダー（FocusType 互換） =====
        // 旧テーブル（categories / practices）への投入は一旦スキップ
        // 新テーブル（typing_categories / typing_materials）に移行済み
        // $this->call([CategorySeeder::class, PracticeSeeder::class]);

        // ===== ユーザー =====
        $this->call(UserSeeder::class);

        // ===== タイピング教材（英語学習）=====
        // カテゴリー毎のフレーズをランダム出題する方式のため、事前学習スライドは使用しない
        $this->call([
            TypingCategorySeeder::class,   // typing_categories
            TypingMaterialSeeder::class,   // typing_materials
        ]);

        // ===== IELTS =====
        $this->call([
            IeltsTopicSeeder::class,       // ielts_topics
            IeltsSlideSeeder::class,       // ielts_slides（IeltsTopic 作成後）
            IeltsMaterialSeeder::class,    // ielts_materials（IeltsTopic 作成後）
        ]);

        // ===== TOEIC =====
        $this->call([
            ToeicSlideSeeder::class,       // toeic_slides
            ToeicQuestionSeeder::class,    // toeic_questions + toeic_question_options
        ]);

        // ===== 英単語 =====
        $this->call(VocabularyWordSeeder::class);

        // ===== 試験概要・学習ストラテジー =====
        $this->call(LearningContentSeeder::class);
    }
}
