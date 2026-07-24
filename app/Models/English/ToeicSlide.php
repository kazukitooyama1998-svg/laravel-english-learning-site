<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class ToeicSlide extends Model
{
    protected $table = 'toeic_slides';

    protected $fillable = [
        'part',
        'step_number',
        'slide_type',
        'title',
        'content',
        'sort_order',
    ];
}
