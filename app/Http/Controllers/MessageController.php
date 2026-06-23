<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * チャット画面の表示
     */
    public function show($room_id)
    {
        $user = Auth::user();
        
        // 1. 部屋の存在チェックと自分の参加チェック
        $room = ChatRoom::where('id', $room_id)
            ->where(function($query) use ($user) {
                $query->where('user_id_1', $user->id)
                      ->orWhere('user_id_2', $user->id);
            })->firstOrFail();

        // 2. チャット相手を特定
        $chatPartnerId = ($room->user_id_1 === $user->id) ? $room->user_id_2 : $room->user_id_1;
        $chatPartner = User::findOrFail($chatPartnerId);

        // 💡 修正: friends() ではなく isMutualFollow() を使用
        if (!$user->isMutualFollow($chatPartnerId)) {
            abort(403, '相互フォロー状態ではないため、チャットできません。');
        }

        // 4. メッセージ取得
        $messages = Message::where('chat_room_id', $room->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // ビューのパスも新しい設計に合わせて確認してください
        return view('menus.partners.chat', compact('room', 'chatPartner', 'messages'));
    }

    /**
     * メッセージの送信
     */
    public function store(Request $request, $room_id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // 1. 部屋の取得と参加チェック
        $room = ChatRoom::where('id', $room_id)
            ->where(function($query) use ($user) {
                $query->where('user_id_1', $user->id)
                      ->orWhere('user_id_2', $user->id);
            })->firstOrFail();

        // 2. 💡 修正: 相手のIDを特定して相互フォローをチェック
        $chatPartnerId = ($room->user_id_1 === $user->id) ? $room->user_id_2 : $room->user_id_1;
        
        if (!$user->isMutualFollow($chatPartnerId)) {
            abort(403, '相互フォローが解除されたため、メッセージを送信できません。');
        }

        // 3. メッセージを保存
        Message::create([
            'chat_room_id' => $room->id,
            'sender_id'    => $user->id,
            'body'         => $request->message,
        ]);

        return redirect()->route('chat.show', $room->id);
    }
}