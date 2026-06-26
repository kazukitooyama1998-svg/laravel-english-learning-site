<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;
use App\Models\Practice;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    protected $recordModel;

    public function __construct(Record $recordModel)
    {
        $this->recordModel = $recordModel;
    }

    public function store(Request $request, $id)
    {
        $practice = Practice::findOrFail($id);
        
        $validated = $request->validate([
            'wpm'        => 'required|integer|min:0',
            'accuracy'   => 'required|numeric|min:0|max:100',
            'clear_time' => 'required|numeric|min:0', 
        ]);

        $data = [
            'user_id'     => Auth::id() ?? 1,
            'practice_id' => $id,
            'wpm'         => $validated['wpm'],
            'accuracy'    => $validated['accuracy'],
            'clear_time'  => $validated['clear_time'], 
        ];
        
        $record = $this->recordModel->create($data);

        // XP加算とレベル情報取得
        $user = Auth::user();
        if ($user) {
            $user->increment('total_xp', $practice->xp);
        }

        return response()->json([
            'success' => true,
            'gained_xp' => $practice->xp,
            'total_xp'  => $user->fresh()->total_xp,
            'level'     => $user->level,
            'next_level_xp' => $user->next_level_xp
        ], 201);
    }
}