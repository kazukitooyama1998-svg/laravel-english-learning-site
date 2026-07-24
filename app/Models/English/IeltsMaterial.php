<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IeltsMaterial extends Model
{
    use SoftDeletes;

    protected $table = 'ielts_materials';

    protected $fillable = [
        'part',
        'topic_id',
        'target_score',
        'order_no',
        'title',
        'prompt',
        'text',
        'xp',
    ];

    // ===== リレーション =====

    public function topic()
    {
        return $this->belongsTo(IeltsTopic::class, 'topic_id');
    }

    // ===== スコープ =====

    /**
     * Part × Topic スラッグ × スコアでフィルタ（コントローラーからの呼び出し用）
     */
    public function scopeForSession($query, int $part, string $topicSlug, string $targetScore)
    {
        return $query
            ->where('part', $part)
            ->where('target_score', $targetScore)
            ->whereHas('topic', fn($q) => $q->where('slug', $topicSlug));
    }
}
