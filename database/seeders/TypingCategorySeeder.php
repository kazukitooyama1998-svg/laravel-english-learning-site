<?php

namespace Database\Seeders;

use App\Models\English\TypingCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypingCategorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Business English',
                'slug'        => 'business-english',
                'description' => 'ビジネスシーンで使う英語フレーズ',
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Daily Conversation',
                'slug'        => 'daily-conversation',
                'description' => '日常会話・旅行・買い物で使う英語フレーズ',
                'sort_order'  => 2,
            ],
        ];

        foreach ($categories as $data) {
            TypingCategory::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
