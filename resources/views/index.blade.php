@extends('layouts.app')

@section('title', 'Axis English - 毎日少しずつ、続ける英語学習。')

@section('content')
<div class="bg-surface text-on-surface">

    {{-- ═══════════ ヒーロー ═══════════ --}}
    <header class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28">
        <span class="pointer-events-none absolute -top-28 -left-28 size-80 rounded-full bg-primary/[0.05]"></span>
        <span class="pointer-events-none absolute top-40 -right-20 size-96 rounded-full bg-secondary/[0.05]"></span>

        <div class="relative max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
            <div class="grid lg:grid-cols-[1.05fr_0.95fr] gap-12 lg:gap-16 items-center">

                <div class="flex flex-col gap-6 max-w-xl">
                    <span class="kicker">Study English, a little every day</span>
                    <h1 class="font-display text-headline-lg md:text-display font-bold text-on-surface leading-[1.3]">
                        <span class="hl">毎日少しずつ</span>、<wbr>続ける英語学習。
                    </h1>
                    <p class="text-body-lg text-on-surface-variant leading-relaxed">
                        タイピングで英語を「体で覚える」。TOEIC・IELTS対策から日常のコミュニケーションまで。短い時間でも毎日続けることが、着実な力になります。
                    </p>
                    <div class="flex flex-wrap items-center gap-3 mt-2">
                        <a href="{{ route('register') }}" class="biscuit-btn pop-burst text-label-md">
                            無料ではじめる
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                        <a href="{{ route('login') }}" class="biscuit-btn biscuit-btn--ghost text-label-md">
                            ログイン
                        </a>
                    </div>
                    <p class="text-caption text-on-surface-variant/80 mt-1">クレジットカード不要・メール登録だけですぐに開始できます。</p>
                </div>

                {{-- オリジナルのフラットイラスト（写真素材・AI画像は不使用） --}}
                <div class="relative">
                    <div class="frame-card shadow-biscuit">
                        <div class="overflow-hidden">
                            @include('components.illustrations.study-desk')
                        </div>
                    </div>
                    <span class="hidden sm:flex absolute -bottom-4 -left-4 items-center gap-2 bg-surface-container-lowest border border-outline-variant rounded-full px-3.5 py-2 shadow-biscuit">
                        <span class="material-symbols-outlined text-[18px] text-secondary">event_available</span>
                        <span class="text-caption font-bold text-on-surface">毎日コツコツ続ける</span>
                    </span>
                </div>

            </div>
        </div>
    </header>

    {{-- ═══════════ 今、英語を学ぶ理由 ═══════════ --}}
    <section class="relative border-y border-outline-variant bg-surface-container py-20 lg:py-24">
        <div class="relative max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
            <div class="max-w-2xl mb-12">
                <span class="kicker">Why English, Why Now</span>
                <h2 class="font-display text-headline-lg font-bold text-on-surface mt-3 mb-4 leading-snug">
                    円安、昇進、進学、外資系就職。<br class="hidden lg:block">
                    英語が、<span class="hl hl--sage">あなたの可能性</span>を広げる。
                </h2>
                <p class="text-body-md text-on-surface-variant leading-relaxed">
                    急速に進む円安により、海外との経済格差は広がり続けています。いまや英語力は、社内での昇進・昇給の条件として、また大学院進学や海外進学に欠かせないスキルとして求められています。給与水準の高い外資系企業への就職でも、実践的な英語力は避けて通れません。英語は、これからの時代を生き抜くための「稼ぐ力」であり、キャリアと人生の選択肢を大きく広げる武器になります。
                </p>
            </div>

            <div class="grid sm:grid-cols-3 gap-5">
                @php
                    $reasons = [
                        ['icon' => 'trending_up', 'tone' => 'primary',   'title' => '円安時代の「稼ぐ力」',   'body' => '海外案件・リモート勤務・副業まで、英語が使えるだけで受け取れる報酬の幅が変わります。'],
                        ['icon' => 'school',      'tone' => 'tertiary',  'title' => '昇進・進学の必須条件',   'body' => '社内評価やTOEICスコア基準、大学院・海外進学の出願要件として英語力が問われます。'],
                        ['icon' => 'apartment',   'tone' => 'secondary', 'title' => '外資系・グローバル就職', 'body' => '面接も業務も英語が前提。実践的なアウトプット力が、選考を通過する分かれ目になります。'],
                    ];
                @endphp
                @foreach($reasons as $r)
                <div class="biscuit-card reveal relative overflow-hidden p-6 flex flex-col gap-3">
                    <span class="pointer-events-none absolute inset-x-0 top-0 h-1 bg-{{ $r['tone'] }}"></span>
                    <span class="grid place-items-center w-12 h-12 rounded-xl bg-{{ $r['tone'] }}-container text-{{ $r['tone'] }} hover-hop mt-1">
                        <span class="material-symbols-outlined text-2xl">{{ $r['icon'] }}</span>
                    </span>
                    <h3 class="font-display text-base font-bold text-on-surface">{{ $r['title'] }}</h3>
                    <p class="text-caption text-on-surface-variant leading-relaxed">{{ $r['body'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════ CTA ═══════════ --}}
    <section class="py-20 lg:py-24">
        <div class="max-w-5xl mx-auto px-margin-mobile md:px-margin-desktop">
            <div class="reveal relative overflow-hidden rounded-2xl bg-inverse-surface text-inverse-on-surface px-8 py-14 lg:px-14 lg:py-16 text-center shadow-biscuit-lg">
                <span class="pointer-events-none absolute -top-20 -right-16 size-56 rounded-full bg-white/[0.04]"></span>
                <span class="pointer-events-none absolute -bottom-24 -left-16 size-64 rounded-full bg-white/[0.04]"></span>

                <div class="relative">
                    <span class="kicker" style="color:#d9a94e">Start today</span>
                    <h2 class="font-display text-headline-lg md:text-display font-bold mt-3 leading-tight">
                        さあ、今日からはじめよう。
                    </h2>

                    @guest
                        <p class="text-inverse-on-surface/75 mt-4 text-body-md max-w-lg mx-auto leading-relaxed">
                            思い立った今が、始めどきです。無料登録して、たった今から英語学習をスタートしましょう。
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center mt-8">
                            <a href="{{ route('register') }}" class="biscuit-btn biscuit-btn--honey pop-burst text-label-md">
                                無料で登録する
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </a>
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center justify-center px-6 py-2.5 rounded-full font-display font-bold text-inverse-on-surface border border-inverse-on-surface/30 hover:bg-inverse-on-surface/10 transition-colors no-underline">
                                ログインして続ける
                            </a>
                        </div>
                    @endguest

                    @auth
                        <p class="text-inverse-on-surface/75 mt-4 text-body-md">
                            おかえりなさい、<span class="text-inverse-primary font-bold">{{ Auth::user()->name }}</span>さん。今日も学習を続けましょう。
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center mt-8">
                            <a href="{{ route('dashboard') }}" class="biscuit-btn biscuit-btn--honey text-label-md">
                                学習をはじめる
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </a>
                            <button type="button"
                                    class="inline-flex items-center justify-center px-6 py-2.5 rounded-full font-display font-bold text-inverse-on-surface border border-inverse-on-surface/30 hover:bg-inverse-on-surface/10 transition-colors"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                ログアウト
                            </button>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
