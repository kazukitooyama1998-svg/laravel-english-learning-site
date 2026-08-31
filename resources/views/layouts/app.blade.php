<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    {{-- scroll-reveal is progressive enhancement: only arm it when JS is available --}}
    <script>document.documentElement.classList.add('js');</script>

    @vite(['resources/css/app.css','resources/js/app.js','resources/js/english/app.js'])

    {{-- Google Fonts — gentle rounded "marumoji" faces (JP + Latin), grown-up weight --}}
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;500;700&family=M+PLUS+Rounded+1c:wght@400;500;700&family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet"/>
    {{-- Material Symbols（Google製のアイコン） --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script id="tailwind-config">
    //// サイト全体の見た目に関する設定 — "Woodland Biscuit" テーマ
    //// Instagram「lazy_english_toeic_jp」の世界観をアプリに反映。
    //// 「1日1分から続ける」低圧・穏やかな学習トーン。生成りの紙地に、
    //// 濃紺のインク、ダスティブルーのアクセント、セージ／プラム／タンの上品な
    //// 補色。見出しには蛍光ペン風のマーカー、英字は小さめのレタースペース見出し。
    //// トークン名は元のまま維持し、全ページに一括反映される。
    const _rounded = ["Zen Maru Gothic", "M PLUS Rounded 1c", "Hiragino Maru Gothic ProN", "system-ui", "sans-serif"];
    const _ui      = ["M PLUS Rounded 1c", "Zen Maru Gothic", "Hiragino Maru Gothic ProN", "system-ui", "sans-serif"];
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {                         //// 色設定（Lazy English — calm study）
            // --- brand / action：ダスティブルー ---
            "primary": "#3d5a80",
            "on-primary": "#ffffff",
            "primary-container": "#dde6f0",
            "on-primary-container": "#182a41",
            "primary-fixed": "#dde6f0",
            "primary-fixed-dim": "#a9c0d6",
            "on-primary-fixed": "#0f1e30",
            "on-primary-fixed-variant": "#2f496b",
            "inverse-primary": "#a9c0d6",
            "surface-tint": "#3d5a80",

            "secondary": "#6f8154",                    // sage / olive
            "on-secondary": "#ffffff",
            "secondary-container": "#e3e8d5",
            "on-secondary-container": "#2c331d",
            "secondary-fixed": "#e3e8d5",
            "secondary-fixed-dim": "#c1caa9",
            "on-secondary-fixed": "#1e2413",
            "on-secondary-fixed-variant": "#556340",

            "tertiary": "#75688f",                     // muted plum
            "on-tertiary": "#ffffff",
            "tertiary-container": "#e6e0ef",
            "on-tertiary-container": "#2c2440",
            "tertiary-fixed": "#e6e0ef",
            "tertiary-fixed-dim": "#c6bcd8",
            "on-tertiary-fixed": "#1f1830",
            "on-tertiary-fixed-variant": "#5b4f73",

            "error": "#b4483a",
            "on-error": "#ffffff",
            "error-container": "#f5ded9",
            "on-error-container": "#4e150d",

            // --- surfaces：生成りの紙 ---
            "background": "#f7f4ec",
            "on-background": "#2e3a4f",
            "surface": "#f7f4ec",
            "on-surface": "#2e3a4f",                   // navy-charcoal ink
            "on-surface-variant": "#5c6675",           // slate
            "surface-variant": "#e6dfcd",
            "surface-dim": "#e0d9c4",
            "surface-bright": "#fffefb",
            "surface-container-lowest": "#ffffff",
            "surface-container-low": "#f2eee2",
            "surface-container": "#ece7d8",
            "surface-container-high": "#e4ddc9",
            "surface-container-highest": "#dcd3bb",

            "outline": "#9a9788",
            "outline-variant": "#dcd6c5",
            "inverse-surface": "#2b3546",
            "inverse-on-surface": "#eef1f5",

            // --- extra decoration tokens (additive) ---
            "navy": "#2e3a4f",
            "slate-blue": "#3d5a80",
            "mist": "#a9c0d6",
            "sky": "#7fa8c9",
            "sage": "#7c8c5f",
            "olive": "#6f8154",
            "plum": "#75688f",
            "tan": "#a1815f",
            "peach": "#d7a488",
            "honey": "#c98f3c",
            "clay": "#b1795a",
            "coral": "#cf8a6d",
            "forest": "#5c7250",
            "matcha": "#7c8c5f",
            "teal": "#4f8481",
            "berry": "#9c6070",
            "grape": "#75688f",
            "cream": "#f2eee2",
            "milk": "#fffefb",
            "biscuit": "#d8cbb0",
            "biscuit-deep": "#b7a888",
            "cocoa": "#4a4234",
            "choco": "#5c6675",
            // TOEIC スコア帯チップ
            "lv600": "#9c7d5b",
            "lv700": "#7fa8c9",
            "lv800": "#3a4a63",
            "lv900": "#7c6f96"
          },
          borderRadius: {           //// 角丸 — 丸みは残しつつ、少し引き締める
            DEFAULT: "0.5rem",
            sm: "0.375rem",
            md: "0.7rem",
            lg: "0.8rem",
            xl: "1.15rem",
            "2xl": "1.5rem",
            "3xl": "2rem",
            blob: "1.5rem",
            full: "9999px"
          },
          boxShadow: {
            "biscuit": "0 1px 2px rgba(46,58,79,.05), 0 12px 24px -16px rgba(46,58,79,.28)",
            "biscuit-lg": "0 2px 4px rgba(46,58,79,.06), 0 24px 40px -22px rgba(46,58,79,.32)"
          },
          spacing: {
            "margin-mobile": "16px",
            "container-max": "1200px",
            "gutter": "24px",
            "base": "4px",
            "margin-desktop": "40px"
          },
          maxWidth: {
            "container-max": "1200px"
          },
          fontFamily: {
            "sans": _ui,
            "headline-md": _rounded,
            "headline-lg": _rounded,
            "headline-lg-mobile": _rounded,
            "display": _rounded,
            "body-md": _ui,
            "body-lg": _ui,
            "caption": _ui,
            "label-md": _rounded
          },
          fontSize: {
            "headline-md": ["23px", { lineHeight: "32px", fontWeight: "700" }],
            "body-md": ["16px", { lineHeight: "26px", fontWeight: "400" }],
            "caption": ["12px", { lineHeight: "17px", fontWeight: "500" }],
            "headline-lg": ["31px", { lineHeight: "40px", letterSpacing: "0em", fontWeight: "700" }],
            "headline-lg-mobile": ["27px", { lineHeight: "35px", fontWeight: "700" }],
            "display": ["44px", { lineHeight: "52px", letterSpacing: "0em", fontWeight: "700" }],
            "label-md": ["14px", { lineHeight: "20px", letterSpacing: "0.015em", fontWeight: "700" }],
            "body-lg": ["18px", { lineHeight: "30px", fontWeight: "400" }]
          },
          keyframes: {
            "pop-in": {
              "0%":   { transform: "scale(.6)", opacity: "0" },
              "70%":  { transform: "scale(1.06)", opacity: "1" },
              "100%": { transform: "scale(1)" }
            }
          },
          animation: {
            "pop-in": "pop-in .5s cubic-bezier(.34,1.56,.64,1) both"
          }
        }
      }
    };
  </script>

  <style>
    /* Base typography + design language live in resources/css/app.css */
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    #custom-drawer {
        right: 0;
        transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    }
    #custom-drawer.translate-x-full {
        transform: translateX(100%);
    }
  </style>
</head>
<body class="bg-surface text-on-surface overflow-x-hidden">

    {{-- ===== Motif sprite: original primitive shapes (no third-party art) ===== --}}
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <symbol id="m-star" viewBox="0 0 64 64">
            <path fill="currentColor" d="M32 3l8.4 17.9L60 23.4 45.6 37.2 49.3 57 32 47.4 14.7 57l3.7-19.8L4 23.4l19.6-2.5z"/>
        </symbol>
        <symbol id="m-heart" viewBox="0 0 64 64">
            <path fill="currentColor" d="M32 56S6 39.6 6 21.6C6 12.4 13.4 5 22.6 5 28 5 33 7.7 32 12c-1-4.3 4-7 9.4-7C50.6 5 58 12.4 58 21.6 58 39.6 32 56 32 56z"/>
        </symbol>
        <symbol id="m-paw" viewBox="0 0 64 64">
            <ellipse cx="32" cy="42" rx="16" ry="13" fill="currentColor"/>
            <circle cx="14" cy="24" r="7" fill="currentColor"/>
            <circle cx="27" cy="15" r="7" fill="currentColor"/>
            <circle cx="42" cy="15" r="7" fill="currentColor"/>
            <circle cx="52" cy="26" r="7" fill="currentColor"/>
        </symbol>
        <symbol id="m-cloud" viewBox="0 0 72 48">
            <path fill="currentColor" d="M20 44a14 14 0 010-28 16 16 0 0130-6 12 12 0 013 24 8 8 0 01-1 .3z"/>
        </symbol>
        <symbol id="m-biscuit" viewBox="0 0 64 64">
            <rect x="8" y="8" width="48" height="48" rx="16" fill="currentColor"/>
            <circle cx="24" cy="26" r="3" fill="#fff" opacity=".55"/>
            <circle cx="40" cy="22" r="2.4" fill="#fff" opacity=".5"/>
            <circle cx="36" cy="42" r="3" fill="#fff" opacity=".55"/>
            <circle cx="22" cy="42" r="2.2" fill="#fff" opacity=".45"/>
        </symbol>
        <symbol id="m-bear" viewBox="0 0 64 64">
            <circle cx="16" cy="16" r="9" fill="currentColor"/>
            <circle cx="48" cy="16" r="9" fill="currentColor"/>
            <circle cx="32" cy="36" r="22" fill="currentColor"/>
            <circle cx="24" cy="33" r="2.6" fill="#4a3627"/>
            <circle cx="40" cy="33" r="2.6" fill="#4a3627"/>
            <path d="M28 42a4 4 0 008 0z" fill="#4a3627"/>
        </symbol>
        <symbol id="m-bunny" viewBox="0 0 64 64">
            <ellipse cx="24" cy="16" rx="6" ry="15" fill="currentColor"/>
            <ellipse cx="40" cy="16" rx="6" ry="15" fill="currentColor"/>
            <circle cx="32" cy="40" r="19" fill="currentColor"/>
            <circle cx="25" cy="38" r="2.4" fill="#4a3627"/>
            <circle cx="39" cy="38" r="2.4" fill="#4a3627"/>
            <circle cx="32" cy="44" r="2.4" fill="#b4483a"/>
        </symbol>
        <symbol id="m-cat" viewBox="0 0 64 64">
            <path d="M12 8l12 12H10z M52 8L40 20h14z" fill="currentColor"/>
            <circle cx="32" cy="36" r="22" fill="currentColor"/>
            <circle cx="24" cy="34" r="2.6" fill="#4a3627"/>
            <circle cx="40" cy="34" r="2.6" fill="#4a3627"/>
            <path d="M30 42a2 2 0 004 0z" fill="#4a3627"/>
        </symbol>
        <symbol id="m-lion" viewBox="0 0 64 64">
            <path fill="currentColor" d="M32 2l6 8 9-4-1 10 10 2-7 8 7 8-10 2 1 10-9-4-6 8-6-8-9 4 1-10-10-2 7-8-7-8 10-2-1-10 9 4z"/>
            <circle cx="32" cy="34" r="17" fill="#ffe895"/>
            <circle cx="26" cy="32" r="2.6" fill="#4a3627"/>
            <circle cx="38" cy="32" r="2.6" fill="#4a3627"/>
            <path d="M28 40a4 4 0 008 0z" fill="#4a3627"/>
        </symbol>
        <symbol id="m-elephant" viewBox="0 0 64 64">
            <circle cx="34" cy="30" r="20" fill="currentColor"/>
            <path d="M20 34c-8 2-12 10-9 18 2 5 8 4 9-1 1-4 2-9 6-11z" fill="currentColor"/>
            <ellipse cx="52" cy="26" rx="8" ry="11" fill="currentColor"/>
            <circle cx="30" cy="27" r="2.6" fill="#4a3627"/>
        </symbol>
        <symbol id="m-penguin" viewBox="0 0 64 64">
            <ellipse cx="32" cy="34" rx="18" ry="24" fill="currentColor"/>
            <ellipse cx="32" cy="38" rx="10" ry="17" fill="#fffdf7"/>
            <circle cx="26" cy="24" r="2.4" fill="#4a3627"/>
            <circle cx="38" cy="24" r="2.4" fill="#4a3627"/>
            <path d="M29 30h6l-3 5z" fill="#ff8a5b"/>
        </symbol>
        <symbol id="m-sparkle" viewBox="0 0 32 32">
            <path fill="currentColor" d="M16 0c1 8 7 14 16 16-9 2-15 8-16 16-1-8-7-14-16-16 9-2 15-8 16-16z"/>
        </symbol>
        <symbol id="m-leaf" viewBox="0 0 64 64">
            <path fill="currentColor" d="M54 8C24 10 12 28 12 46c0 4 1 8 3 11C20 40 34 24 52 18 36 28 24 42 20 58c22 2 38-12 38-34 0-6-1-12-4-16z"/>
        </symbol>
        <symbol id="m-acorn" viewBox="0 0 64 64">
            <path fill="currentColor" d="M32 24c11 0 20 3 20 8 0 12-9 26-20 26S12 44 12 32c0-5 9-8 20-8z"/>
            <rect x="14" y="14" width="36" height="13" rx="6.5" fill="currentColor" opacity=".75"/>
            <rect x="30" y="4" width="4" height="12" rx="2" fill="currentColor" opacity=".75"/>
        </symbol>
        <symbol id="m-mushroom" viewBox="0 0 64 64">
            <path fill="currentColor" d="M32 8C16 8 6 20 6 30c0 4 3 6 8 6h36c5 0 8-2 8-6C58 20 48 8 32 8z"/>
            <rect x="24" y="34" width="16" height="22" rx="8" fill="currentColor" opacity=".7"/>
            <circle cx="22" cy="22" r="4" fill="#fffdf7" opacity=".6"/>
            <circle cx="40" cy="19" r="3" fill="#fffdf7" opacity=".55"/>
        </symbol>
        <symbol id="m-drop" viewBox="0 0 40 40">
            <path fill="currentColor" d="M20 3c7 10 13 16 13 24a13 13 0 01-26 0C7 19 13 13 20 3z"/>
        </symbol>
        <symbol id="m-check" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </symbol>
        <symbol id="m-cross" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
        </symbol>
        <symbol id="m-medal" viewBox="0 0 64 64">
            <path fill="currentColor" d="M22 4h8l-7 20-9-3z" opacity=".7"/>
            <path fill="currentColor" d="M42 4h-8l7 20 9-3z" opacity=".7"/>
            <circle cx="32" cy="40" r="18" fill="currentColor"/>
            <circle cx="32" cy="40" r="11" fill="#fffdf7"/>
            <path fill="currentColor" d="M32 31l2.6 5.4 5.9.8-4.3 4.1 1 5.9-5.2-2.8-5.2 2.8 1-5.9-4.3-4.1 5.9-.8z"/>
        </symbol>
        <symbol id="m-flame" viewBox="0 0 64 64">
            <path fill="currentColor" d="M32 4c4 10-6 14-6 24 0 5 3 8 3 8s-9-2-9-14c0 0-8 8-8 20a20 20 0 0040 0c0-14-9-20-9-28 0 6-4 8-8 10 2-8 4-14 4-20z"/>
        </symbol>
    </svg>

    {{-- ===== Ambient background — a couple of very soft geometric shapes ===== --}}
    <div class="motif-sky" aria-hidden="true">
        <span class="motif motif--bob-slow" style="left:-4%;top:12%;width:180px;height:180px;border-radius:9999px;background:rgba(61,90,128,.05)" data-parallax="0.04"></span>
        <span class="motif motif--bob"      style="right:-3%;top:52%;width:150px;height:150px;border-radius:9999px;background:rgba(124,140,95,.05)" data-parallax="0.07"></span>
        <span class="motif motif--sway"     style="left:6%;bottom:8%;width:110px;height:110px;border:2px solid rgba(117,104,143,.08);border-radius:9999px" data-parallax="0.05"></span>
    </div>

    <div id="app" class="relative z-10 min-h-screen flex flex-col w-full overflow-x-hidden">
        
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-outline-variant/20">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

                <a href="{{ url('/') }}" class="group flex items-center gap-2.5 no-underline text-inherit">
                    <span class="grid place-items-center size-10 rounded-xl bg-primary text-on-primary shadow-biscuit">
                        <span class="material-symbols-outlined text-[22px] hover-hop">auto_stories</span>
                    </span>
                    <span class="font-display text-xl font-bold tracking-tight text-on-surface">Axis <span class="text-primary">English</span></span>
                </a>

                <div class="flex items-center gap-4 ml-auto">
                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-base font-medium text-on-surface-variant hover:text-primary no-underline px-3 py-2 rounded-md transition-colors">
                                Login
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="biscuit-btn pop-burst text-base no-underline">
                                Register
                            </a>
                        @endif
                    @else
                        <div class="flex items-center gap-2 px-4 py-2.5 bg-surface-container-low rounded-xl border border-outline-variant/30">
                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">person</span>
                            <span class="text-base font-medium text-on-surface-variant">
                                {{ Auth::user()->name }}
                            </span>
                        </div>

                        <a href="{{ route('logout') }}" 
                        class="text-base font-medium text-error hover:text-error/80 no-underline px-4 py-2.5 rounded-xl hover:bg-error-container/50 transition-colors"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <button id="menu-trigger-btn" class="group flex flex-col items-center justify-center size-14 bg-primary text-on-primary rounded-full ml-2 shadow-[0_3px_0_#2f496b,0_10px_18px_-10px_rgba(46,58,79,.5)] transition-transform duration-200 hover:-translate-y-0.5 active:translate-y-1 focus:outline-none" type="button" aria-label="Menu">
                            <div class="flex flex-col gap-1.5 justify-center items-center w-6 h-3">
                                <span class="block w-full h-[3px] bg-current rounded-full"></span>
                                <span class="block w-full h-[3px] bg-current rounded-full"></span>
                            </div>
                            <span class="text-[10px] font-bold tracking-wider mt-0.5 uppercase leading-none">Menu</span>
                        </button>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endguest
                </div>

                <div id="custom-drawer" class="fixed top-0 right-0 h-full w-[280px] bg-surface-container-lowest border-l border-outline-variant z-[9999] transform translate-x-full transition-transform duration-300 ease-in-out shadow-2xl">
                    <div class="flex items-center justify-between p-5 border-b border-outline-variant">
                        <h5 class="font-display font-bold text-on-surface text-lg">Menu</h5>
                        <button id="menu-close-btn" class="p-1.5 text-on-surface-variant hover:text-on-surface hover:bg-surface-container rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-4 flex flex-col gap-1">

                        {{-- Admin Controls: 管理者のみ表示 --}}
                        @can('admin')
                            <a href="{{ route('admin.users') }}" class="px-3 py-3 text-error hover:bg-error-container/40 rounded-xl no-underline font-semibold flex items-center gap-3 transition-all">
                                <span class="material-symbols-outlined text-[20px]">manage_accounts</span> Admin Dashboard
                            </a>
                            <hr class="border-outline-variant my-2">
                        @endcan

                        {{-- 通常メニュー --}}
                        <a href="/english" class="px-3 py-3 text-on-surface hover:text-primary hover:bg-primary/[0.06] rounded-xl no-underline font-semibold flex items-center gap-3 transition-all">
                            <span class="material-symbols-outlined text-[20px]">auto_stories</span> English Learning
                        </a>
                        <a href="{{ route('partners.index') }}" class="px-3 py-3 text-on-surface hover:text-primary hover:bg-primary/[0.06] rounded-xl no-underline font-semibold flex items-center gap-3 transition-all">
                            <span class="material-symbols-outlined text-[20px]">group</span> Language Partners
                        </a>
                        @auth
                            <a href="{{ route('profile.show') }}" class="px-3 py-3 text-on-surface hover:text-primary hover:bg-primary/[0.06] rounded-xl no-underline font-semibold flex items-center gap-3 transition-all">
                                <span class="material-symbols-outlined text-[20px]">person</span> Profile
                            </a>
                        @endauth
                    </div>
                </div>
                
                <div id="drawer-overlay" class="fixed inset-0 bg-black/40 z-[9990] hidden transition-opacity"></div>

            </div>
        </nav>

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="relative mt-16 border-t border-outline-variant bg-surface-container-low">
            <div class="relative z-10 max-w-7xl mx-auto px-6 py-12 text-center">
                <span class="inline-grid place-items-center size-11 rounded-xl bg-primary text-on-primary shadow-biscuit mb-3">
                    <span class="material-symbols-outlined text-2xl">auto_stories</span>
                </span>
                <p class="font-display font-bold text-on-surface">Axis <span class="text-primary">English</span></p>
                <p class="text-caption text-on-surface-variant/70 mt-1">© 2026 Axis English — 毎日少しずつ、続ける英語学習。</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const trigger = document.getElementById('menu-trigger-btn');
            const close = document.getElementById('menu-close-btn');
            const drawer = document.getElementById('custom-drawer');
            const overlay = document.getElementById('drawer-overlay');

            if (trigger && drawer) {
                // 開く処理
                trigger.addEventListener('click', function () {
                    drawer.classList.remove('translate-x-full');
                    overlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // ★ここを追加：スクロール固定
                });

                // 閉じる処理
                const closeMenu = function () {
                    drawer.classList.add('translate-x-full');
                    overlay.classList.add('hidden');
                    document.body.style.overflow = ''; // ★ここを追加：スクロール固定解除
                };

                if (close) close.addEventListener('click', closeMenu);
                if (overlay) overlay.addEventListener('click', closeMenu);
            }
        });
    </script>
</body>
</html>