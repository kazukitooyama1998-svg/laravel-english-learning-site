<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypingMaterial extends Model
{
    use SoftDeletes;

    protected $table = 'typing_materials';

    protected $fillable = [
        'category_id',
        'title',
        'level',
        'prompt',
        'text',
        'xp',
        'sort_order',
    ];

    // ===== リレーション =====

    public function category()
    {
        return $this->belongsTo(TypingCategory::class, 'category_id');
    }

    public function slides()
    {
        return $this->hasMany(TypingSlide::class, 'material_id')->orderBy('step_number');
    }

    // ===== スコープ =====

    /**
     * セッション用フィルタスコープ（カテゴリ × レベル）
     */
    public function scopeForSession($query, string $categorySlug, ?string $level = null)
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        })->when($level, fn($q) => $q->where('level', $level));
    }
}
