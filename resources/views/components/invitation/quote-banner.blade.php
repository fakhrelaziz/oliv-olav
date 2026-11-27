@props([
    'variant' => 'about',
    'image',
    'imageAlt' => 'Background botanical',
    'quote' => '',
    'quoteId' => null,
])

@if ($variant === 'about')
    <div class="relative w-full min-h-[210px] sm:min-h-[250px] md:min-h-[290px] lg:min-h-[340px] py-14 sm:py-16 md:py-20 px-6 flex items-center justify-center text-center overflow-hidden bg-[#222920]">
        <div class="absolute inset-0 z-0 pointer-events-none">
            <img src="{{ $image }}" class="w-full h-full object-cover object-left" alt="{{ $imageAlt }}" loading="lazy" decoding="async" />
            <img src="{{ $image }}" class="absolute inset-y-0 right-0 w-1/2 h-full object-cover object-right" style="-webkit-mask-image: linear-gradient(to left, black 60%, transparent 100%); mask-image: linear-gradient(to left, black 60%, transparent 100%);" alt="" aria-hidden="true" loading="lazy" decoding="async" />
            <div class="absolute inset-0 quote-banner-scrim-about" aria-hidden="true"></div>
        </div>
        <div class="relative z-10 max-w-xl md:max-w-2xl px-4 sm:px-8 text-warmwhite">
            <p @if($quoteId) id="{{ $quoteId }}" @endif class="font-display text-2xl sm:text-3xl md:text-4xl lg:text-5xl leading-tight quote-banner-text-shadow">
                &ldquo;{!! nl2br(e($quote)) !!}&rdquo;
            </p>
            <div class="h-px w-12 md:w-16 bg-warmwhite/80 mx-auto mt-6 md:mt-10"></div>
        </div>
    </div>
@elseif ($variant === 'story')
    <div class="relative w-full py-16 sm:py-24 md:py-32 lg:py-40 px-6 flex flex-col items-center justify-center text-center overflow-hidden bg-moss text-warmwhite">
        <div class="absolute inset-0 z-0 pointer-events-none">
            <img src="{{ $image }}" class="w-full h-full object-cover object-left" alt="{{ $imageAlt }}" loading="lazy" decoding="async" />
            <div class="absolute inset-0 quote-banner-scrim-story" aria-hidden="true"></div>
        </div>
        <div class="relative z-10 w-full max-w-3xl mx-auto flex flex-col items-center justify-center">
            <div class="text-center w-full relative">
                <span class="text-8xl font-display text-warmwhite/45 absolute -top-12 left-4 md:left-0 quote-banner-text-shadow">&ldquo;</span>
                <p @if($quoteId) id="{{ $quoteId }}" @endif class="font-display text-3xl md:text-5xl italic leading-tight text-warmwhite relative z-10 max-w-xl mx-auto quote-banner-text-shadow">
                    {!! nl2br(e($quote)) !!}
                </p>
                <div class="w-16 h-px bg-warmwhite/65 mx-auto mt-10"></div>
            </div>
        </div>
    </div>
@else
    <div class="relative w-full min-h-[190px] sm:min-h-[230px] md:min-h-[270px] lg:min-h-[310px] py-12 sm:py-16 md:py-20 bg-moss overflow-hidden flex items-center">
        <div class="absolute inset-0 z-0 pointer-events-none">
            <img src="{{ $image }}" class="w-full h-full object-cover object-center" alt="{{ $imageAlt }}" loading="lazy" decoding="async" />
            <div class="absolute inset-0 quote-banner-scrim-gallery" aria-hidden="true"></div>
        </div>
        <div class="relative z-10 w-full max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center sm:justify-end px-6 sm:px-12 md:px-20 gap-8">
            <div class="sm:border-l border-warmwhite/50 sm:pl-8 md:pl-12 py-2 flex flex-col items-center justify-center">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif
