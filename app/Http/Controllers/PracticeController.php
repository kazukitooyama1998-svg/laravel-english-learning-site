<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Practice;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class PracticeController extends Controller
{
    // ログイン後の練習一覧画面 (Dashboard)
    public function index()
    {
        $user = Auth::user();
        
        // データベースからすべての練習データを取得
        $practices = Practice::all();

        // --- 進捗率の計算ロジック ---
        // 1. 全カテゴリー数を取得
        $totalCategories = Category::count();

        // 2. ユーザーが完了（練習）したユニークなカテゴリー数をカウント
        // recordsテーブルからpracticeを経由してcategory_idを取得し、重複を除外
        $completedCount = $user->records()
            ->join('practices', 'records.practice_id', '=', 'practices.id')
            ->distinct('practices.category_id')
            ->count('practices.category_id');

        // 3. 進捗率を算出（0除算を防ぐ）
        $progress = $totalCategories > 0 
            ? round(($completedCount / $totalCategories) * 100) 
            : 0;
        // ----------------------------

        // ログイン後の表示画面のBladeにデータを渡す
        return view('dashboard', compact('practices', 'progress'));
    }

    // 各タイピングゲーム画面
    public function show($id)
    {
        // クリックされたIDの練習データを取得。なければ404エラー
        $practice = Practice::findOrFail($id);

        // タイピング画面のBladeにデータを渡す
        // Blade側で変数名が異なっていた部分を補えるように展開して渡します
        return view('practice.show', [
            'prompt' => $practice->prompt,
            'typingText' => $practice->text
        ]);
    }


}
