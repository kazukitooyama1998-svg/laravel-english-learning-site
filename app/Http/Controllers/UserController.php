<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserController extends Controller
{
    // プロフィール表示
    public function show()
    {
        $user = Auth::user();

        return view('menus.profile.index', compact('user'));
    }

    // プロフィール編集画面
    public function edit()
    {
        return view('menus.profile.edit', ['user' => Auth::user()]);
    }

    // プロフィール更新処理
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. 基本情報のバリデーション
        // 💡 プロフィール画像のアップロード機能は廃止。アイコンは選択済みキャラクター（character_key）に従う。
        $request->validate([
            'name'         => 'required|min:1|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'introduction' => 'nullable|string|max:100',
        ]);

        // 2. データ更新
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->introduction = $request->introduction;

        // 3. パスワード変更処理
        if ($request->filled('current_password') || $request->filled('new_password')) {
            $request->validate([
                'current_password' => ['required'],
                'new_password'     => ['required', 'confirmed', Password::defaults()],
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => '現在のパスワードが正しくありません。']);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'プロフィールを更新しました。');
    }
}