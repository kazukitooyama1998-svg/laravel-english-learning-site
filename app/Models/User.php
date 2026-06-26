<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 1; // administrator
    const USER_ROLE_ID = 2; // the regular user

    // 💡 1対多：ユーザーはたくさんの練習履歴（レコード）を持つ
    public function records()
    {
        return $this->hasMany(Record::class);
    }

    // フォローしている人（自分がフォローしている相手）
    public function followings() {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    // フォロワー（自分をフォローしている人）
    public function followers() {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    // 相互フォロー判定メソッド
    public function isMutualFollow($userId) {
        return $this->followings()->where('following_id', $userId)->exists() &&
            $this->followers()->where('follower_id', $userId)->exists();
    }

    public function studyLogs()
    {
        return $this->hasMany(StudyLog::class);
    }

    public function getNextLevelXpAttribute()
    {
        // レベルアップに必要なXP（例: レベル100ごとに1レベルUP）
        return $this->level * 500; 
    }

    public function getLevelAttribute()
    {
        // 累積XPから現在のレベルを動的に算出
        return floor($this->total_xp / 500) + 1;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
