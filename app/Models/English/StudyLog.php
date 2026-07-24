<?php

namespace App\Models\English;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StudyLog extends Model
{
    protected $table = 'study_logs';

    // activity_type の定数
    const TYPE_TYPING     = 'typing';
    const TYPE_IELTS      = 'ielts';
    const TYPE_TOEIC      = 'toeic';
    const TYPE_VOCABULARY = 'vocabulary';
    const TYPE_QUIZ       = 'quiz';

    protected $fillable = [
        'user_id',
        'activity_type',
        'activity_id',
        'xp_gained',
        'duration_seconds',
        'studied_date',
    ];

    protected $casts = [
        'studied_date' => 'date',
    ];

    // ===== リレーション =====

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
