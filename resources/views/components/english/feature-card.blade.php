@props(['icon' => 'star', 'title' => '', 'description' => '', 'href' => '#', 'progress' => null, 'badge' => null, 'actionLabel' => null, 'tone' => null])

@php
    // rotate through the calm accent set
    $tones = ['primary', 'secondary', 'tertiary'];
    $t     = $tone ?: $tones[abs(crc32($title)) % count($tones)];
@endphp

<a href="{{ $href }}"
   class="biscuit-card reveal group relative overflow-hidden p-6 flex flex-col gap-3 no-underline text-inherit">

    {{-- thin top accent rule (Instagram-card style) --}}
    <span class="pointer-events-none absolute inset-x-0 top-0 h-1 bg-{{ $t }}"></span>

    <div class="flex items-center gap-3 pt-1">
        <span class="grid place-items-center w-11 h-11 shrink-0 rounded-xl bg-{{ $t }}-container text-{{ $t }} hover-hop">
            <span class="material-symbols-outlined text-[21px]">{{ $icon }}</span>
        </span>
        <h3 class="font-display text-base text-on-surface font-bold leading-tight">{{ $title }}</h3>
        @if($badge)
        <span class="ml-auto shrink-0 bg-{{ $t }} text-on-{{ $t }} text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">{{ $badge }}</span>
        @endif
    </div>

    <p class="text-caption text-on-surface-variant leading-relaxed">{{ $description }}</p>

    @if($progress !== null)
    <div class="mt-auto pt-1">
        <div class="flex justify-between text-caption text-on-surface-variant mb-1">
            <span class="font-semibold">進捗</span>
            <span class="font-bold text-{{ $t }}">{{ $progress }}%</span>
        </div>
        <div class="w-full bg-surface-container-high rounded-full h-2 overflow-hidden">
            <div class="bg-{{ $t }} h-full rounded-full transition-all duration-700" style="width: {{ $progress }}%"></div>
        </div>
    </div>
    @endif

    <div class="flex items-center gap-1 text-{{ $t }} text-label-md font-bold {{ $progress === null ? 'mt-auto' : '' }} group-hover:gap-2.5 transition-all">
        <span>{{ $actionLabel ?? ($progress !== null ? '学習を続ける' : '詳しく見る') }}</span>
        <span class="material-symbols-outlined text-base group-hover:translate-x-1 transition-transform">arrow_forward</span>
    </div>
</a>
