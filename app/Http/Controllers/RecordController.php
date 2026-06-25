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
        // ログで受け取ったIDを確認
        \Log::info('Received $id: ' . $id);
        
        $practice = Practice::find($id);
        
        // ログで検索した教材のIDを確認
        \Log::info('Found practice ID: ' . ($practice ? $practice->id : 'null'));

        // バリデーション...
        try {
            $validated = $request->validate([
                'wpm'        => 'required|integer|min:0',
                'accuracy'   => 'required|numeric|min:0|max:100',
                'clear_time' => 'required|numeric|min:0', 
            ]);
            \Log::info('Validation passed.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed: ' . json_encode($e->errors()));
            throw $e;
        }

        // 保存処理...
        try {
            $data = [
                'user_id'     => Auth::id() ?? 1,
                'practice_id' => $id,
                'wpm'         => $validated['wpm'],
                'accuracy'    => $validated['accuracy'],
                'clear_time'  => $validated['clear_time'], 
            ];
            
            $record = $this->recordModel->create($data);
            \Log::info('Record created successfully: ' . $record->id);

            return response()->json([
                'success' => true,
                'message' => 'Result recorded successfully!',
                'data'    => $record
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Database save failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }
}
