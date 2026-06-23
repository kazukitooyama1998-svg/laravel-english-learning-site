<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    // プロフィール表示
    public function show()
    {
        $user = Auth::user();
        // ここで学習履歴を取得（※StudyLogモデルがある場合）
        // $studyLogs = $user->studyLogs()->latest()->take(5)->get();
        $studyLogs = []; // 学習履歴モデルを作成したら置き換えてください

        return view('menus.profile.index', compact('user', 'studyLogs'));
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

        $request->validate([
            'name' => 'required|string|max:255',
            'introduction' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:8|confirmed', // パスワード変更
        ]);

        $user->name = $request->name;
        $user->introduction = $request->introduction;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}