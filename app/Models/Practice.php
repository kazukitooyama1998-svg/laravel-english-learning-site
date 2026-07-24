<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Practice extends Model
{
    use HasFactory;
    use SoftDeletes; // 💡要件：論理削除を有効化

    // データベースに保存を許可するカラムを指定
    protected $fillable = [
        'category_id',
        'title',
        'level',
        'prompt',
        'text'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
