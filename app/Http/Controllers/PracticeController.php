<?php

namespace App\Http\Controllers;

use App\Models\Practice;

class PracticeController extends Controller
{
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
