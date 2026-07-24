@props(['icon' => 'star', 'title' => '', 'description' => '', 'href' => '#', 'progress' => null, 'badge' => null, 'actionLabel' => null])

<a href="{{ $href }}" class="bg-surface-container-lowest rounded-[0.75rem] shadow-sm hover:shadow-md transition-all p-6 flex flex-col gap-3 group no-underline text-inherit">
    <div class="flex items-center gap-3">
        <div class="w-11 h-11 shrink-0 bg-primary/10 rounded-[0.75rem] flex items-center justify-center">
            <span class="material-symbols-outlined text-primary text-xl">{{ $icon }}</span>
        </div>
        <h3 class="font-headline-md text-base text-on-surface font-bold">{{ $title }}</h3>
        @if($badge)
        <span class="ml-auto shrink-0 bg-primary text-on-primary text-[10px] font-bold px-2.5 py-1 rounded-[0.75rem] uppercase tracking-wider">{{ $badge }}</span>
        @endif
    </div>

    <p class="text-caption text-on-surface-variant">{{ $description }}</p>

    @if($progress !== null)
    <div class="mt-auto pt-1">
        <div class="flex justify-between text-caption text-on-surface-variant mb-1">
            <span>進捗</span>
            <span>{{ $progress }}%</span>
        </div>
        <div class="w-full bg-surface-container-high rounded-[0.75rem] h-1.5 overflow-hidden">
            <div class="bg-primary h-full rounded-[0.75rem]" style="width: {{ $progress }}%"></div>
        </div>
    </div>
    @endif

    <div class="flex items-center gap-1 text-primary text-label-md font-semibold {{ $progress === null ? 'mt-auto' : '' }} group-hover:gap-2 transition-all">
        <span>{{ $actionLabel ?? ($progress !== null ? '学習を開始する' : '詳しく見る') }}</span>
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
    </div>
</a>
