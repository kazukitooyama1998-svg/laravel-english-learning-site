<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController; // 💡Admin用コントローラー（例）　後で\Adminを加える必要があるか確認

// 英語学習モジュール Controllers
use App\Http\Controllers\English\HubController;
use App\Http\Controllers\English\ForestController;
use App\Http\Controllers\English\Toeic\ToeicController;
use App\Http\Controllers\English\Ielts\IeltsController;
use App\Http\Controllers\English\Vocabulary\VocabularyController;
use App\Http\Controllers\English\Typing\TypingController;
use App\Http\Controllers\English\Quiz\QuizController;
use App\Http\Controllers\English\Content\LearningContentController;
use App\Http\Controllers\English\ProgressController as EnglishProgressController;
use App\Http\Controllers\English\RankingController as EnglishRankingController;

/*
|--------------------------------------------------------------------------
| 1. 公開ルート（ログインなしでアクセス可能）
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Laravelの標準認証ルート（Login, Register, メール認証など）
Auth::routes(['verify' => true]); // 💡'verify' => true で要件のメール認証を有効化

/*
|--------------------------------------------------------------------------
| 2. 受講生・一般ユーザー共通ルート（ログイン必須 ＆ メール認証済み必須）
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // --- タイピング・学習関連 ---
    // ダッシュボード → 英語学習Hub へリダイレクト
    Route::get('/dashboard', [HubController::class, 'index'])->name('dashboard');
    // タイピング練習画面
    Route::get('/practice/{id}', [PracticeController::class, 'show'])->name('practice.show');
    // 💡新規：タイピング結果の保存（1対多リレーションの要件用）
    Route::post('/practice/{id}/result', [RecordController::class, 'store'])->name('record.store');
    // 💡新規：マイページ・学習履歴一覧
    Route::get('/history', [RecordController::class, 'index'])->name('history');

    // --- Partner---
    Route::get('/partners', [FollowController::class, 'index'])->name('partners.index');
    Route::post('/follow/{id}', [FollowController::class, 'follow'])->name('follow');
    Route::post('/unfollow/{id}', [FollowController::class, 'unfollow'])->name('unfollow');

    // --- チャット関連 ---
    // 相互フォロー関係をチェックしてチャットを開始する仲介ルート
    Route::get('/partners/{id}/chat', [FollowController::class, 'startChat'])->name('partners.chat');

    // チャット画面
    Route::get('/chat/{room_id}', [MessageController::class, 'show'])->name('chat.show');
    Route::post('/chat/{room_id}/message', [MessageController::class, 'store'])->name('message.store');

    // Profile
    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [UserController::class, 'update'])->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | 英語学習モジュール（/english プレフィックス）
    |--------------------------------------------------------------------------
    */
    Route::prefix('english')->name('english.')->group(function () {

        // ── Hub ──────────────────────────────────────────────────────────
        Route::get('/', [HubController::class, 'index'])->name('hub');

        // ── TOEIC ────────────────────────────────────────────────────────
        // {part} は 5・6・7 のみ許可（Route レベルで 404 に）
        Route::prefix('toeic')->name('toeic.')->group(function () {
            Route::get('/',                                  [ToeicController::class, 'index'])->name('index');
            Route::get('/{part}/slides',                     [ToeicController::class, 'slides'])->name('slides')->whereIn('part', config('english.toeic_parts'));
            Route::get('/{part}/slides/{step}',              [ToeicController::class, 'slides'])->name('slides.step')->whereIn('part', config('english.toeic_parts'))->whereNumber('step');
            Route::post('/{part}/slides/complete',           [ToeicController::class, 'completeSlides'])->name('slides.complete')->whereIn('part', config('english.toeic_parts'));
            Route::get('/{part}/practice',                   [ToeicController::class, 'practice'])->name('practice')->whereIn('part', config('english.toeic_parts'));
            Route::post('/{part}/answer',                    [ToeicController::class, 'submitAnswer'])->name('answer')->whereIn('part', config('english.toeic_parts'));
            Route::post('/{part}/complete',                  [ToeicController::class, 'complete'])->name('complete')->whereIn('part', config('english.toeic_parts'));
            Route::get('/{part}/result',                     [ToeicController::class, 'result'])->name('result')->whereIn('part', config('english.toeic_parts'));
        });

        // ── IELTS ─────────────────────────────────────────────────────────
        // {part}=1〜3 / {topic}=education|technology|environment / {score}=5.5|6.0|6.5|7.0
        Route::prefix('ielts')->name('ielts.')->group(function () {
            Route::get('/',                                                                 [IeltsController::class, 'index'])->name('index');
            Route::get('/speaking/{part}/topic',                                            [IeltsController::class, 'topic'])->name('topic')->whereIn('part', config('english.ielts_parts'));
            Route::get('/speaking/{part}/{topic}/score',                                    [IeltsController::class, 'score'])->name('score')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'));
            Route::get('/speaking/{part}/{topic}/{score}/slides',                           [IeltsController::class, 'slides'])->name('slides')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'))->whereIn('score', config('english.ielts_scores'));
            Route::get('/speaking/{part}/{topic}/{score}/slides/{step}',                    [IeltsController::class, 'slides'])->name('slides.step')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'))->whereIn('score', config('english.ielts_scores'))->whereNumber('step');
            Route::post('/speaking/{part}/{topic}/{score}/slides/complete',                 [IeltsController::class, 'completeSlides'])->name('slides.complete')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'))->whereIn('score', config('english.ielts_scores'));
            Route::get('/speaking/{part}/{topic}/{score}/typing',                           [IeltsController::class, 'typing'])->name('typing')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'))->whereIn('score', config('english.ielts_scores'));
            Route::post('/speaking/{part}/{topic}/{score}/result',                          [IeltsController::class, 'storeResult'])->name('result.store')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'))->whereIn('score', config('english.ielts_scores'));
            Route::get('/speaking/{part}/{topic}/{score}/result',                           [IeltsController::class, 'result'])->name('result')->whereIn('part', config('english.ielts_parts'))->whereIn('topic', config('english.ielts_topics'))->whereIn('score', config('english.ielts_scores'));
        });

        // ── 英単語 ────────────────────────────────────────────────────────
        // {level} は vocabulary_level_slugs のみ許可
        Route::prefix('vocabulary')->name('vocabulary.')->group(function () {
            Route::get('/',                              [VocabularyController::class, 'index'])->name('index');
            // favorite / progress / favorites は {level} より先に定義（ルーティング衝突防止）
            Route::get('/favorites',                     [VocabularyController::class, 'favorites'])->name('favorites');
            Route::post('/favorite',                     [VocabularyController::class, 'toggleFavorite'])->name('favorite');
            Route::post('/progress',                     [VocabularyController::class, 'updateProgress'])->name('progress');
            Route::get('/{level}/flashcard',             [VocabularyController::class, 'flashcard'])->name('flashcard')->whereIn('level', config('english.vocabulary_level_slugs'));
            Route::get('/{level}/spelling',              [VocabularyController::class, 'spelling'])->name('spelling')->whereIn('level', config('english.vocabulary_level_slugs'));
            Route::post('/{level}/spelling/check',       [VocabularyController::class, 'checkSpelling'])->name('spelling.check')->whereIn('level', config('english.vocabulary_level_slugs'));
            Route::post('/{level}/spelling/complete',    [VocabularyController::class, 'completeSpelling'])->name('spelling.complete')->whereIn('level', config('english.vocabulary_level_slugs'));
            Route::get('/{level}/quiz',                  [VocabularyController::class, 'quiz'])->name('quiz')->whereIn('level', config('english.vocabulary_level_slugs'));
            Route::post('/{level}/quiz/submit',          [VocabularyController::class, 'submitQuiz'])->name('quiz.submit')->whereIn('level', config('english.vocabulary_level_slugs'));
        });

        // ── タイピング（単独） ─────────────────────────────────────────────
        Route::prefix('typing')->name('typing.')->group(function () {
            Route::get('/',                        [TypingController::class, 'index'])->name('index');
            Route::get('/category/{category}/practice', [TypingController::class, 'randomPractice'])->name('category.practice');
            Route::get('/{id}/slides',             [TypingController::class, 'slides'])->name('slides')->whereNumber('id');
            Route::get('/{id}/slides/{step}',      [TypingController::class, 'slides'])->name('slides.step')->whereNumber('id')->whereNumber('step');
            Route::post('/{id}/slides/complete',   [TypingController::class, 'completeSlides'])->name('slides.complete')->whereNumber('id');
            Route::get('/{id}/practice',           [TypingController::class, 'practice'])->name('practice')->whereNumber('id');
            Route::post('/{id}/result',            [TypingController::class, 'storeResult'])->name('result.store')->whereNumber('id');
            Route::get('/{id}/result',             [TypingController::class, 'result'])->name('result')->whereNumber('id');
        });

        // ── クイズ ────────────────────────────────────────────────────────
        Route::prefix('quiz')->name('quiz.')->group(function () {
            Route::get('/',                        [QuizController::class, 'index'])->name('index');
            Route::get('/spelling',                [QuizController::class, 'spelling'])->name('spelling');
            Route::post('/spelling/submit',        [QuizController::class, 'submitSpelling'])->name('spelling.submit');
            Route::get('/vocabulary',              [QuizController::class, 'vocabulary'])->name('vocabulary');
            Route::post('/vocabulary/submit',      [QuizController::class, 'submitVocabulary'])->name('vocabulary.submit');
        });

        // ── 試験概要 ──────────────────────────────────────────────────────
        Route::prefix('overview')->name('overview.')->group(function () {
            Route::get('/',        [LearningContentController::class, 'overviewIndex'])->name('index');
            Route::get('/{exam}',  [LearningContentController::class, 'overviewShow'])->name('show')->whereIn('exam', config('english.exam_types'));
        });

        // ── 学習ストラテジー ──────────────────────────────────────────────
        Route::prefix('strategy')->name('strategy.')->group(function () {
            Route::get('/',               [LearningContentController::class, 'strategyIndex'])->name('index');
            Route::get('/{exam}/{level}', [LearningContentController::class, 'strategyShow'])->name('show')->whereIn('exam', config('english.exam_types'));
        });

        // ── 英語の森（3D アイランド） ─────────────────────────────────────
        // ログイン済みユーザーのみ入島・移動できる（auth + verified グループ内）
        Route::get('/forest', [ForestController::class, 'index'])->name('forest');

        // ── 学習管理・ランキング ──────────────────────────────────────────
        Route::get('/progress', [EnglishProgressController::class, 'index'])->name('progress');
        Route::post('/progress/exam-date', [EnglishProgressController::class, 'updateExamDate'])->name('progress.exam-date');
        Route::get('/ranking',  [EnglishRankingController::class, 'index'])->name('ranking');
    });
});

/*
|--------------------------------------------------------------------------
| 3. Admin（管理者）専用ルート（ログイン必須 ＆ Adminミドルウェア必須）
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {     
     
        // 管理者用ユーザー一覧・検索
        Route::get('/users', [UsersController::class, 'index'])->name('users');
        Route::get('/search', [UsersController::class, 'search'])->name('search');
        
        // アカウントのアクティブ切り替え
        Route::patch('/users/{id}/activate', [UsersController::class, 'activate'])->name('users.activate');
        Route::delete('/users/{id}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate');

        // 今後Postもアクティブ切り替え検討
    });