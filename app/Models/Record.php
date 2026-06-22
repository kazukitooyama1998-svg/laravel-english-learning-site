<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    // 一括保存を許可するカラムを指定
    protected $fillable = ['user_id', 'practice_id', 'wpm', 'accuracy'];

    // 💡 多対1：この履歴は特定のユーザーのもの
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 💡 多対1：この履歴は特定のお題のもの
    public function practice()
    {
        return $this->belongsTo(Practice::class);
    }
}
