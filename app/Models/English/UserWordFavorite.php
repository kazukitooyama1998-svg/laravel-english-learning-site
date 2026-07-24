<?php

namespace App\Models\English;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserWordFavorite extends Model
{
    protected $table = 'user_word_favorites';

    protected $fillable = [
        'user_id',
        'word_id',
    ];

    // ===== リレーション =====

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function word()
    {
        return $this->belongsTo(VocabularyWord::class, 'word_id');
    }
}
