<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class LearningContent extends Model
{
    protected $table = 'learning_contents';

    // content_type の定数
    const TYPE_OVERVIEW  = 'overview';
    const TYPE_STRATEGY  = 'strategy';

    protected $fillable = [
        'content_type',
        'exam_type',
        'target_level',
        'title',
        'body',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // ===== スコープ =====

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOverview($query, string $examType)
    {
        return $query->where('content_type', self::TYPE_OVERVIEW)
                     ->where('exam_type', $examType);
    }

    public function scopeStrategy($query, string $examType, ?string $targetLevel = null)
    {
        return $query
            ->where('content_type', self::TYPE_STRATEGY)
            ->where('exam_type', $examType)
            ->when($targetLevel, fn($q) => $q->where('target_level', $targetLevel));
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
