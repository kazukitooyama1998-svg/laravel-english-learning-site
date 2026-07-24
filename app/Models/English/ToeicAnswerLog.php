<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class ToeicAnswerLog extends Model
{
    protected $table = 'toeic_answer_logs';

    protected $fillable = [
        'result_id',
        'question_id',
        'selected_option_id',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // ===== リレーション =====

    public function question()
    {
        return $this->belongsTo(ToeicQuestion::class, 'question_id');
    }

    public function selectedOption()
    {
        return $this->belongsTo(ToeicQuestionOption::class, 'selected_option_id');
    }
}
