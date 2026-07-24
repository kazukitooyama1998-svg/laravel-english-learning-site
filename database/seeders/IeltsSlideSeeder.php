<?php

namespace Database\Seeders;

use App\Models\English\IeltsSlide;
use App\Models\English\IeltsTopic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * IELTS スライド Seeder
 *
 * Part(1/2/3) × Topic(education/technology/environment) × Band(5.5/6.0/6.5/7.0)
 * の全組み合わせに、4種類のスライド（vocabulary / grammar / expression / tip）を投入する。
 * 実際のコンテンツは database/seeders/data/ielts_slides.php で管理する。
 *
 * updateOrCreate を使用しているため、内容を更新して再実行しても安全（重複しない）。
 */
class IeltsSlideSeeder extends Seeder
{
    use WithoutModelEvents;

    /** @var array<int,string> スライドの表示順（vocabulary → grammar → expression → tip） */
    private const SLIDE_ORDER = ['vocabulary', 'grammar', 'expression', 'tip'];

    public function run(): void
    {
        $topics = IeltsTopic::all()->keyBy('slug');
        $data   = require database_path('seeders/data/ielts_slides.php');

        foreach ($data as $part => $topicSlides) {
            foreach ($topicSlides as $topicSlug => $bandSlides) {
                $topic = $topics->get($topicSlug);

                if (!$topic) {
                    continue;
                }

                foreach ($bandSlides as $score => $slides) {
                    foreach (self::SLIDE_ORDER as $stepNumber => $slideType) {
                        $slide = $slides[$slideType];

                        IeltsSlide::updateOrCreate(
                            [
                                'part'         => $part,
                                'topic_id'     => $topic->id,
                                'target_score' => $score,
                                'step_number'  => $stepNumber + 1,
                            ],
                            [
                                'slide_type' => $slideType,
                                'title'      => $slide['title'],
                                'content'    => $slide['content'],
                                'sort_order' => $stepNumber + 1,
                            ]
                        );
                    }
                }
            }
        }
    }
}
