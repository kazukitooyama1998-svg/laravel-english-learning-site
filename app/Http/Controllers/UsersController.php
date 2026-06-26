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
        // User::query() でベースを作成
        $query = User::query();

        // name="keyword" と合わせたキーで取得
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%");
        }

        // paginate と withQueryString をセットで使うことで、
        // 次ページに移動しても検索状態が維持されます
        $all_users = $query->paginate(20)->withQueryString();
        
        // 変数名 $all_users で渡す
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
