@props([
    'type' => 'akad',
    'name',
    'date',
    'time',
    'place',
    'address',
    'photo',
    'photoFallback',
    'prefix' => 'event-akad',
])

<div class="border border-moss/35 p-6 sm:p-8 bg-paper/60 grid grid-cols-1 sm:grid-cols-12 gap-6 sm:gap-8 items-center relative">
    <!-- Left Arch Image -->
    <div class="sm:col-span-6 flex justify-center">
        <div class="w-full max-w-[210px] sm:max-w-[230px] aspect-[3/4.5] rounded-t-full overflow-hidden shadow-md border-2 border-white">
            <img id="{{ $prefix }}-photo" src="{{ $photo }}" data-fallback="{{ $photoFallback }}" alt="{{ strip_tags($name) }}" class="w-full h-full object-cover object-center" loading="lazy" decoding="async" />
        </div>
    </div>

    <!-- Right Details -->
    <div class="sm:col-span-6 flex flex-col items-start text-left">
        <div class="w-14 h-14 border border-moss/70 rounded-full flex items-center justify-center mb-5">
            @if ($type === 'akad')
                <svg class="w-7 h-7 text-moss" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <circle cx="9.5" cy="13.5" r="4.5" />
                    <circle cx="14.5" cy="13.5" r="4.5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 6.5l1.5-2 1.5 2-1.5 2-1.5-2z" />
                </svg>
            @else
                <svg class="w-7 h-7 text-moss" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 3h4l-1 7a2.5 2.5 0 01-5 0L7 3h1zm8 0h4l1 7a2.5 2.5 0 01-5 0l-1-7h1zM8.5 12.5L7 19m-2 0h4m6.5-6.5L17 19m-2 0h4M12 2v2" />
                </svg>
            @endif
        </div>

        <h3 id="{{ $prefix }}-name" class="font-display text-3xl sm:text-4xl mb-3 leading-[1.05] text-moss">
            {!! nl2br(e($name)) !!}
        </h3>
        <div class="w-8 h-px bg-moss/50 mb-5"></div>

        <div class="w-full space-y-3.5 text-moss/90">
            <div class="flex items-center gap-3.5">
                <svg class="w-4 h-4 text-moss shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <rect x="3" y="4" width="18" height="17" rx="2" />
                    <path stroke-linecap="round" d="M16 2v4M8 2v4M3 9h18" />
                </svg>
                <p id="{{ $prefix }}-date" class="text-xs sm:text-sm font-medium">{{ $date }}</p>
            </div>
            <div class="flex items-center gap-3.5">
                <svg class="w-4 h-4 text-moss shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                </svg>
                <p id="{{ $prefix }}-time" class="text-xs sm:text-sm font-medium">{{ $time }}</p>
            </div>
            <div class="flex items-start gap-3.5">
                <svg class="w-4 h-4 text-moss shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-5.5-7-11a7 7 0 1114 0c0 5.5-7 11-7 11z" />
                    <circle cx="12" cy="10" r="2.5" />
                </svg>
                <div>
                    <p id="{{ $prefix }}-place" class="text-xs sm:text-sm font-medium">{{ $place }}</p>
                    <p id="{{ $prefix }}-address" class="text-xs text-moss/75 leading-relaxed">{!! nl2br(e($address)) !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>
