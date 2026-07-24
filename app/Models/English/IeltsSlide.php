<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class IeltsSlide extends Model
{
    protected $table = 'ielts_slides';

    protected $fillable = [
        'part',
        'topic_id',
        'target_score',
        'step_number',
        'slide_type',
        'title',
        'content',
        'sort_order',
    ];

    // ===== スコープ =====

    /**
     * Part × Topic × スコアでフィルタ
     */
    public function scopeForSession($query, int $part, int $topicId, string $targetScore)
    {
        return $query
            ->where('part', $part)
            ->where('topic_id', $topicId)
            ->where('target_score', $targetScore)
            ->orderBy('step_number');
    }
}
