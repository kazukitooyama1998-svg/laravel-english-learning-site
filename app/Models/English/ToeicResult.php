<?php

namespace App\Models\English;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ToeicResult extends Model
{
    protected $table = 'toeic_results';

    protected $fillable = [
        'user_id',
        'part',
        'total_questions',
        'correct_count',
        'xp_gained',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // ===== リレーション =====

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answerLogs()
    {
        return $this->hasMany(ToeicAnswerLog::class, 'result_id');
    }

    // ===== アクセサ =====

    /**
     * 正答率（%）
     */
    public function getAccuracyAttribute(): float
    {
        if ($this->total_questions === 0) {
            return 0.0;
        }
        return round($this->correct_count / $this->total_questions * 100, 1);
    }
}
