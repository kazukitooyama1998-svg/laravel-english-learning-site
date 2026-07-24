@extends('layouts.app')

@section('title', 'TOEIC Overview')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('english.overview.index') }}" class="hover:text-primary transition-colors no-underline">試験概要</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">TOEIC</span>
    </x-english.breadcrumb>

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">TOEIC 概要</h1>
        <p class="text-body-md text-on-surface-variant">Test of English for International Communication</p>
    </div>

    <div class="max-w-4xl space-y-6">

        {{-- 試験概要 --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8">
            <h2 class="text-headline-md font-bold text-on-surface mb-4">試験の基本情報</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                    <p class="text-headline-lg font-black text-primary">200問</p>
                    <p class="text-caption text-on-surface-variant">総問題数</p>
                </div>
                <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                    <p class="text-headline-lg font-black text-primary">120分</p>
                    <p class="text-caption text-on-surface-variant">試験時間</p>
                </div>
                <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                    <p class="text-headline-lg font-black text-primary">990点</p>
                    <p class="text-caption text-on-surface-variant">満点</p>
                </div>
                <div class="p-4 bg-surface-container-low rounded-[0.5rem] text-center">
                    <p class="text-headline-lg font-black text-primary">7</p>
                    <p class="text-caption text-on-surface-variant">Part数</p>
                </div>
            </div>
        </div>

        {{-- アコーディオン: Part別詳細 --}}
        @php
        $parts = [
            ['part' => 1, 'name' => 'Part 1 - Photographs', 'questions' => 6, 'time' => 'Listening内', 'desc' => '写真を見て、最も適切な説明文（A〜D）を選ぶ。人物・物・場所の状態を正確に描写している選択肢を選ぶ。'],
            ['part' => 2, 'name' => 'Part 2 - Question-Response', 'questions' => 25, 'time' => 'Listening内', 'desc' => '質問や発言を聞き、最も適切な応答（A〜C）を選ぶ。Wh-疑問文、Yes/No疑問文、依頼・提案など様々な形式がある。'],
            ['part' => 3, 'name' => 'Part 3 - Conversations', 'questions' => 39, 'time' => 'Listening内', 'desc' => '2〜3人の会話を聞き、各会話に関する3問に答える。13セットの会話で構成。一部に図表問題あり。'],
            ['part' => 4, 'name' => 'Part 4 - Talks', 'questions' => 30, 'time' => 'Listening内', 'desc' => '1人によるトーク（ナレーション）を聞き、各トークに関する3問に答える。10セット構成。'],
            ['part' => 5, 'name' => 'Part 5 - Incomplete Sentences', 'questions' => 30, 'time' => 'Reading内', 'desc' => '空欄を含む文を読み、最も適切な語句（A〜D）を選ぶ。品詞・語彙・文法の知識が問われる。'],
            ['part' => 6, 'name' => 'Part 6 - Text Completion', 'questions' => 16, 'time' => 'Reading内', 'desc' => '長文中の空欄に最も適切な語句や文を選ぶ。4つの文章×4問で構成。文脈理解が重要。'],
            ['part' => 7, 'name' => 'Part 7 - Reading Comprehension', 'questions' => 54, 'time' => 'Reading内', 'desc' => 'シングルパッセージ（1文書）とダブル・トリプルパッセージ（複数文書）の読解問題。Eメール・広告・記事・メモなど多様な文書が出題される。'],
        ];
        @endphp

        <div x-data="{ openPart: null }" class="space-y-3">
            @foreach($parts as $p)
            <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm overflow-hidden">
                <button @click="openPart = openPart === {{ $p['part'] }} ? null : {{ $p['part'] }}"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-surface-container transition-colors">
                    <div class="flex items-center gap-4">
                        <span class="bg-primary text-on-primary text-caption font-black w-8 h-8 rounded-[0.75rem] flex items-center justify-center flex-shrink-0">{{ $p['part'] }}</span>
                        <div>
                            <p class="font-bold text-on-surface">{{ $p['name'] }}</p>
                            <p class="text-caption text-on-surface-variant">{{ $p['questions'] }}問 / {{ $p['time'] }}</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant transition-transform"
                          :class="openPart === {{ $p['part'] }} ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="openPart === {{ $p['part'] }}" x-transition class="px-5 pb-5 border-t border-outline-variant">
                    <p class="text-body-md text-on-surface-variant mt-4">{{ $p['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- スコア換算表 --}}
        <div class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm p-8">
            <h2 class="text-headline-md font-bold text-on-surface mb-4">スコア目安と英語力</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-outline-variant">
                            <th class="py-3 px-4 text-label-md text-on-surface-variant">スコア</th>
                            <th class="py-3 px-4 text-label-md text-on-surface-variant">レベル</th>
                            <th class="py-3 px-4 text-label-md text-on-surface-variant">目安</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/50 text-body-md">
                        <tr class="hover:bg-surface-container-low/50">
                            <td class="py-3 px-4 font-bold text-primary">900+</td>
                            <td class="py-3 px-4 text-on-surface">上級</td>
                            <td class="py-3 px-4 text-on-surface-variant">ネイティブと流暢に会話できるレベル</td>
                        </tr>
                        <tr class="hover:bg-surface-container-low/50">
                            <td class="py-3 px-4 font-bold text-primary">800+</td>
                            <td class="py-3 px-4 text-on-surface">中上級</td>
                            <td class="py-3 px-4 text-on-surface-variant">ビジネスで英語を使える実用レベル</td>
                        </tr>
                        <tr class="hover:bg-surface-container-low/50">
                            <td class="py-3 px-4 font-bold text-primary">700+</td>
                            <td class="py-3 px-4 text-on-surface">中級</td>
                            <td class="py-3 px-4 text-on-surface-variant">日常業務で英語を活用できるレベル</td>
                        </tr>
                        <tr class="hover:bg-surface-container-low/50">
                            <td class="py-3 px-4 font-bold text-primary">600+</td>
                            <td class="py-3 px-4 text-on-surface">初中級</td>
                            <td class="py-3 px-4 text-on-surface-variant">基本的な英語コミュニケーションができる</td>
                        </tr>
                        <tr class="hover:bg-surface-container-low/50">
                            <td class="py-3 px-4 font-bold text-primary">〜500</td>
                            <td class="py-3 px-4 text-on-surface">初級</td>
                            <td class="py-3 px-4 text-on-surface-variant">基礎英語の習得段階</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
