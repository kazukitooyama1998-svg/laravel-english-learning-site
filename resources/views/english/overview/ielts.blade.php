@extends('layouts.app')

@section('title', 'IELTS Overview')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.overview.index') }}" class="hover:text-primary transition-colors no-underline">試験概要</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">IELTS</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">IELTS 概要</h1>
        <p class="text-body-md text-on-surface-variant">International English Language Testing System</p>
    </div>

    <div x-data="{ activeTab: 'listening' }" class="max-w-4xl">

        {{-- タブナビゲーション --}}
        <div class="flex flex-wrap gap-2 mb-8 border-b border-outline-variant pb-4">
            @foreach([['id'=>'listening','label'=>'Listening'],['id'=>'reading','label'=>'Reading'],['id'=>'writing','label'=>'Writing'],['id'=>'speaking','label'=>'Speaking']] as $tab)
            <button @click="activeTab = '{{ $tab['id'] }}'"
                    :class="activeTab === '{{ $tab['id'] }}' ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest border border-outline-variant text-on-surface hover:bg-surface-container'"
                    class="px-5 py-2.5 rounded-[0.75rem] font-label-md text-label-md transition-all">
                {{ $tab['label'] }}
            </button>
            @endforeach
        </div>

        {{-- Listening --}}
        <div x-show="activeTab === 'listening'" x-transition>
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 space-y-6">
                <h2 class="text-headline-md font-bold text-on-surface">Listening セクション</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-primary">40</p>
                        <p class="text-caption text-on-surface-variant">問題数</p>
                    </div>
                    <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-primary">30分</p>
                        <p class="text-caption text-on-surface-variant">試験時間</p>
                    </div>
                    <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-primary">0-9</p>
                        <p class="text-caption text-on-surface-variant">バンドスコア</p>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-on-surface mb-2">セクション構成</h3>
                    <ul class="space-y-2 text-body-md text-on-surface-variant">
                        <li class="flex items-start gap-2"><span class="text-primary">•</span> Section 1: 日常会話（2人の会話）</li>
                        <li class="flex items-start gap-2"><span class="text-primary">•</span> Section 2: 一般的な会話（1人のスピーチ）</li>
                        <li class="flex items-start gap-2"><span class="text-primary">•</span> Section 3: 教育・トレーニング場面の会話</li>
                        <li class="flex items-start gap-2"><span class="text-primary">•</span> Section 4: 学術的なモノローグ（大学の講義など）</li>
                    </ul>
                </div>
                <div class="p-4 bg-primary/5 rounded-[0.5rem] border border-primary/20">
                    <p class="font-bold text-primary mb-1">注意点</p>
                    <p class="text-body-md text-on-surface-variant">音声は1回のみ再生。問題を事前に読む時間が与えられるので活用しよう。</p>
                </div>
            </div>
        </div>

        {{-- Reading --}}
        <div x-show="activeTab === 'reading'" x-transition>
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 space-y-6">
                <h2 class="text-headline-md font-bold text-on-surface">Reading セクション</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-primary">40</p>
                        <p class="text-caption text-on-surface-variant">問題数</p>
                    </div>
                    <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-primary">60分</p>
                        <p class="text-caption text-on-surface-variant">試験時間</p>
                    </div>
                    <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-primary">3つ</p>
                        <p class="text-caption text-on-surface-variant">長文パッセージ</p>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-on-surface mb-2">問題形式</h3>
                    <ul class="space-y-2 text-body-md text-on-surface-variant">
                        <li class="flex items-start gap-2"><span class="text-primary">•</span> 多肢選択問題</li>
                        <li class="flex items-start gap-2"><span class="text-primary">•</span> 穴埋め問題（Summary completion）</li>
                        <li class="flex items-start gap-2"><span class="text-primary">•</span> True / False / Not Given</li>
                        <li class="flex items-start gap-2"><span class="text-primary">•</span> 見出しマッチング</li>
                    </ul>
                </div>
                <div class="p-4 bg-primary/5 rounded-[0.5rem] border border-primary/20">
                    <p class="font-bold text-primary mb-1">注意点</p>
                    <p class="text-body-md text-on-surface-variant">Academic版とGeneral Training版で内容が異なる。スキャニングとスキミングのスキルが重要。</p>
                </div>
            </div>
        </div>

        {{-- Writing --}}
        <div x-show="activeTab === 'writing'" x-transition>
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 space-y-6">
                <h2 class="text-headline-md font-bold text-on-surface">Writing セクション</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-primary">60分</p>
                        <p class="text-caption text-on-surface-variant">試験時間</p>
                    </div>
                    <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-primary">2問</p>
                        <p class="text-caption text-on-surface-variant">課題数</p>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-on-surface mb-2">Task 構成</h3>
                    <div class="space-y-3">
                        <div class="p-4 bg-surface-container-low rounded-[0.5rem]">
                            <p class="font-bold text-on-surface">Task 1（150語以上）</p>
                            <p class="text-body-md text-on-surface-variant mb-3">Academic と General Training で内容が異なります。</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="p-3 bg-surface-container-lowest border border-outline-variant rounded-[0.5rem]">
                                    <p class="text-caption font-bold text-primary uppercase tracking-wider mb-1">Academic</p>
                                    <p class="text-body-md text-on-surface-variant">グラフ・図・地図・プロセス図などのデータを描写・説明するレポートを書く</p>
                                </div>
                                <div class="p-3 bg-surface-container-lowest border border-outline-variant rounded-[0.5rem]">
                                    <p class="text-caption font-bold text-primary uppercase tracking-wider mb-1">General Training</p>
                                    <p class="text-body-md text-on-surface-variant">与えられた状況に応じて、フォーマル・セミフォーマル・インフォーマルな手紙を書く</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-surface-container-low rounded-[0.5rem]">
                            <p class="font-bold text-on-surface">Task 2（250語以上）</p>
                            <p class="text-body-md text-on-surface-variant">社会問題について意見を述べるエッセイを書く</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-primary/5 rounded-[0.5rem] border border-primary/20">
                    <p class="font-bold text-primary mb-1">注意点</p>
                    <p class="text-body-md text-on-surface-variant">Task 1はAcademic/General Trainingで課題内容が異なりますが、Task 2のエッセイ課題はどちらも共通です。配点はTask 2の方が大きいため、時間配分は Task 1に約20分、Task 2に約40分を目安にしましょう。</p>
                </div>
            </div>
        </div>

        {{-- Speaking --}}
        <div x-show="activeTab === 'speaking'" x-transition>
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8 space-y-6">
                <h2 class="text-headline-md font-bold text-on-surface">Speaking セクション</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-primary">11-14分</p>
                        <p class="text-caption text-on-surface-variant">試験時間</p>
                    </div>
                    <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                        <p class="text-headline-lg font-black text-primary">3パート</p>
                        <p class="text-caption text-on-surface-variant">構成</p>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-on-surface mb-2">Part 構成</h3>
                    <div class="space-y-3">
                        <div class="p-4 bg-surface-container-low rounded-[0.5rem]">
                            <p class="font-bold text-on-surface">Part 1 (4-5分)</p>
                            <p class="text-body-md text-on-surface-variant">自己紹介・身近なトピックについての質問に答える</p>
                        </div>
                        <div class="p-4 bg-surface-container-low rounded-[0.5rem]">
                            <p class="font-bold text-on-surface">Part 2 (3-4分)</p>
                            <p class="text-body-md text-on-surface-variant">与えられたトピックカードについて1-2分間スピーチする</p>
                        </div>
                        <div class="p-4 bg-surface-container-low rounded-[0.5rem]">
                            <p class="font-bold text-on-surface">Part 3 (4-5分)</p>
                            <p class="text-body-md text-on-surface-variant">Part 2のトピックに関連した抽象的な質問に答える</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-primary/5 rounded-[0.5rem] border border-primary/20">
                    <p class="font-bold text-primary mb-1">評価基準</p>
                    <p class="text-body-md text-on-surface-variant">流暢さと一貫性・語彙力・文法の幅と正確性・発音の4項目で評価される。</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
