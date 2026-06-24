<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController; // 💡Admin用コントローラー（例）　後で\Adminを加える必要があるか確認

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
    // ダッシュボード（お題のカテゴリ選択 ＆ ランキング表示）
    Route::get('/dashboard', [PracticeController::class, 'index'])->name('dashboard');
    // タイピング練習画面
    Route::get('/practice/{id}', [PracticeController::class, 'show'])->name('practice.show');
    // ランキング画面
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
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
});

/*
|--------------------------------------------------------------------------
| 3. Admin（管理者）専用ルート（ログイン必須 ＆ Adminミドルウェア必須）
|--------------------------------------------------------------------------
*/
// 💡書き方を統一し、すっきりさせました
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        // 管理者用ユーザー一覧・検索
        Route::get('/users', [UsersController::class, 'index'])->name('users');
        Route::get('/search', [UsersController::class, 'search'])->name('search');
        
        // 💡ここに今後「お題の追加・論理削除（SoftDelete）」のルートを追加していくと綺麗です
        // Route::delete('/practice/{id}', [AdminPracticeController::class, 'destroy'])->name('practice.destroy');
    });