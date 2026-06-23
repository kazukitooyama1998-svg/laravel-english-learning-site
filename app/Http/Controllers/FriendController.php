<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * フレンド一覧 ＆ ユーザー検索画面
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 💡 1. 自分のフレンド一覧を取得
        $friends = $user->friends()->paginate(10);

        // 💡 2. フレンド追加できる「他のユーザー」を検索、またはおすすめ表示
        $search = $request->query('search');
        
        // 自分自身と、すでにフレンドになっている人のIDを除外リストにする
        $excludeIds = $user->friends()->pluck('users.id')->toArray();
        $excludeIds[] = $user->id;

        $query = User::whereNotIn('id', $excludeIds);

        // 検索ワードがあれば名前で絞り込み、なければ5件だけおすすめ表示
        if (!empty($search)) {
            $suggestedUsers = $query->where('name', 'LIKE', "%{$search}%")->get();
        } else {
            $suggestedUsers = $query->take(5)->get();
        }

        return view('menus.friends.index', compact('friends', 'suggestedUsers', 'search'));
    }

    /**
     * フレンド登録
     */
    public function add($friendId)
    {
        $user = Auth::user();
        
        if (!$user->friends()->where('friend_id', $friendId)->exists()) {
            $user->friends()->attach($friendId);
            
            $friend = User::findOrFail($friendId);
            $friend->friends()->attach($user->id);
        }
        return redirect()->route('friends.index')->with('success', 'Friend added successfully!');
    }

    /**
     * フレンド解除
     */
    public function remove($friendId)
    {
        $user = Auth::user();

        // 1. 自分から相手への繋がりを削除
        $user->friends()->detach($friendId);
        
        // 2. 相手から自分への繋がりも同時に削除
        $friend = User::findOrFail($friendId);
        $friend->friends()->detach($user->id);

        return redirect()->route('friends.index')->with('success', 'Friend removed.');
    }
}
