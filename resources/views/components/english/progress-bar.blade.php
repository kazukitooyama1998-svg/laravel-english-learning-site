@props(['percent' => 0, 'label' => '', 'current' => null, 'total' => null])

<div class="w-full">
    @if($label || ($current !== null && $total !== null))
    <div class="flex items-center justify-between mb-1.5">
        @if($label)
        <span class="text-caption text-on-surface-variant font-bold">{{ $label }}</span>
        @endif
        @if($current !== null && $total !== null)
        <span class="text-caption text-on-surface-variant font-semibold">{{ $current }} / {{ $total }}</span>
        @endif
    </div>
    @endif
    <div class="w-full bg-surface-container-high rounded-full h-2.5 overflow-hidden ring-1 ring-outline-variant/40">
        <div class="bg-primary h-full rounded-full transition-all duration-500 ease-out"
             style="width: {{ min(max((int)$percent, 0), 100) }}%"></div>
    </div>
</div>
