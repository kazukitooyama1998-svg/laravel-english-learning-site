{{--
    英語の森（3D アイランド）

    ログイン済みユーザーだけが入れる 3D 空間。画面いっぱいを使うため
    layouts.app は使わず、このページ単体で完結させている。
--}}
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#3f9ede">
    <title>{{ config('app.name') }} | 英語の森</title>

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;500;700&family=M+PLUS+Rounded+1c:wght@400;500;700&display=swap" rel="stylesheet"/>

    @vite(['resources/js/english/forest/app.js'])

    <style>
        :root {
            --ink: #2e3a4f;
            --ink-soft: #5c6675;
            --paper: #fffefb;
            --primary: #3d5a80;
            --outline: #dcd6c5;
            --shadow: 0 2px 4px rgba(46, 58, 79, .08), 0 18px 34px -20px rgba(46, 58, 79, .45);
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            height: 100%;
            overflow: hidden;
            overscroll-behavior: none;
            background: #9fd7ef;
            color: var(--ink);
            font-family: "Zen Maru Gothic", "M PLUS Rounded 1c", "Hiragino Maru Gothic ProN", system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        #forest-canvas {
            display: block;
            width: 100vw;
            height: 100dvh;
            touch-action: none;
            cursor: grab;
        }
        #forest-canvas:active { cursor: grabbing; }

        /* ===== HUD 共通 ===== */
        .hud {
            position: fixed;
            z-index: 10;
            pointer-events: none;
        }
        .hud > * { pointer-events: auto; }

        .panel {
            background: rgba(255, 254, 251, .92);
            border: 1px solid var(--outline);
            border-radius: 18px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(6px);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .6rem 1rem;
            border: 1px solid var(--outline);
            border-radius: 14px;
            background: rgba(255, 254, 251, .92);
            color: var(--ink);
            font: inherit;
            font-weight: 700;
            font-size: .9rem;
            text-decoration: none;
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: transform .15s ease, background .15s ease;
        }
        .btn:hover { transform: translateY(-2px); background: #fff; }
        .btn:active { transform: translateY(1px); }
        .btn--primary {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        .btn--primary:hover { background: #33507a; }

        /* ===== 左上：プレイヤーカード ===== */
        .hud-player {
            top: max(16px, env(safe-area-inset-top));
            left: max(16px, env(safe-area-inset-left));
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .7rem .95rem;
        }
        .hud-player img,
        .hud-player .avatar-fallback {
            width: 44px;
            height: 44px;
            border-radius: 999px;
            object-fit: cover;
            display: grid;
            place-items: center;
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .hud-player .meta { line-height: 1.3; }
        .hud-player .place {
            font-size: .7rem;
            letter-spacing: .1em;
            color: var(--ink-soft);
            text-transform: uppercase;
        }
        .hud-player .name { font-weight: 700; font-size: 1rem; }
        .hud-player .lv {
            font-size: .75rem;
            color: var(--ink-soft);
        }

        /* ===== 右上：ボタン ===== */
        .hud-actions {
            top: max(16px, env(safe-area-inset-top));
            right: max(16px, env(safe-area-inset-right));
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        /* ===== 下部中央：施設に入るプロンプト ===== */
        .hud-prompt {
            left: 50%;
            bottom: max(28px, env(safe-area-inset-bottom));
            transform: translate(-50%, 30px);
            opacity: 0;
            visibility: hidden;
            transition: opacity .2s ease, transform .2s ease, visibility .2s;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .75rem .75rem .75rem 1.1rem;
            border-top: 4px solid var(--spot-color, var(--primary));
            max-width: min(92vw, 460px);
        }
        .hud-prompt.is-visible {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, 0);
        }
        .hud-prompt .spot-name { font-weight: 700; font-size: 1.05rem; line-height: 1.3; }
        .hud-prompt .spot-desc { font-size: .8rem; color: var(--ink-soft); }
        .hud-prompt .key {
            display: none;
            font-size: .72rem;
            color: var(--ink-soft);
            margin-top: .15rem;
        }
        @media (hover: hover) and (pointer: fine) {
            .hud-prompt .key { display: block; }
        }

        /* ===== バーチャルスティック（タッチ時のみ表示） ===== */
        #forest-stick {
            position: fixed;
            z-index: 9;
            display: none;
            width: 132px;
            height: 132px;
            margin: -66px 0 0 -66px;
            border-radius: 999px;
            background: rgba(255, 254, 251, .3);
            border: 2px solid rgba(255, 254, 251, .6);
            pointer-events: none;
        }
        #forest-stick .knob {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 58px;
            height: 58px;
            margin: 0;
            border-radius: 999px;
            background: rgba(255, 254, 251, .85);
            box-shadow: var(--shadow);
            transform: translate(-50%, -50%);
        }

        /* ===== 使い方パネル ===== */
        .hud-help {
            right: max(16px, env(safe-area-inset-right));
            top: 76px;
            width: min(90vw, 320px);
            padding: 1.1rem 1.2rem 1.2rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: opacity .2s ease, transform .2s ease, visibility .2s;
            max-height: min(72dvh, 560px);
            overflow-y: auto;
        }
        .hud-help.is-open { opacity: 1; visibility: visible; transform: translateY(0); }
        .hud-help h2 { margin: 0 0 .6rem; font-size: 1rem; }
        .hud-help h3 { margin: 1.1rem 0 .4rem; font-size: .8rem; color: var(--ink-soft); letter-spacing: .04em; }
        .hud-help dl { margin: 0; display: grid; grid-template-columns: auto 1fr; gap: .35rem .7rem; font-size: .85rem; }
        .hud-help dt { font-weight: 700; }
        .hud-help dd { margin: 0; color: var(--ink-soft); }
        .hud-help ul { margin: 0; padding: 0; list-style: none; display: grid; gap: .3rem; }
        .hud-help ul a {
            display: flex;
            justify-content: space-between;
            gap: .5rem;
            padding: .45rem .6rem;
            border-radius: 10px;
            font-size: .85rem;
            color: var(--ink);
            text-decoration: none;
            background: #f2eee2;
        }
        .hud-help ul a:hover { background: #e4ddc9; }
        .hud-help ul span { color: var(--ink-soft); font-size: .75rem; }
        .hud-help .close {
            position: absolute;
            top: .5rem;
            right: .6rem;
            border: 0;
            background: none;
            font-size: 1.2rem;
            line-height: 1;
            color: var(--ink-soft);
            cursor: pointer;
        }

        /* ===== ローディング / フェード ===== */
        .overlay {
            position: fixed;
            inset: 0;
            z-index: 20;
            display: grid;
            place-content: center;
            justify-items: center;
            gap: 1rem;
            text-align: center;
            padding: 2rem;
            background: linear-gradient(180deg, #9fd7ef 0%, #dff2fb 60%, #ecdcb4 100%);
            transition: opacity .45s ease, visibility .45s;
        }
        .overlay.is-hidden { opacity: 0; visibility: hidden; }
        .overlay h1 { margin: 0; font-size: 1.5rem; }
        .overlay p { margin: 0; color: var(--ink-soft); font-size: .9rem; }
        .spinner {
            width: 54px;
            height: 54px;
            border-radius: 999px;
            border: 5px solid rgba(61, 90, 128, .18);
            border-top-color: var(--primary);
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        #forest-fade {
            position: fixed;
            inset: 0;
            z-index: 30;
            display: grid;
            place-content: center;
            gap: .8rem;
            justify-items: center;
            background: rgba(46, 58, 79, .88);
            color: #fff;
            opacity: 0;
            visibility: hidden;
            transition: opacity .4s ease, visibility .4s;
        }
        #forest-fade.is-visible { opacity: 1; visibility: visible; }

        .fallback-list { display: grid; gap: .4rem; padding: 0; margin: 0; list-style: none; }
        .fallback-list a { color: var(--primary); font-weight: 700; }

        @media (max-width: 640px) {
            .hud-player { padding: .5rem .7rem; gap: .5rem; }
            .hud-player img, .hud-player .avatar-fallback { width: 36px; height: 36px; }
            .hud-player .name { font-size: .9rem; }
            .btn { padding: .5rem .75rem; font-size: .8rem; }
        }
    </style>
</head>
<body>

    <canvas id="forest-canvas"></canvas>

    {{-- 左上：ログイン中のユーザー --}}
    <div class="hud hud-player panel">
        @if ($player['avatar'])
            <img src="{{ $player['avatar'] }}" alt="{{ $user->name }}">
        @else
            <span class="avatar-fallback">{{ mb_substr($user->name, 0, 1) }}</span>
        @endif
        <div class="meta">
            <div class="place">英語の森 · English Forest</div>
            <div class="name">{{ $user->name }}</div>
            <div class="lv">Lv.{{ $levelInfo['level'] }} · この島を歩けるのはあなただけ</div>
        </div>
    </div>

    {{-- 右上：操作ボタン --}}
    <div class="hud hud-actions">
        <button type="button" class="btn" id="forest-help-toggle">？ 使い方</button>
        <a class="btn btn--primary" href="{{ route('profile.show') }}">← プロフィールへ</a>
    </div>

    {{-- 使い方 & 施設一覧 --}}
    <div class="hud hud-help panel" id="forest-help">
        <button type="button" class="close" id="forest-help-close" aria-label="閉じる">×</button>
        <h2>島のあるきかた</h2>
        <dl>
            <dt>W A S D / ←↑↓→</dt><dd>歩く</dd>
            <dt>Shift</dt><dd>走る</dd>
            <dt>ドラッグ</dt><dd>視点をまわす</dd>
            <dt>ホイール</dt><dd>ズーム</dd>
            <dt>E / Enter</dt><dd>施設に入る</dd>
        </dl>
        <p style="font-size:.8rem;color:var(--ink-soft);margin:.8rem 0 0">スマホ・タブレットは画面左半分をドラッグでスティック、右半分をドラッグで視点移動です。</p>

        <h3>島の施設</h3>
        <ul>
            @foreach ($spots as $spot)
                <li>
                    <a href="{{ $spot['url'] }}">{{ $spot['name'] }} <span>{{ $spot['desc'] }}</span></a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- 施設に近づいたときのプロンプト --}}
    <div class="hud hud-prompt panel" id="forest-prompt">
        <div>
            <div class="spot-name" id="forest-prompt-name">単語の木</div>
            <div class="spot-desc" id="forest-prompt-desc">英単語を覚える</div>
            <div class="key">E / Enter キーでも入れます</div>
        </div>
        <button type="button" class="btn btn--primary" id="forest-prompt-btn">はいる</button>
    </div>

    {{-- バーチャルスティック --}}
    <div id="forest-stick"><div class="knob"></div></div>

    {{-- 読み込み中 --}}
    <div class="overlay" id="forest-loading">
        <div class="spinner"></div>
        <h1>英語の森へ渡っています…</h1>
        <p>{{ $user->name }}さん、ようこそ。まもなく島に到着します。</p>
    </div>

    {{-- WebGL が使えない場合 --}}
    <div class="overlay is-hidden" id="forest-unsupported">
        <h1>この端末では島を表示できません</h1>
        <p>お使いのブラウザが 3D 表示（WebGL）に対応していないようです。<br>下のリンクから各学習メニューへ進めます。</p>
        <ul class="fallback-list">
            @foreach ($spots as $spot)
                <li><a href="{{ $spot['url'] }}">{{ $spot['name'] }}（{{ $spot['desc'] }}）</a></li>
            @endforeach
            <li><a href="{{ route('profile.show') }}">プロフィールへ戻る</a></li>
        </ul>
    </div>

    {{-- 施設へ移動するときの暗転 --}}
    <div id="forest-fade">
        <div class="spinner"></div>
        <p id="forest-fade-text">移動中…</p>
    </div>

    <script>
        // 3D 側へ渡す設定（施設の位置・遷移先と、プレイヤー情報）
        window.__FOREST_CONFIG__ = {
            spots: @json($spots),
            player: @json($player),
        };
    </script>
</body>
</html>
