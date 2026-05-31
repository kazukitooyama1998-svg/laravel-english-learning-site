<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // もしすでにログインしている場合は、ログイン後の画面（ダッシュボード）にリダイレクトさせる
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // ログインしていない場合は、ウェルカムページを表示
        return view('index'); // ※ログイン前のホーム画面のファイル名に合わせてください
    }
}
