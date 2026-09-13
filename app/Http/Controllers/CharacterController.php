<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * キャラクター選択（アカウント作成後の初期設定 & 変更）
 *
 * 8 匹の中から 1 匹を選び、プロフィールのアイコンと
 * 「英語の森」を歩くアバターの見た目に反映する。
 */
class CharacterController extends Controller
{
    /**
     * 選択元に応じて選択後の遷移先を切り替えるための許可リスト。
     * オープンリダイレクト対策として、URL ではなくキーで受け取る。
     */
    private const REDIRECT_ROUTES = [
        'forest'  => 'english.forest',
        'profile' => 'profile.show',
        'hub'     => 'english.hub',
    ];

    /**
     * キャラクター選択画面
     * GET /character/select
     */
    public function select(Request $request)
    {
        $user       = Auth::user();
        $characters = config('english.characters');
        $redirect   = $request->query('redirect');
        $redirect   = array_key_exists($redirect, self::REDIRECT_ROUTES) ? $redirect : null;

        return view('character.select', compact('user', 'characters', 'redirect'));
    }

    /**
     * キャラクター確定処理
     * POST /character/select
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'character_key' => ['required', Rule::in(array_keys(config('english.characters')))],
            'redirect'      => ['nullable', Rule::in(array_keys(self::REDIRECT_ROUTES))],
        ]);

        $user = Auth::user();
        $user->character_key = $validated['character_key'];
        $user->save();

        $routeName = self::REDIRECT_ROUTES[$validated['redirect'] ?? ''] ?? 'english.hub';

        return redirect()->route($routeName)->with('status', 'キャラクターを設定しました。');
    }
}
