@props(['level' => 1, 'currentXp' => 0, 'nextXp' => 100])

@php
    $percent = $nextXp > 0 ? min(round(($currentXp / $nextXp) * 100), 100) : 0;
@endphp

<div class="w-full">
    <div class="flex items-center justify-between mb-1.5">
        <span class="inline-flex items-center gap-1.5 text-label-md font-bold text-primary">
            <span class="material-symbols-outlined text-base">military_tech</span>
            Level {{ $level }}
        </span>
        <span class="text-caption text-on-surface-variant font-semibold">{{ number_format($currentXp) }} / {{ number_format($nextXp) }} XP</span>
    </div>
    <div class="w-full bg-surface-container-high rounded-full h-3.5 overflow-hidden ring-1 ring-outline-variant/40">
        <div class="bg-primary h-full rounded-full transition-all duration-700 ease-out"
             style="width: {{ $percent }}%"></div>
    </div>
    <p class="text-caption text-on-surface-variant/80 mt-1.5 text-right">Level {{ $level + 1 }} まで {{ 100 - $percent }}%</p>
</div>
