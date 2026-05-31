<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    use HasFactory;

    // データベースに保存を許可するカラムを指定
    protected $fillable = [
        'category',
        'title',
        'level',
        'prompt',
        'text'
    ];
}
