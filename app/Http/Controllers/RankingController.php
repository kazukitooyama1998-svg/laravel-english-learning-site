<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;
use App\Models\Practice;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        // 💡 1. プルダウン用にすべての教材データを取得
        $practices = Practice::all();

        // 💡 2. 選択された教材IDを取得（なければリストの最初の教材ID、それもなければ1をデフォルトに）
        $selectedPracticeId = $request->query('practice_id', $practices->first()->id ?? 1);

        // 💡 3. 選択された教材のランキングだけを最大50件取得して10件ずつペジネーション
        $rankings = Record::with('user')
            ->where('practice_id', $selectedPracticeId) // 教材IDで絞り込み
            ->orderBy('wpm', 'desc')
            ->orderBy('accuracy', 'desc')
            ->take(50)
            ->paginate(10)
            ->withQueryString(); // 📄 ページをめくってもURLの「?practice_id=X」を引き継ぐ設定

        return view('menus.ranking.index', compact('rankings', 'practices', 'selectedPracticeId'));
    }
}
