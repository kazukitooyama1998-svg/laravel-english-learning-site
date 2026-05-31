<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PracticeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. ログイン前のホーム画面
Route::get('/', [HomeController::class, 'index'])->name('home');

// Laravelの認証機能（Login, Registerなど）のルートを読み込む
// ※ Laravel Breezeなどの標準設定
Auth::routes(); 

// 2. ログイン後のみアクセス可能なグループ (auth ミドルウェア)
Route::middleware(['auth'])->group(function () {
    
    // ログイン後のトップ（練習一覧画面）
    Route::get('/dashboard', [PracticeController::class, 'index'])->name('dashboard');
    
    // タイピング画面（詳細画面）
    Route::get('/practice/{id}', [PracticeController::class, 'show'])->name('practice.show');

});