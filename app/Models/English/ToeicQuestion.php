<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToeicQuestion extends Model
{
    use SoftDeletes;

    protected $table = 'toeic_questions';

    protected $fillable = [
        'part',
        'passage_id',
        'question_text',
        'explanation',
        'difficulty',
        'xp',
        'sort_order',
    ];

    // ===== リレーション =====

    public function options()
    {
        return $this->hasMany(ToeicQuestionOption::class, 'question_id');
    }

    public function passage()
    {
        return $this->belongsTo(ToeicPassage::class, 'passage_id');
    }

    // ===== スコープ =====

    public function scopeForPart($query, int $part)
    {
        return $query->where('part', $part)->orderBy('sort_order');
    }
}
