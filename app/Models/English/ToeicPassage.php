<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class ToeicPassage extends Model
{
    protected $table = 'toeic_passages';

    protected $fillable = [
        'part',
        'passage_type',
        'title',
        'documents',
        'sort_order',
    ];

    protected $casts = [
        'documents' => 'array',
    ];
}
