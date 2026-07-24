@extends('layouts.app')

@section('title', 'Ranking')

@section('content')
<div class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <x-english.breadcrumb>
        <a href="{{ route('english.hub') }}" class="hover:text-primary transition-colors no-underline">Home</a>
        <span class="mx-1">/</span>
        <span class="text-on-surface font-semibold">ランキング</span>
    </x-english.breadcrumb>

    <div class="mb-8 text-center">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">ランキング</h1>
        <p class="text-body-md text-on-surface-variant">獲得したXP（週間・月間・Total）でランキングを競おう</p>
    </div>

    <div class="max-w-3xl mx-auto">

        {{-- タブ（GETリクエスト） --}}
        <div class="flex gap-2 mb-6 justify-center">
            @foreach([['id'=>'weekly','label'=>'週間'],['id'=>'monthly','label'=>'月間'],['id'=>'total','label'=>'Total']] as $tab)
            <a href="{{ route('english.ranking', ['period' => $tab['id']]) }}"
               class="{{ $period === $tab['id'] ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest shadow-sm text-on-surface hover:bg-surface-container' }} px-6 py-2.5 rounded-[0.75rem] font-label-md text-label-md transition-all no-underline">
                {{ $tab['label'] }}
            </a>
            @endforeach
        </div>

        {{-- ランキングテーブル --}}
        <div class="bg-surface-container-lowest rounded-[0.5rem] shadow-sm overflow-hidden mb-4">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="py-4 px-4 text-label-md text-on-surface-variant text-center w-16">順位</th>
                        <th class="py-4 px-4 text-label-md text-on-surface-variant">ユーザー</th>
                        <th class="py-4 px-4 text-label-md text-on-surface-variant text-center">Level</th>
                        <th class="py-4 px-4 text-label-md text-on-surface-variant text-center">総学習日数</th>
                        <th class="py-4 px-4 text-label-md text-on-surface-variant text-right">
                            {{ $period === 'total' ? 'Total XP' : ($period === 'weekly' ? '週間 XP' : '月間 XP') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/50">
                    @forelse($rankings as $i => $entry)
                    @php
                        $rank = ($rankings->currentPage() - 1) * $rankings->perPage() + $i + 1;
                        $level = (int) floor($entry->total_xp / 500) + 1;
                        $xpDisplay = $period === 'total' ? $entry->total_xp : ($entry->period_xp ?? 0);
                        $isMe = $entry->id === $user->id;
                    @endphp
                    <tr class="{{ $rank <= 3 ? 'bg-primary/5 font-semibold' : '' }} {{ $isMe ? 'bg-primary/10 ring-1 ring-inset ring-primary/30' : '' }} hover:bg-surface-container-low/50 transition-colors">
                        <td class="py-4 px-4 text-center font-bold">
                            @if($rank === 1) <span class="text-xl">🥇</span>
                            @elseif($rank === 2) <span class="text-xl">🥈</span>
                            @elseif($rank === 3) <span class="text-xl">🥉</span>
                            @else <span class="text-on-surface-variant text-sm">{{ $rank }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-on-surface">
                            {{ $entry->name }}
                            @if($isMe)<span class="ml-1 text-caption text-primary font-bold">（あなた）</span>@endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="bg-primary/10 text-primary text-caption font-bold px-2 py-0.5 rounded-[0.75rem]">Lv.{{ $level }}</span>
                        </td>
                        <td class="py-4 px-4 text-center text-on-surface-variant">{{ $entry->total_study_days }}日</td>
                        <td class="py-4 px-4 text-right font-bold text-primary">{{ number_format($xpDisplay) }} <span class="text-caption font-normal text-on-surface-variant">XP</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-on-surface-variant text-body-md">
                            この期間のランキングデータがありません
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ページネーション --}}
        @if($rankings->hasPages())
        <div class="mb-4">
            {{ $rankings->appends(['period' => $period])->links() }}
        </div>
        @endif

        {{-- 自分の順位（下部固定表示） --}}
        <div class="bg-primary/10 border-2 border-primary rounded-[0.5rem] p-4">
            <p class="text-caption text-primary font-bold mb-2">あなたの順位</p>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="text-headline-md font-black text-primary">{{ $myRank }}位</span>
                    <div>
                        <p class="font-bold text-on-surface">{{ $user->name }}</p>
                        <span class="bg-primary/10 text-primary text-caption font-bold px-2 py-0.5 rounded-[0.75rem]">Lv.{{ $levelInfo['level'] }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-black text-primary text-headline-md">{{ number_format($levelInfo['current_xp']) }}</p>
                    <p class="text-caption text-on-surface-variant">Total XP</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
