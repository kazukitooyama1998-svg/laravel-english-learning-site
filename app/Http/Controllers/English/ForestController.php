<?php

namespace App\Http\Controllers\English;

use App\Http\Controllers\Controller;
use App\Services\English\XpService;
use Illuminate\Support\Facades\Auth;

/**
 * 英語の森（3D アイランド）
 *
 * ログイン済みユーザーだけが島に入り、キャラクターを歩かせて
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
        $user      = Auth::user();
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

        // 3D 側へ渡す最小限のプレイヤー情報
        $player = [
            'name'   => $user->name,
            'avatar' => $user->avatar,
            'level'  => $levelInfo['level'],
            // 名前から服の色を決める（同じユーザーは常に同じ色になる）
            'color'  => $this->colorFor($user->name),
        ];

        return view('english.forest.index', compact('user', 'spots', 'player', 'levelInfo'));
    }

    /**
     * ユーザー名から服の色を決定する（サイトのパレットから選択）。
     */
    private function colorFor(string $name): string
    {
        $palette = ['#3d5a80', '#6f8154', '#75688f', '#b4483a', '#c98f3c', '#4f8481', '#9c6070', '#a1815f'];

        return $palette[crc32($name) % count($palette)];
    }
}
