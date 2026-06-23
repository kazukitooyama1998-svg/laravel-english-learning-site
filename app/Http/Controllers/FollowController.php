<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FollowController extends Controller
{
    public function follow($id) {
        Auth::user()->followings()->attach($id);
        return back();
    }

    public function unfollow($id) {
        Auth::user()->followings()->detach($id);
        return back();
    }

    public function index(Request $request) {
    $search = $request->input('search');
    $query = User::where('id', '!=', Auth::id());

    if ($search) {
        $query->where('name', 'like', "%{$search}%");
    }

    $followings = Auth::user()->followings()->paginate(10);
    $suggestedUsers = $query->whereNotIn('id', Auth::user()->followings->pluck('id'))->get();

    return view('menus.partners.index', compact('followings', 'suggestedUsers', 'search'));
    }

    public function startChat($targetId) {
        if (!Auth::user()->isMutualFollow($targetId)) {
            return back()->with('error', '相互フォローしている相手としかチャットできません');
        }

        $userId1 = min(Auth::id(), $targetId);
        $userId2 = max(Auth::id(), $targetId);

        $room = \App\Models\ChatRoom::firstOrCreate([
            'user_id_1' => $userId1,
            'user_id_2' => $userId2,
        ]);

        return redirect()->route('chat.show', $room->id);
    }
}
