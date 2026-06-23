<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record; // 
use App\Models\Practice;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    // プロパティとしてモデルを保持
    protected $recordModel;

    /**
     * コンストラクタで Record モデルをインジェクション（注入）
     */
    public function __construct(Record $recordModel)
    {
        $this->recordModel = $recordModel;
    }

    /**
     * タイピング結果をデータベースに保存する
     */
    public function store(Request $request, $id)
    {
        // 1. 教材の存在チェック（なければ最初の1件目を仮取得するか、IDをそのまま使う）
        $practice = Practice::find($id);
        $practiceId = $practice ? $practice->id : $id;

        // 2. バリデーション
        $validated = $request->validate([
            'wpm'        => 'required|integer|min:0',
            'accuracy'   => 'required|numeric|min:0|max:100',
            'clear_time' => 'required|numeric|min:0', 
        ]);

        // 3. データ保存（一括代入）
        $record = $this->recordModel->create([
            'user_id'     => Auth::id() ?? 1, // ログインしていなければ仮でユーザーID: 1
            'practice_id' => $practiceId,     // 確実にIDをセット
            'wpm'         => $validated['wpm'],
            'accuracy'    => $validated['accuracy'],
            'clear_time'  => $validated['clear_time'], 
        ]);

        // 4. レスポンスを返す
        return response()->json([
            'success' => true,
            'message' => 'Result recorded successfully!',
            'data'    => $record
        ], 201);
    }
}
