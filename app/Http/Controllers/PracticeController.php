<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Practice;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class PracticeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // すべての練習データを取得
        $practices = Practice::all();

        // --- 進捗率の計算ロジック（修正後） ---
        
        // 1. 全練習課題の総数を取得
        $totalPractices = Practice::count();

        // 2. ユーザーが過去に練習したユニークな練習課題の数を取得
        // recordsテーブルからpractice_idを重複除外してカウント
        $completedCount = $user->records()
            ->distinct('practice_id')
            ->count('practice_id');

        // 3. 進捗率を算出
        $progress = ($totalPractices > 0) 
            ? round(($completedCount / $totalPractices) * 100) 
            : 0;
        // -------------------------------------

        return view('dashboard', compact('practices', 'progress'));
    }

    // 各タイピングゲーム画面
    public function show($id)
    {
        // 練習データを取得
        $practice = Practice::findOrFail($id);

        // 💡 $practice そのものを渡すのが一番確実です
        return view('practice.show', [
            'practice' => $practice,
            'typingText' => $practice->text // 必要であればこれも継続して渡す
        ]);
    }


}
