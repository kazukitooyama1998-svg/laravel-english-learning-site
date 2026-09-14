<?php

namespace App\Http\Controllers\English;

use App\Http\Controllers\Controller;
use App\Services\English\XpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

    /**
     * じぶんの家（室内の 3D 空間）
     * GET /english/forest/home
     */
    public function home()
    {
        $user = Auth::user();

        if (! $user->character_key) {
            return redirect()->route('character.select', ['redirect' => 'forest']);
        }

        $levelInfo = $this->xpService->getLevelInfo($user);

        $player = [
            'name'      => $user->name,
            'level'     => $levelInfo['level'],
            'character' => $user->character,
        ];

        // 模様替えに必要なカタログ一式（家具・床・壁）と、保存済みのレイアウト
        $catalog = [
            'furniture' => config('english.furniture'),
            'floors'    => config('english.room_floors'),
            'walls'     => config('english.room_walls'),
            'room'      => config('english.room'),
        ];

        return view('english.forest.home', [
            'user'    => $user,
            'player'  => $player,
            'catalog' => $catalog,
            'layout'  => $user->room,
            'saveUrl' => route('english.forest.home.save'),
            'exitUrl' => route('english.forest'),
        ]);
    }

    /**
     * 模様替えの保存
     * POST /english/forest/home
     */
    public function saveRoom(Request $request)
    {
        $room = config('english.room');

        // 家具は部屋の内側にしか置けない。範囲外の座標は弾く。
        $halfW = $room['width'] / 2;
        $halfD = $room['depth'] / 2;

        $validated = $request->validate([
            'floor'         => ['required', Rule::in(array_keys(config('english.room_floors')))],
            'wall'          => ['required', Rule::in(array_keys(config('english.room_walls')))],
            'items'         => ['present', 'array', 'max:60'],
            'items.*.key'   => ['required', Rule::in(array_keys(config('english.furniture')))],
            'items.*.x'     => ['required', 'numeric', 'between:' . (-$halfW) . ',' . $halfW],
            'items.*.z'     => ['required', 'numeric', 'between:' . (-$halfD) . ',' . $halfD],
            'items.*.rot'   => ['required', 'numeric', 'between:-7,7'],
        ]);

        $user = Auth::user();
        $user->room_layout = [
            'floor' => $validated['floor'],
            'wall'  => $validated['wall'],
            'items' => array_map(fn (array $item) => [
                'key' => $item['key'],
                'x'   => round((float) $item['x'], 2),
                'z'   => round((float) $item['z'], 2),
                'rot' => round((float) $item['rot'], 3),
            ], $validated['items']),
        ];
        $user->save();

        return response()->json(['status' => 'ok']);
    }
}
