@props([
    'label',
    'value' => 0,
    'subtext' => null,
    'icon' => 'users',
    'valueColor' => 'text-inkAdmin',
    'subtextColor' => 'text-inkMuted',
    'percent' => null,
    'barColor' => 'bg-emerald-600',
    'ornament' => null,
    'variant' => 'compact', // 'compact' (4-col) or 'wide' (3-col dashboard)
])

@if ($variant === 'wide')
    <div class="bg-cardBg rounded-3xl p-6 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] flex items-center space-x-5">
        <div class="w-14 h-14 rounded-2xl bg-[#EBEFE8] text-moss flex items-center justify-center flex-shrink-0">
            <i data-lucide="{{ $icon }}" class="w-6 h-6 stroke-[1.8]"></i>
        </div>
        <div>
            <span class="text-xs font-medium text-inkMuted block">{{ $label }}</span>
            <span class="text-2xl font-bold {{ $valueColor }}">{{ $value }}</span>
            @if ($subtext)
                <span class="text-[11px] {{ $subtextColor }} font-medium block mt-0.5">{{ $subtext }}</span>
            @endif
        </div>
    </div>
@else
    <div class="bg-cardBg rounded-3xl p-5 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] relative overflow-hidden">
        <div class="flex items-center space-x-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-[#EBEFE8] text-moss flex items-center justify-center shrink-0">
                <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-medium text-inkMuted">{{ $label }}</span>
        </div>
        <span class="text-2xl font-bold {{ $valueColor }}">{{ $value }}</span>

        @if ($percent !== null)
            <div class="w-full bg-[#EBE9DE] h-1.5 rounded-full overflow-hidden mt-1.5 mb-1">
                <div class="{{ $barColor }} h-full rounded-full transition-all duration-300" style="width: {{ min(100, max(0, (float) $percent)) }}%;"></div>
            </div>
        @endif

        @if ($subtext)
            <span class="text-[10px] {{ $subtextColor }} block mt-0.5">{{ $subtext }}</span>
        @endif

        @if ($ornament)
            <img src="{{ asset($ornament) }}" alt="" class="absolute -bottom-4 -right-4 w-16 opacity-15 pointer-events-none">
        @endif
    </div>
@endif
