@props(['level' => 1, 'currentXp' => 0, 'nextXp' => 100])

@php
    $percent = $nextXp > 0 ? min(round(($currentXp / $nextXp) * 100), 100) : 0;
@endphp

<div class="w-full">
    <div class="flex items-center justify-between mb-1">
        <span class="text-label-md font-bold text-primary">Level {{ $level }}</span>
        <span class="text-caption text-on-surface-variant">{{ number_format($currentXp) }} / {{ number_format($nextXp) }} XP</span>
    </div>
    <div class="w-full bg-surface-container-high rounded-[0.75rem] h-3 overflow-hidden">
        <div class="bg-primary h-full rounded-[0.75rem] transition-all duration-700"
             style="width: {{ $percent }}%"></div>
    </div>
    <p class="text-caption text-on-surface-variant mt-1 text-right">{{ $percent }}% to Level {{ $level + 1 }}</p>
</div>
