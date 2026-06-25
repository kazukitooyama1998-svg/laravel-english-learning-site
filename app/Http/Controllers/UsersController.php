<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * 管理者用：ユーザー一覧を表示
     */
    public function index()
    {
        // ユーザーを全件取得（ページネーション付き）
        $all_users = User::withTrashed()->paginate(20);
        
        return view('admin.users', compact('all_users'));
    }

    /**
     * 管理者用：ユーザー検索
     */
    public function search(Request $request)
    {
        $query = User::query();

        // 検索キーワードがあれば絞り込み
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
        }

        $users = $query->paginate(20);
        
        return view('admin.users', compact('all_users'));
    }

    public function activate($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return back()->with('success', 'User activated successfully.');
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User deactivated successfully.');
    }
}
