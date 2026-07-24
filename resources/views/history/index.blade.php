@extends('layouts.app')

@section('content')
<main class="flex-grow max-w-container-max mx-auto w-full px-margin-mobile md:px-margin-desktop py-12">

    <div class="mb-8">
        <h1 class="text-headline-lg font-bold text-on-surface mb-2">学習履歴</h1>
        <p class="text-body-md text-on-surface-variant">タイピング練習の記録一覧</p>
    </div>

    <div class="bg-surface-container-lowest rounded-full shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="py-3 px-6 text-label-md text-on-surface-variant">お題</th>
                        <th class="py-3 px-6 text-label-md text-on-surface-variant">カテゴリ</th>
                        <th class="py-3 px-6 text-label-md text-on-surface-variant text-right">WPM</th>
                        <th class="py-3 px-6 text-label-md text-on-surface-variant text-right">正答率</th>
                        <th class="py-3 px-6 text-label-md text-on-surface-variant text-right">日時</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/50">
                    @forelse($records as $record)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="py-3 px-6 text-body-md text-on-surface">{{ $record->practice->title ?? '-' }}</td>
                        <td class="py-3 px-6 text-caption text-on-surface-variant">{{ $record->practice->category->name ?? '-' }}</td>
                        <td class="py-3 px-6 text-right text-body-md text-on-surface">{{ $record->wpm }}</td>
                        <td class="py-3 px-6 text-right text-body-md text-on-surface">{{ $record->accuracy }}%</td>
                        <td class="py-3 px-6 text-right text-caption text-on-surface-variant">{{ $record->created_at->format('Y/m/d H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-on-surface-variant text-body-md">まだ学習履歴がありません</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-primary text-on-primary rounded-full inline-block hover:opacity-90 transition-all">
            ダッシュボードに戻る
        </a>
    </div>

</main>
@endsection
