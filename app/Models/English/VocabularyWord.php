<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class VocabularyWord extends Model
{
    protected $table = 'vocabulary_words';

    protected $fillable = [
        'word',
        'part_of_speech',
        'meaning_ja',
        'meaning_en',
        'example_sentence',
        'example_sentence_ja',
        'exam_type',
        'level',
        'sort_order',
    ];

    // ===== スコープ =====

    /**
     * exam_type + level でフィルタ（最も使用頻度が高いクエリ）
     *
     * 使用例: VocabularyWord::byLevel('TOEIC', '700')->get()
     */
    public function scopeByLevel($query, string $examType, string $level)
    {
        return $query
            ->where('exam_type', $examType)
            ->where('level', $level)
            ->orderBy('sort_order');
    }
}
