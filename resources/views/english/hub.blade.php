@extends('layouts.app')

@section('title', 'English Learning Hub')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-8 md:py-12">

    {{-- ヒーロー: あいさつ + 現在のレベルカード --}}
    <section class="relative overflow-hidden rounded-2xl mb-10 p-6 sm:p-8 md:p-10 bg-surface-container-lowest border border-outline-variant shadow-biscuit">
        <span class="pointer-events-none absolute -top-24 -left-24 size-64 rounded-full bg-primary/[0.05]"></span>
        <span class="pointer-events-none absolute -bottom-28 right-2 size-72 rounded-full bg-secondary/[0.05]"></span>

        <div class="relative grid lg:grid-cols-[1fr_360px] gap-8 lg:gap-12 items-center">
            <div>
                <span class="kicker">Welcome back</span>
                <h1 class="font-display text-headline-lg md:text-display font-bold text-on-surface mt-3 mb-3 leading-[1.3]">
                    {{ $user->name }}さん、<br class="hidden sm:block">今日も一日がんばりましょう。
                </h1>
                <p class="text-body-md text-on-surface-variant max-w-xl leading-relaxed">
                    英語は<span class="hl">毎日の積み重ね</span>が力になります。ほんの少しでも大丈夫。あせらず、コツコツと続けていきましょう。
                </p>
            </div>

            <div class="w-full biscuit-panel border border-outline-variant p-6 shrink-0">
                <div class="flex items-center gap-3 mb-4">
                    <span class="grid place-items-center w-11 h-11 shrink-0 rounded-xl bg-primary text-on-primary">
                        <span class="material-symbols-outlined text-[21px]">military_tech</span>
                    </span>
                    <div>
                        <p class="text-caption text-on-surface-variant leading-none mb-1">現在のレベル</p>
                        <p class="text-headline-md font-bold text-on-surface leading-none">Lv.{{ $levelInfo['level'] }}</p>
                    </div>
                </div>

                <x-english.xp-bar
                    :level="$levelInfo['level']"
                    :currentXp="$levelInfo['xp_in_level']"
                    :nextXp="500" />

                <div class="grid grid-cols-3 gap-2 mt-5 pt-4 border-t border-outline-variant">
                    <div class="text-center">
                        <p class="text-headline-md font-bold text-on-surface">{{ number_format($levelInfo['current_xp']) }}</p>
                        <p class="text-caption text-on-surface-variant mt-0.5">Total XP</p>
                    </div>
                    <div class="text-center border-x border-outline-variant">
                        <p class="text-headline-md font-bold text-on-surface">{{ $totalStudyDays }}</p>
                        <p class="text-caption text-on-surface-variant mt-0.5">総学習日数</p>
                    </div>
                    <div class="text-center">
                        <p class="text-headline-md font-bold text-on-surface">{{ $overallProgress }}%</p>
                        <p class="text-caption text-on-surface-variant mt-0.5">全体進捗率</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-2 pt-4 border-t border-outline-variant">
                    <div class="text-center">
                        @if($examDaysLeft['toeic'] === null)
                            <p class="text-headline-md font-bold text-on-surface">未設定</p>
                        @elseif($examDaysLeft['toeic'] > 0)
                            <p class="text-headline-md font-bold text-on-surface">{{ $examDaysLeft['toeic'] }}<span class="text-body-md">日</span></p>
                        @elseif($examDaysLeft['toeic'] === 0)
                            <p class="text-headline-md font-bold text-on-surface">本日</p>
                        @else
                            <p class="text-headline-md font-bold text-on-surface">終了</p>
                        @endif
                        <p class="text-caption text-on-surface-variant mt-0.5">TOEICまで</p>
                    </div>
                    <div class="text-center border-l border-outline-variant">
                        @if($examDaysLeft['ielts'] === null)
                            <p class="text-headline-md font-bold text-on-surface">未設定</p>
                        @elseif($examDaysLeft['ielts'] > 0)
                            <p class="text-headline-md font-bold text-on-surface">{{ $examDaysLeft['ielts'] }}<span class="text-body-md">日</span></p>
                        @elseif($examDaysLeft['ielts'] === 0)
                            <p class="text-headline-md font-bold text-on-surface">本日</p>
                        @else
                            <p class="text-headline-md font-bold text-on-surface">終了</p>
                        @endif
                        <p class="text-caption text-on-surface-variant mt-0.5">IELTSまで</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 試験概要・学習ストラテジー バナー --}}
    <a href="{{ route('english.strategy.index') }}"
       class="biscuit-card group block p-6 mb-10 no-underline text-inherit">
        <div class="flex items-center gap-5">
            <span class="grid place-items-center w-12 h-12 shrink-0 rounded-xl bg-secondary-container text-secondary hover-hop">
                <span class="material-symbols-outlined text-2xl">menu_book</span>
            </span>
            <div class="flex-1 min-w-0">
                <h3 class="text-label-md font-bold text-on-surface mb-1">試験概要と学習ストラテジー</h3>
                <p class="text-caption text-on-surface-variant leading-relaxed">TOEIC・IELTSの試験概要や出題形式を確認し、効果的な学習の進め方を学びましょう。</p>
            </div>
            <span class="hidden sm:flex items-center gap-1 text-primary text-label-md font-bold shrink-0 group-hover:gap-2 transition-all">
                詳しく見る
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </span>
        </div>
    </a>

    {{-- 機能カードグリッド --}}
    <section class="mb-12">
        <div class="reveal mb-5">
            <span class="kicker">Menu</span>
            <h2 class="font-display text-headline-md font-bold text-on-surface mt-2">学習メニュー</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <x-english.feature-card
                icon="menu_book" tone="primary"
                title="TOEIC学習"
                description="TOEICの文法・読解、Part 1〜7を実践的に学習"
                href="{{ route('english.toeic.index') }}"
                :progress="$featureProgress['toeic']"
            />
            <x-english.feature-card
                icon="record_voice_over" tone="secondary"
                title="IELTS Speaking"
                description="IELTSスピーキングを実践的に練習"
                href="{{ route('english.ielts.index') }}"
                :progress="$featureProgress['ielts']"
            />
            <x-english.feature-card
                icon="translate" tone="tertiary"
                title="英単語"
                description="フラッシュカードで語彙を強化"
                href="{{ route('english.vocabulary.index') }}"
                :progress="$featureProgress['vocabulary']"
            />
            <x-english.feature-card
                icon="keyboard" tone="primary"
                title="タイピング練習"
                description="英語タイピングのスピードと精度を向上"
                href="{{ route('english.typing.index') }}"
                :progress="$featureProgress['typing']"
            />
            <x-english.feature-card
                icon="quiz" tone="secondary"
                title="クイズ"
                description="スペル・語彙クイズで実力をテスト"
                href="{{ route('english.quiz.index') }}"
                actionLabel="問題に挑戦する"
            />
        </div>
    </section>

    {{-- 下部ナビゲーション --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <a href="{{ route('english.progress') }}"
           class="reveal biscuit-card group p-5 flex items-center gap-4 no-underline">
            <span class="grid place-items-center p-3 rounded-xl bg-tertiary-container text-tertiary hover-hop">
                <span class="material-symbols-outlined text-2xl">trending_up</span>
            </span>
            <div>
                <h3 class="text-label-md font-bold text-on-surface group-hover:text-primary transition-colors">学習管理</h3>
                <p class="text-caption text-on-surface-variant">進捗・履歴・学習日数を確認</p>
            </div>
            <span class="material-symbols-outlined text-on-surface-variant ml-auto group-hover:translate-x-1 transition-transform">chevron_right</span>
        </a>
        <a href="{{ route('english.ranking') }}"
           class="reveal biscuit-card group p-5 flex items-center gap-4 no-underline">
            <span class="grid place-items-center p-3 rounded-xl bg-secondary-container text-secondary hover-hop">
                <span class="material-symbols-outlined text-2xl">leaderboard</span>
            </span>
            <div>
                <h3 class="text-label-md font-bold text-on-surface group-hover:text-primary transition-colors">ランキング</h3>
                <p class="text-caption text-on-surface-variant">週間・月間・総合ランキング</p>
            </div>
            <span class="material-symbols-outlined text-on-surface-variant ml-auto group-hover:translate-x-1 transition-transform">chevron_right</span>
        </a>
    </section>

</div>
@endsection
