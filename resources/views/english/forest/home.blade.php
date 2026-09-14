{{--
    じぶんの家（室内の 3D 空間）

    島と同じく全画面で使うため layouts.app は使わない。
    歩行モードと模様替えモードを 1 画面で切り替える。
--}}
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f2e8d5">
    <title>{{ config('app.name') }} | じぶんの家</title>

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;500;700&family=M+PLUS+Rounded+1c:wght@400;500;700&display=swap" rel="stylesheet"/>

    @vite(['resources/js/english/home/app.js'])

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
            background: #dfe7ee;
            color: var(--ink);
            font-family: "Zen Maru Gothic", "M PLUS Rounded 1c", "Hiragino Maru Gothic ProN", system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        #home-canvas {
            display: block;
            width: 100vw;
            height: 100dvh;
            touch-action: none;
            cursor: grab;
        }
        #home-canvas:active { cursor: grabbing; }
        body.is-editing #home-canvas { cursor: pointer; }

        .hud { position: fixed; z-index: 10; pointer-events: none; }
        .hud > * { pointer-events: auto; }

        .panel {
            background: rgba(255, 254, 251, .94);
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
            background: rgba(255, 254, 251, .94);
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
        .btn:disabled { opacity: .5; pointer-events: none; }
        .btn--primary { background: var(--primary); border-color: var(--primary); color: #fff; }
        .btn--primary:hover { background: #33507a; }
        .btn--danger { color: #b4483a; border-color: #e4c3bd; }

        /* ===== 左上：部屋の主 ===== */
        .hud-owner {
            top: max(16px, env(safe-area-inset-top));
            left: max(16px, env(safe-area-inset-left));
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .7rem .95rem;
        }
        .hud-owner .place { font-size: .7rem; letter-spacing: .1em; color: var(--ink-soft); text-transform: uppercase; }
        .hud-owner .name { font-weight: 700; font-size: 1rem; }
        .hud-owner .hint { font-size: .75rem; color: var(--ink-soft); }

        /* ===== 右上：操作ボタン ===== */
        .hud-actions {
            top: max(16px, env(safe-area-inset-top));
            right: max(16px, env(safe-area-inset-right));
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        /* ===== 模様替えパネル ===== */
        .hud-edit {
            right: max(16px, env(safe-area-inset-right));
            top: 76px;
            width: min(92vw, 330px);
            max-height: min(74dvh, 640px);
            overflow-y: auto;
            padding: 1rem 1.1rem 1.2rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: opacity .2s ease, transform .2s ease, visibility .2s;
        }
        .hud-edit.is-open { opacity: 1; visibility: visible; transform: translateY(0); }
        .hud-edit h2 { margin: 0 0 .2rem; font-size: 1rem; }
        .hud-edit .lead { margin: 0 0 .9rem; font-size: .78rem; color: var(--ink-soft); line-height: 1.5; }
        .hud-edit h3 { margin: 1.1rem 0 .5rem; font-size: .8rem; color: var(--ink-soft); letter-spacing: .04em; }

        .chip-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .4rem; }
        .chip {
            padding: .5rem .35rem;
            border: 1px solid var(--outline);
            border-radius: 12px;
            background: #f7f3e9;
            font: inherit;
            font-size: .75rem;
            font-weight: 700;
            color: var(--ink);
            cursor: pointer;
            transition: background .15s ease, border-color .15s ease;
        }
        .chip:hover { background: #fff; border-color: var(--primary); }

        .swatches { display: flex; flex-wrap: wrap; gap: .45rem; }
        .swatch {
            width: 46px;
            border: 2px solid transparent;
            border-radius: 12px;
            background: none;
            padding: 0;
            cursor: pointer;
            font: inherit;
        }
        .swatch span.dot {
            display: block;
            height: 30px;
            border-radius: 10px;
            border: 1px solid rgba(46,58,79,.15);
        }
        .swatch span.label { display: block; font-size: .6rem; color: var(--ink-soft); margin-top: .15rem; }
        .swatch.is-active { border-color: var(--primary); }

        /* ===== 選択中の家具 ===== */
        .hud-selection {
            left: 50%;
            bottom: max(24px, env(safe-area-inset-bottom));
            transform: translate(-50%, 24px);
            opacity: 0;
            visibility: hidden;
            transition: opacity .2s ease, transform .2s ease, visibility .2s;
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .6rem .7rem .6rem 1.1rem;
        }
        .hud-selection.is-visible { opacity: 1; visibility: visible; transform: translate(-50%, 0); }
        .hud-selection .name { font-weight: 700; font-size: .95rem; }

        #home-save-state { font-size: .75rem; color: var(--ink-soft); align-self: center; }
        #home-save-state.is-dirty { color: #b4483a; font-weight: 700; }

        /* ===== バーチャルスティック ===== */
        #home-stick {
            position: fixed;
            z-index: 9;
            display: none;
            width: 132px;
            height: 132px;
            margin: -66px 0 0 -66px;
            border-radius: 999px;
            background: rgba(255, 254, 251, .35);
            border: 2px solid rgba(255, 254, 251, .7);
            pointer-events: none;
        }
        #home-stick .knob {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 58px;
            height: 58px;
            border-radius: 999px;
            background: rgba(255, 254, 251, .9);
            box-shadow: var(--shadow);
            transform: translate(-50%, -50%);
        }
        body.is-editing #home-stick { display: none !important; }

        /* ===== オーバーレイ ===== */
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
            background: linear-gradient(180deg, #f2e8d5 0%, #e4d9c4 100%);
            transition: opacity .45s ease, visibility .45s;
        }
        .overlay.is-hidden { opacity: 0; visibility: hidden; }
        .overlay h1 { margin: 0; font-size: 1.4rem; }
        .overlay p { margin: 0; color: var(--ink-soft); font-size: .9rem; }
        .spinner {
            width: 54px; height: 54px; border-radius: 999px;
            border: 5px solid rgba(61, 90, 128, .18);
            border-top-color: var(--primary);
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        @media (max-width: 640px) {
            .hud-owner { padding: .5rem .7rem; }
            .hud-owner .name { font-size: .9rem; }
            .btn { padding: .5rem .75rem; font-size: .8rem; }
            .hud-edit { top: 72px; }
        }
    </style>
</head>
<body>

    <canvas id="home-canvas"></canvas>

    {{-- 左上：部屋の主 --}}
    <div class="hud hud-owner panel">
        <x-character.badge :character="$player['character']" size="sm" />
        <div>
            <div class="place">じぶんの家 · MY ROOM</div>
            <div class="name">{{ $user->name }}</div>
            <div class="hint">WASD で歩く・模様替えで家具を動かす</div>
        </div>
    </div>

    {{-- 右上：操作 --}}
    <div class="hud hud-actions">
        <span id="home-save-state"></span>
        <button type="button" class="btn" id="home-edit-toggle">🛋 模様替え</button>
        <button type="button" class="btn btn--primary" id="home-save">模様替えを保存</button>
        <a class="btn" href="{{ $exitUrl }}">← 島へもどる</a>
    </div>

    {{-- 模様替えパネル --}}
    <div class="hud hud-edit panel" id="home-edit-panel">
        <h2>模様替え</h2>
        <p class="lead">
            家具をドラッグして動かせます。選ぶと下のボタンで回転・削除ができます（R キーで回転、Delete で削除）。
        </p>

        <h3>家具をおく</h3>
        <div class="chip-grid">
            @foreach ($catalog['furniture'] as $key => $item)
                <button type="button" class="chip" data-add-furniture="{{ $key }}">{{ $item['name'] }}</button>
            @endforeach
        </div>

        <h3>ゆか</h3>
        <div class="swatches">
            @foreach ($catalog['floors'] as $key => $floor)
                <button type="button" class="swatch" data-floor="{{ $key }}" title="{{ $floor['name'] }}">
                    <span class="dot" style="background: {{ $floor['color'] }}"></span>
                    <span class="label">{{ $floor['name'] }}</span>
                </button>
            @endforeach
        </div>

        <h3>かべ</h3>
        <div class="swatches">
            @foreach ($catalog['walls'] as $key => $wall)
                <button type="button" class="swatch" data-wall="{{ $key }}" title="{{ $wall['name'] }}">
                    <span class="dot" style="background: {{ $wall['color'] }}"></span>
                    <span class="label">{{ $wall['name'] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- 選択中の家具 --}}
    <div class="hud hud-selection panel" id="home-selection">
        <div class="name" id="home-selected-name">家具</div>
        <button type="button" class="btn" id="home-rotate">⟳ 回す</button>
        <button type="button" class="btn btn--danger" id="home-remove">しまう</button>
    </div>

    {{-- バーチャルスティック --}}
    <div id="home-stick"><div class="knob"></div></div>

    {{-- 読み込み中 --}}
    <div class="overlay" id="home-loading">
        <div class="spinner"></div>
        <h1>じぶんの家にはいっています…</h1>
        <p>{{ $user->name }}さんの部屋をととのえています。</p>
    </div>

    {{-- WebGL 非対応 --}}
    <div class="overlay is-hidden" id="home-unsupported">
        <h1>この端末では部屋を表示できません</h1>
        <p>お使いのブラウザが 3D 表示（WebGL）に対応していないようです。</p>
        <a class="btn btn--primary" href="{{ $exitUrl }}">島へもどる</a>
    </div>

    <script>
        // 3D 側へ渡す設定（家具カタログ・保存済みレイアウト・保存先）
        window.__HOME_CONFIG__ = {
            player: @json($player),
            catalog: @json($catalog),
            layout: @json($layout),
            saveUrl: @json($saveUrl),
        };
    </script>
</body>
</html>
