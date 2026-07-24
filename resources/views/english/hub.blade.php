@extends('layouts.app')

@section('title', 'English Learning Hub')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-8 md:py-12">

    {{-- ヒーロー: タイトル + 現在のレベルカード --}}
    <section class="relative overflow-hidden rounded-[0.75rem] mb-8 p-8 md:p-10 bg-cover bg-center"
              style="background-image: url('{{ asset('images/english/hub-hero.webp') }}');">
        <div class="absolute inset-0 bg-gradient-to-r from-white/80 from-5% via-white/25 via-40% to-transparent to-65% pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center gap-8">
            <div class="flex-1">
                <h1 class="text-display font-black text-on-surface mb-1">英語学習</h1>
                <p class="text-headline-md font-bold text-primary mb-3">English Learning</p>
                <p class="text-body-md text-on-surface-variant max-w-lg">
                    TOEIC・IELTS対策を中心に、英語力を伸ばすための学習コンテンツをご利用いただけます。
                </p>
            </div>

            <div class="w-full lg:w-[380px] bg-surface-container-lowest rounded-[0.75rem] shadow-md p-6 shrink-0">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 shrink-0 bg-primary/10 rounded-[0.75rem] flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">military_tech</span>
                    </div>
                    <div>
                        <p class="text-caption text-on-surface-variant leading-none mb-1">現在のレベル</p>
                        <p class="text-headline-md font-black text-on-surface leading-none">Lv.{{ $levelInfo['level'] }}</p>
                    </div>
                </div>

                <x-english.xp-bar
                    :level="$levelInfo['level']"
                    :currentXp="$levelInfo['xp_in_level']"
                    :nextXp="500" />

                <div class="grid grid-cols-3 gap-2 mt-5 pt-4 border-t border-outline-variant/30">
                    <div class="text-center">
                        <p class="text-headline-md font-black text-on-surface">{{ number_format($levelInfo['current_xp']) }}</p>
                        <p class="text-caption text-on-surface-variant mt-0.5">Total XP</p>
                    </div>
                    <div class="text-center border-x border-outline-variant/30">
                        {{-- 連続日数表示（コメントアウト。トータル学習日数表示に変更したため） --}}
                        {{--
                        <p class="text-headline-md font-black text-on-surface">🔥 {{ $user->study_streak }}</p>
                        <p class="text-caption text-on-surface-variant mt-0.5">日連続</p>
                        --}}
                        <p class="text-headline-md font-black text-on-surface">{{ $totalStudyDays }}</p>
                        <p class="text-caption text-on-surface-variant mt-0.5">総学習日数</p>
                    </div>
                    <div class="text-center">
                        <p class="text-headline-md font-black text-on-surface">{{ $overallProgress }}%</p>
                        <p class="text-caption text-on-surface-variant mt-0.5">全体進捗率</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-2 pt-4 border-t border-outline-variant/30">
                    <div class="text-center">
                        @if($examDaysLeft['toeic'] === null)
                            <p class="text-headline-md font-black text-on-surface">未設定</p>
                        @elseif($examDaysLeft['toeic'] > 0)
                            <p class="text-headline-md font-black text-on-surface">{{ $examDaysLeft['toeic'] }}<span class="text-body-md">日</span></p>
                        @elseif($examDaysLeft['toeic'] === 0)
                            <p class="text-headline-md font-black text-on-surface">本日</p>
                        @else
                            <p class="text-headline-md font-black text-on-surface">終了</p>
                        @endif
                        <p class="text-caption text-on-surface-variant mt-0.5">TOEICまで</p>
                    </div>
                    <div class="text-center border-l border-outline-variant/30">
                        @if($examDaysLeft['ielts'] === null)
                            <p class="text-headline-md font-black text-on-surface">未設定</p>
                        @elseif($examDaysLeft['ielts'] > 0)
                            <p class="text-headline-md font-black text-on-surface">{{ $examDaysLeft['ielts'] }}<span class="text-body-md">日</span></p>
                        @elseif($examDaysLeft['ielts'] === 0)
                            <p class="text-headline-md font-black text-on-surface">本日</p>
                        @else
                            <p class="text-headline-md font-black text-on-surface">終了</p>
                        @endif
                        <p class="text-caption text-on-surface-variant mt-0.5">IELTSまで</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 試験概要・学習ストラテジー バナー --}}
    <a href="{{ route('english.strategy.index') }}"
       class="block bg-primary/5 border border-primary/10 rounded-[0.75rem] p-6 mb-8 no-underline text-inherit hover:bg-primary/10 transition-colors">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 shrink-0 bg-primary/10 rounded-[0.75rem] flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-2xl">menu_book</span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-label-md font-bold text-on-surface mb-1">試験概要と学習ストラテジー</h3>
                <p class="text-caption text-on-surface-variant">TOEIC・IELTSの試験概要や出題形式を確認し、効果的な学習の進め方を学びましょう。</p>
            </div>
            <span class="hidden sm:flex items-center gap-1 text-primary text-label-md font-semibold shrink-0">
                詳しく見る
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </span>
        </div>
    </a>

    {{-- 機能カードグリッド --}}
    <section class="mb-10">
        <h2 class="text-headline-md font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">auto_stories</span>
            学習メニュー
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <x-english.feature-card
                icon="menu_book"
                title="TOEIC学習"
                description="TOEICの文法・読解を中心に学習"
                href="{{ route('english.toeic.index') }}"
                :progress="$featureProgress['toeic']"
            />
            <x-english.feature-card
                icon="record_voice_over"
                title="IELTS Speaking"
                description="IELTSスピーキングを実践的に練習"
                href="{{ route('english.ielts.index') }}"
                :progress="$featureProgress['ielts']"
            />
            <x-english.feature-card
                icon="translate"
                title="英単語"
                description="フラッシュカードで語彙を強化"
                href="{{ route('english.vocabulary.index') }}"
                :progress="$featureProgress['vocabulary']"
            />
            <x-english.feature-card
                icon="keyboard"
                title="タイピング練習"
                description="英語タイピングのスピードと精度を向上"
                href="{{ route('english.typing.index') }}"
                :progress="$featureProgress['typing']"
            />
            <x-english.feature-card
                icon="quiz"
                title="クイズ"
                description="スペル・語彙クイズで実力をテスト"
                href="{{ route('english.quiz.index') }}"
                actionLabel="問題に挑戦する"
            />
        </div>
    </section>

    {{-- 下部ナビゲーション --}}
    <section class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('english.progress') }}"
           class="flex-1 bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-5 flex items-center gap-4 no-underline group">
            <div class="p-3 bg-primary/10 rounded-[0.75rem]">
                <span class="material-symbols-outlined text-primary text-2xl">trending_up</span>
            </div>
            <div>
                <h3 class="text-label-md font-bold text-on-surface group-hover:text-primary transition-colors">学習管理</h3>
                <p class="text-caption text-on-surface-variant">進捗・履歴・学習日数を確認</p>
            </div>
            <span class="material-symbols-outlined text-on-surface-variant ml-auto">chevron_right</span>
        </a>
        <a href="{{ route('english.ranking') }}"
           class="flex-1 bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-5 flex items-center gap-4 no-underline group">
            <div class="p-3 bg-primary/10 rounded-[0.75rem]">
                <span class="material-symbols-outlined text-primary text-2xl">leaderboard</span>
            </div>
            <div>
                <h3 class="text-label-md font-bold text-on-surface group-hover:text-primary transition-colors">ランキング</h3>
                <p class="text-caption text-on-surface-variant">週間・月間・総合ランキング</p>
            </div>
            <span class="material-symbols-outlined text-on-surface-variant ml-auto">chevron_right</span>
        </a>
    </section>

</div>
@endsection
