<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\English\IeltsRecord;
use App\Models\English\QuizResult;
use App\Models\English\StudyLog;
use App\Models\English\ToeicAnswerLog;
use App\Models\English\ToeicResult;
use App\Models\English\TypingRecord;
use App\Models\English\UserSectionProgress;
use App\Models\English\UserWordFavorite;
use App\Models\English\UserWordProgress;
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

    // ===== 既存リレーション（FocusType互換） =====

    // 旧タイピング練習履歴（既存との互換性維持）
    public function records()
    {
        return $this->hasMany(Record::class);
    }

    // フォローしている人（自分がフォローしている相手）
    public function followings()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    // フォロワー（自分をフォローしている人）
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    // 相互フォロー判定メソッド
    public function isMutualFollow($userId)
    {
        return $this->followings()->where('following_id', $userId)->exists() &&
            $this->followers()->where('follower_id', $userId)->exists();
    }

    // ===== 英語学習リレーション =====

    public function typingRecords()
    {
        return $this->hasMany(TypingRecord::class);
    }

    public function ieltsRecords()
    {
        return $this->hasMany(IeltsRecord::class);
    }

    public function toeicResults()
    {
        return $this->hasMany(ToeicResult::class);
    }

    public function toeicAnswerLogs()
    {
        return $this->hasManyThrough(ToeicAnswerLog::class, ToeicResult::class, 'user_id', 'result_id');
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }

    public function studyLogs()
    {
        return $this->hasMany(StudyLog::class);
    }

    public function sectionProgress()
    {
        return $this->hasMany(UserSectionProgress::class);
    }

    public function wordFavorites()
    {
        return $this->hasMany(UserWordFavorite::class);
    }

    public function wordProgress()
    {
        return $this->hasMany(UserWordProgress::class);
    }

    // ===== アクセサ =====

    /**
     * 累積XPから現在のレベルを動的に算出
     * level = floor(total_xp / 500) + 1
     */
    public function getLevelAttribute(): int
    {
        return (int) floor($this->total_xp / 500) + 1;
    }

    /**
     * 次のレベルに必要な累積XP
     */
    public function getNextLevelXpAttribute(): int
    {
        return $this->level * 500;
    }

    /**
     * 現在のレベル内でのXP（進捗バー用）
     * current_level_xp = total_xp % 500
     */
    public function getCurrentLevelXpAttribute(): int
    {
        return (int) ($this->total_xp % 500);
    }

    /**
     * 選択済みキャラクターの情報を config/english.php から解決して返す。
     * 未選択（登録直後など）の場合は 1 匹目をフォールバックとして返す。
     */
    public function getCharacterAttribute(): array
    {
        $characters = config('english.characters');
        $key        = $this->character_key && isset($characters[$this->character_key])
            ? $this->character_key
            : array_key_first($characters);

        return ['key' => $key] + $characters[$key];
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
        'character_key',
        'introduction',
        'role_id',
        'total_xp',
        'study_streak',
        'last_study_date',
        'total_study_time',
        'toeic_exam_date',
        'ielts_exam_date',
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
            'email_verified_at'  => 'datetime',
            'password'           => 'hashed',
            'last_study_date'    => 'date',
            'total_xp'           => 'integer',
            'study_streak'       => 'integer',
            'total_study_time'   => 'integer',
            'toeic_exam_date'    => 'date',
            'ielts_exam_date'    => 'date',
        ];
    }
}
