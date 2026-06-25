<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    {{-- Material Symbols（Google製のアイコン） --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {                              //// サイト全体の見た目に関する設定
        extend: {                           //// デフォルトのデザインを「消さずに、新しく追加（拡張）」 
          colors: {                         //// 色設定
            "inverse-surface": "#3d2d27",
            "tertiary-container": "#0078c7",
            "tertiary-fixed": "#d1e4ff",
            "on-tertiary-fixed-variant": "#00497c",
            "surface-container-lowest": "#ffffff",
            "outline": "#8e7166",
            "on-primary-fixed-variant": "#7f2b00",
            "on-tertiary-container": "#fdfcff",
            "tertiary": "#005f9f",
            "primary-container": "#cc4900",
            "on-surface-variant": "#5a4138",
            "on-error": "#ffffff",
            "secondary-fixed-dim": "#d9c2b8",
            "on-surface": "#261813",
            "outline-variant": "#e2bfb3",
            "on-primary-fixed": "#370e00",
            "on-error-container": "#93000a",
            "on-primary": "#ffffff",
            "secondary": "#6c5b53",
            "on-primary-container": "#fffbff",
            "error-container": "#ffdad6",
            "surface-container-high": "#fde3d9",
            "secondary-fixed": "#f6ded3",
            "primary-fixed": "#ffdbce",
            "on-secondary-fixed": "#251913",
            "surface": "#fff8f6",
            "primary-fixed-dim": "#ffb599",
            "surface-dim": "#efd5cc",
            "inverse-on-surface": "#ffede7",
            "on-secondary": "#ffffff",
            "on-background": "#261813",
            "surface-variant": "#f8ddd4",
            "surface-container": "#ffe9e2",
            "primary": "#a33900", 
            "on-secondary-fixed-variant": "#53433c",
            "tertiary-fixed-dim": "#9ecaff",
            "on-tertiary-fixed": "#001d36",
            "surface-bright": "#fff8f6",
            "error": "#ba1a1a",
            "inverse-primary": "#ffb599",
            "on-secondary-container": "#726159",
            "background": "#fff8f6",
            "on-tertiary": "#ffffff",
            "secondary-container": "#f6ded3",
            "surface-tint": "#a73a00",
            "surface-container-low": "#fff1ec",
            "surface-container-highest": "#f8ddd4"
          },
          borderRadius: {           //// ボタンやカードのRadius設定
            DEFAULT: "0.125rem",
            lg: "0.25rem",
            xl: "0.5rem",
            full: "0.75rem"
          },
          spacing: {                //// スペース設定
            "margin-mobile": "16px",
            "container-max": "1280px",
            "gutter": "24px",
            "base": "4px",
            "margin-desktop": "40px"
          },
          fontFamily: {             //// fontFamily設定
            "headline-md": ["Public Sans"],
            "body-md": ["Public Sans"],
            "caption": ["Public Sans"],
            "headline-lg": ["Public Sans"],
            "headline-lg-mobile": ["Public Sans"],
            "display": ["Public Sans"],
            "label-md": ["Public Sans"],
            "body-lg": ["Public Sans"]
          },
          fontSize: {               //// fontSize設定
            "headline-md": ["24px", { lineHeight: "32px", fontWeight: "600" }],
            "body-md": ["16px", { lineHeight: "24px", fontWeight: "400" }],
            "caption": ["12px", { lineHeight: "16px", fontWeight: "400" }],
            "headline-lg": ["32px", { lineHeight: "40px", letterSpacing: "-0.01em", fontWeight: "700" }],
            "headline-lg-mobile": ["28px", { lineHeight: "36px", fontWeight: "700" }],
            "display": ["48px", { lineHeight: "56px", letterSpacing: "-0.02em", fontWeight: "700" }],
            "label-md": ["14px", { lineHeight: "20px", letterSpacing: "0.01em", fontWeight: "600" }],
            "body-lg": ["18px", { lineHeight: "28px", fontWeight: "400" }]
          }
        }
      }
    };
  </script>

  <style>
    body {
      font-family: 'Public Sans', sans-serif;
    }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    #custom-drawer {
        right: 0;
        transition: transform 0.3s ease-in-out;
    }
    #custom-drawer.translate-x-full {
        transform: translateX(100%);
    }
  </style>
</head>
<body class="bg-surface text-on-surface overflow-x-hidden">
    <div id="app" class="min-h-screen flex flex-col w-full overflow-x-hidden">
        
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-outline-variant/20">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

                <a href="{{ url('/') }}" class="flex items-center gap-2 no-underline text-inherit">
                    <div class="size-8 text-[#a33900]">
                        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078L44 7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094L4 42.4379Z" fill="currentColor" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-[#261813]">FocusType</span>
                </a>

                <div class="flex items-center gap-4 ml-auto">
                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-base font-medium text-on-surface-variant hover:text-primary no-underline px-3 py-2 rounded-md transition-colors">
                                Login
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-base font-medium text-on-primary bg-primary hover:opacity-90 no-underline px-4 py-2 rounded-xl shadow-sm transition-colors">
                                Register
                            </a>
                        @endif
                    @else
                        <div class="flex items-center gap-2 px-4 py-2.5 bg-surface-container-low rounded-xl border border-outline-variant/30">
                            <span class="text-sm">👤</span>
                            <span class="text-base font-medium text-on-surface-variant">
                                {{ Auth::user()->name }}
                            </span>
                        </div>

                        <a href="{{ route('logout') }}" 
                        class="text-base font-medium text-error hover:text-error/80 no-underline px-4 py-2.5 rounded-xl hover:bg-error-container/50 transition-colors"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <button id="menu-trigger-btn" class="flex flex-col items-center justify-center size-14 bg-primary hover:opacity-90 text-on-primary rounded-full shadow-md transition-all focus:outline-none ml-2" type="button">
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

                <div id="custom-drawer" class="fixed top-0 right-0 h-full w-[280px] border-l border-outline-variant/30 z-[9999] transform translate-x-full transition-transform duration-300 ease-in-out shadow-2xl" style="background: #fff1ec !important;">
                    <div class="flex items-center justify-between p-5 border-b border-outline-variant/20">
                        <h5 class="font-bold text-on-surface text-lg">Menu</h5>
                        <button id="menu-close-btn" class="p-1 text-on-surface hover:bg-surface-container rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="p-4 flex flex-col gap-2" style="background: #fff1ec !important;">
                        
                        {{-- Admin Controls: 管理者のみ表示 --}}
                        @can('admin')
                            <a href="{{ route('admin.users') }}" class="px-3 py-3 text-error hover:bg-error-container/20 rounded-xl no-underline font-semibold flex items-center transition-all">
                                <i class="fa-solid fa-user-gear w-6"></i> Admin Dashboard
                            </a>
                            <hr class="border-outline-variant/30 my-2">
                        @endcan

                        {{-- 通常メニュー --}}
                        <a href="{{ route('ranking.index') }}" class="px-3 py-3 text-on-surface hover:text-primary hover:bg-surface-container rounded-xl no-underline font-semibold flex items-center transition-all">
                            <i class="fa-solid fa-trophy w-6"></i> Ranking
                        </a>
                        <a href="{{ route('partners.index') }}" class="px-3 py-3 text-on-surface hover:text-primary hover:bg-surface-container rounded-xl no-underline font-semibold flex items-center transition-all">
                            <i class="fa-solid fa-user-group w-6"></i> Language Partners
                        </a>
                        @auth
                            <a href="{{ route('profile.show', Auth::user()->id) }}" class="px-3 py-3 text-on-surface hover:text-primary hover:bg-surface-container rounded-xl no-underline font-semibold flex items-center transition-all">
                                <i class="fa-solid fa-user w-6"></i> Profile
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
        
        <footer class="py-10 border-t border-outline-variant/20 bg-surface-container-low">
            <div class="max-w-7xl mx-auto px-6 text-center text-on-surface-variant/60 text-sm">
            © 2026 FocusType English Learning Systems
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