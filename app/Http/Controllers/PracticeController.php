<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    // ログイン後の練習一覧画面 (Dashboard)
    public function index()
    {
        // データベースからすべての練習データを取得
        $practices = Practice::all();

        // 週間の目標進捗率（現在は仮に75%としていますが、将来的に計算可能）
        $progress = 75;

        // ログイン後の表示画面のBlade（例: dashboard.blade.php）にデータを渡す
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
