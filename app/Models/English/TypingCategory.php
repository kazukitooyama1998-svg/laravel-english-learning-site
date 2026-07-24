<?php

namespace App\Models\English;

use Illuminate\Database\Eloquent\Model;

class TypingCategory extends Model
{
    protected $table = 'typing_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    // ===== リレーション =====

    public function materials()
    {
        return $this->hasMany(TypingMaterial::class, 'category_id');
    }
}
