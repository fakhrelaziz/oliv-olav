<!-- SECTION 3 - OUR STORY -->
@php
    $defaultStoryImgs = [
        '/aset/section3_fotopengantin(2).webp',
        '/aset/section3_fotopengantin(3).webp',
        '/aset/section3_fotopengantin(5).webp',
        '/aset/section3_fotopengantin(6).webp',
        '/aset/section3_fotopengantin(4).webp',
    ];
@endphp

<section id="story" class="relative w-full bg-paper text-moss overflow-hidden">
    <!-- Top Title Area (Luxury Editorial Scrapbook + Fine Paper Grain) -->
    <div class="story-album-surface bg-moss text-warmwhite px-6 md:px-16 lg:px-24 pt-14 sm:pt-20 md:pt-24 pb-10 sm:pb-14 md:pb-20 flex flex-col lg:flex-row justify-between items-center relative overflow-visible">
        <div class="relative z-20 w-full lg:w-[40%] flex flex-col justify-center mb-0">
            <p class="text-[10px] md:text-xs uppercase tracking-[0.3em] mb-2.5 md:mb-4 text-warmwhite/70 font-semibold">OUR STORY</p>
            <h2 id="story-headline" class="font-display text-[2.55rem] sm:text-5xl md:text-6xl lg:text-7xl mb-4 md:mb-6 leading-[1.02]">{!! nl2br(e($story['headline'])) !!}</h2>
            <div class="w-12 h-px bg-warmwhite/50 mb-4 md:mb-6"></div>
            <p id="story-intro" class="text-[13.5px] sm:text-sm md:text-base leading-relaxed text-warmwhite/90 max-w-sm">
                {{ $story['intro'] }}
            </p>
        </div>

        <div class="relative z-20 w-full max-w-[350px] sm:max-w-[430px] mx-auto lg:max-w-none lg:mx-0 lg:w-[60%] mt-9 sm:mt-10 lg:mt-0 pb-14 sm:pb-16 lg:pb-0 lg:h-[500px] lg:flex lg:items-center lg:justify-end">
            <div class="relative w-[78%] sm:w-[72%] ml-0 mr-auto lg:w-[58%] lg:max-w-[420px] lg:mr-0 lg:-left-20 z-10 drop-shadow-2xl">
                <img id="story-photo-hero" src="{{ $story['photo_hero'] }}" data-fallback="/aset/section3_fotopengantin.webp" class="w-full h-auto object-contain" alt="Fakhrel &amp; Olivia dari belakang" loading="lazy" decoding="async" />
            </div>
            <div class="absolute right-1 sm:right-2 lg:right-4 -bottom-6 sm:-bottom-8 lg:-bottom-16 w-[49%] sm:w-[46%] lg:w-[42%] max-w-[205px] sm:max-w-[235px] lg:max-w-[300px] z-30 drop-shadow-2xl">
                <img src="{{ asset('aset/section3(01).webp') }}" class="w-full h-auto object-contain" alt="Polaroid collage" loading="lazy" />
            </div>
        </div>
    </div>

    <!-- Timeline Area -->
    <div class="px-6 sm:px-10 md:px-16 lg:px-20 pt-14 pb-16 sm:pt-20 sm:pb-24 md:py-28 max-w-7xl mx-auto relative overflow-hidden">
        <div class="lg:hidden flex items-center gap-3 mb-8 relative z-10">
            <span class="w-8 h-px bg-moss/40" aria-hidden="true"></span>
            <span class="font-script text-2xl text-moss/85 leading-none">Jejak Langkah Kami</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-6 items-start relative z-10">
            <!-- Left Column on Desktop: 3 Polaroid Photos -->
            <div class="hidden lg:flex lg:col-span-4 flex-col items-center justify-between space-y-10 pr-4">
                @foreach ([0, 1, 2] as $slotIdx)
                    @php
                        $item = $stories->get($slotIdx);
                        $fallback = $defaultStoryImgs[$slotIdx];
                        $src = $item ? \App\Services\MediaStorageService::normalizeAssetUrl($item->foto_url, $fallback) : $fallback;
                    @endphp
                    @if ($item)
                        @if ($slotIdx === 0)
                            <div class="relative w-64 xl:w-72" id="story-polaroid-wrap-1">
                                <img src="{{ asset('aset/section3_bunga.webp') }}" class="absolute -left-10 bottom-2 w-24 h-auto z-20 pointer-events-none drop-shadow-md" alt="" loading="lazy" />
                                <img id="story-polaroid-1" src="{{ $src }}" data-fallback="{{ $fallback }}" alt="{{ $item->judul }}" class="w-full h-auto drop-shadow-xl relative z-10" loading="lazy" decoding="async" />
                            </div>
                        @elseif ($slotIdx === 1)
                            <div class="relative w-64 xl:w-72 -ml-4" id="story-polaroid-wrap-2">
                                <img id="story-polaroid-2" src="{{ $src }}" data-fallback="{{ $fallback }}" alt="{{ $item->judul }}" class="w-full h-auto drop-shadow-xl" loading="lazy" decoding="async" />
                            </div>
                        @else
                            <div class="relative w-60 xl:w-64 ml-6 pt-2" id="story-polaroid-wrap-3">
                                <div class="absolute -left-24 bottom-6 z-20 font-script text-2xl xl:text-3xl text-moss/85 -rotate-[12deg] leading-tight text-center pointer-events-none">
                                    Forever and<br>Always<br><span class="text-lg">&hearts;</span>
                                </div>
                                <img id="story-polaroid-3" src="{{ $src }}" data-fallback="{{ $fallback }}" alt="{{ $item->judul }}" class="w-full h-auto drop-shadow-xl relative z-10" loading="lazy" decoding="async" />
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>

            <!-- Center Column: Vertical Timeline -->
            <div class="lg:col-span-5 relative pl-8 sm:pl-12 lg:pl-10">
                <div class="absolute left-[7px] sm:left-[11px] lg:left-[7px] top-3 bottom-6 w-px bg-moss/35"></div>

                <div class="space-y-12 md:space-y-14" id="story-timeline-container">
                    @forelse ($stories as $idx => $st)
                        @php
                            $fallback = $defaultStoryImgs[$idx % count($defaultStoryImgs)];
                            $imgSrc = \App\Services\MediaStorageService::normalizeAssetUrl($st->foto_url, $fallback);
                        @endphp
                        <div class="relative">
                            <div class="absolute -left-[32px] sm:-left-[44px] lg:-left-[40px] top-1.5 w-3.5 h-3.5 rounded-full border-[1.5px] border-moss bg-paper z-10"></div>
                            <p class="text-xs sm:text-sm text-moss/75 mb-1 font-serif">{{ $st->tanggal_momen }}</p>
                            <h3 class="font-display text-2xl sm:text-3xl font-semibold mb-2.5 text-moss">{{ $st->judul }}</h3>
                            <p class="text-xs sm:text-sm leading-relaxed text-moss/80 max-w-md">{{ $st->deskripsi }}</p>
                            @if ($st->foto_url)
                                <div class="mt-5 lg:hidden w-52 sm:w-60">
                                    <img src="{{ $imgSrc }}" data-fallback="{{ $fallback }}" alt="{{ $st->judul }}" class="w-full h-auto drop-shadow-lg story-mobile-img" loading="lazy" decoding="async" />
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs sm:text-sm text-moss/70">Belum ada kisah perjalanan yang ditambahkan.</p>
                    @endforelse
                </div>
            </div>

            <!-- Right Column on Desktop: 2 Polaroid Photos -->
            <div class="hidden lg:flex lg:col-span-3 flex-col items-center justify-center space-y-16 pt-28">
                @foreach ([3, 4] as $slotIdx)
                    @php
                        $item = $stories->get($slotIdx);
                        $fallback = $defaultStoryImgs[$slotIdx];
                        $src = $item ? \App\Services\MediaStorageService::normalizeAssetUrl($item->foto_url, $fallback) : $fallback;
                    @endphp
                    @if ($item)
                        @if ($slotIdx === 3)
                            <div class="relative w-48 xl:w-56" id="story-polaroid-wrap-4">
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-12 h-5 bg-[#d8d3c4]/70 rotate-2 z-20 shadow-sm"></div>
                                <img id="story-polaroid-4" src="{{ $src }}" data-fallback="{{ $fallback }}" alt="{{ $item->judul }}" class="w-full h-auto drop-shadow-xl relative z-10" loading="lazy" decoding="async" />
                                <img src="{{ asset('aset/section5_bunga(2).webp') }}" class="absolute -right-12 -bottom-6 w-28 h-auto z-0 pointer-events-none opacity-90" alt="" loading="lazy" />
                            </div>
                        @else
                            <div class="relative w-52 xl:w-60 ml-4" id="story-polaroid-wrap-5">
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-12 h-5 bg-[#d8d3c4]/70 -rotate-1 z-20 shadow-sm"></div>
                                <img id="story-polaroid-5" src="{{ $src }}" data-fallback="{{ $fallback }}" alt="{{ $item->judul }}" class="w-full h-auto drop-shadow-xl relative z-10" loading="lazy" decoding="async" />
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <x-invitation.quote-banner
        variant="story"
        :image="asset('aset/section3_bagianbawah.webp')"
        imageAlt="White flowers"
        :quote="$story['bottom_quote']"
        quoteId="story-bottom-quote"
    />
</section>
