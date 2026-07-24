@props(['percent' => 0, 'label' => '', 'current' => null, 'total' => null])

<div class="w-full">
    @if($label || ($current !== null && $total !== null))
    <div class="flex items-center justify-between mb-1">
        @if($label)
        <span class="text-caption text-on-surface-variant font-semibold">{{ $label }}</span>
        @endif
        @if($current !== null && $total !== null)
        <span class="text-caption text-on-surface-variant">{{ $current }} / {{ $total }}</span>
        @endif
    </div>
    @endif
    <div class="w-full bg-surface-container-high rounded-[0.75rem] h-2 overflow-hidden">
        <div class="bg-primary h-full rounded-[0.75rem] transition-all duration-500"
             style="width: {{ min(max((int)$percent, 0), 100) }}%"></div>
    </div>
</div>
