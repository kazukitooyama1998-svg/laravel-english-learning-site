<?php

namespace App\Http\Controllers\English;

use App\Http\Controllers\Controller;
use App\Services\English\XpService;
use Illuminate\Support\Facades\Auth;

/**
 * 英語の森（3D アイランド）
 *
 * ログイン済みユーザーだけが島に入り、選択したキャラクターを歩かせて
 * 各学習機能（施設）へ移動できる 3D 空間を提供する。
 */
class ForestController extends Controller
{
    public function __construct(
        private readonly XpService $xpService,
    ) {}

    /**
     * 島の入り口
     * GET /english/forest
     */
    public function index()
    {
        $user = Auth::user();

        // キャラクター未選択（登録直後にスキップした等）ならまず選んでもらう
        if (! $user->character_key) {
            return redirect()->route('character.select', ['redirect' => 'forest']);
        }

        $levelInfo = $this->xpService->getLevelInfo($user);

        // config の施設定義に、遷移先 URL を解決して付与する
        $spots = collect(config('english.forest_spots', []))
            ->map(fn (array $spot) => [
                'key'   => $spot['key'],
                'kind'  => $spot['kind'],
                'name'  => $spot['name'],
                'desc'  => $spot['desc'],
                'color' => $spot['color'],
                'x'     => (float) $spot['x'],
                'z'     => (float) $spot['z'],
                'rot'   => (float) $spot['rot'],
                'url'   => route($spot['route']),
            ])
            ->values()
            ->all();

        // 3D 側へ渡す最小限のプレイヤー情報（見た目は選択済みキャラクターに従う）
        $player = [
            'name'      => $user->name,
            'level'     => $levelInfo['level'],
            'character' => $user->character,
        ];

        return view('english.forest.index', compact('user', 'spots', 'player', 'levelInfo'));
    }
}
