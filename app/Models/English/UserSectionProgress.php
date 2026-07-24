<?php

namespace App\Models\English;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserSectionProgress extends Model
{
    protected $table = 'user_section_progress';

    // section_type の定数
    const TYPE_TOEIC_SLIDES    = 'toeic_slides';
    const TYPE_TOEIC_QUESTIONS = 'toeic_questions';
    const TYPE_IELTS_SLIDES    = 'ielts_slides';
    const TYPE_IELTS_TYPING    = 'ielts_typing';
    const TYPE_TYPING_SLIDES   = 'typing_slides';
    const TYPE_TYPING_MATERIAL = 'typing_material';
    const TYPE_VOCABULARY      = 'vocabulary';

    protected $fillable = [
        'user_id',
        'section_type',
        'section_key',
        'last_step',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // ===== リレーション =====

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
