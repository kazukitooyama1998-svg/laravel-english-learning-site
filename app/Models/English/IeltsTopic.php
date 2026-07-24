<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class IeltsTopic extends Model
{
    protected $table = 'ielts_topics';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'icon',
        'sort_order',
    ];

    // ===== スコープ =====

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
