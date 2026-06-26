<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RankingController extends Controller
{
    /**
     * 累計XPランキングを表示
     */
    public function index()
    {
        // 累計XPが高い順にユーザーを50人取得し、10人ずつページネーション
        $rankings = User::orderBy('total_xp', 'desc')
            ->paginate(10);

        return view('menus.ranking.index', compact('rankings'));
    }
}