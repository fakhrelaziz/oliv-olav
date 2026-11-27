<!-- SECTION 2 - ABOUT & MEMPELAI -->
<section id="about" class="relative w-full overflow-hidden bg-moss text-warmwhite">
    <!-- Top Quote Area -->
    <div class="relative w-full min-h-[75vh] md:min-h-[85vh] flex flex-col justify-center px-6 md:px-16 lg:px-24 py-20 md:py-28 overflow-hidden">
        <!-- Right Collage Area -->
        <div class="relative md:absolute md:right-0 md:top-0 w-full md:w-[52%] h-[420px] sm:h-[500px] md:h-full z-[1] mt-10 md:mt-0 order-2 md:order-none">
            <div class="absolute right-0 top-6 md:top-14 bottom-0 w-[88%] md:w-[85%] overflow-hidden">
                <img id="about-photo-couple" src="{{ $about['photo_couple'] }}" data-fallback="/aset/section2_fotopengantin.webp" alt="{{ $about['groom_nickname'] }} dan {{ $about['bride_nickname'] }} berpegangan tangan" class="w-full h-full object-cover object-center opacity-85" loading="lazy" decoding="async" />
                <div class="absolute inset-0 bg-moss/25 mix-blend-multiply"></div>
            </div>

            <div class="absolute left-[10%] md:left-[12%] top-2 md:top-6 bottom-0 w-28 sm:w-36 md:w-44 bg-[#9ca395]/25 backdrop-blur-[1px] pointer-events-none z-[2]"></div>
            <div class="absolute left-[4%] md:left-[6%] top-16 md:top-24 bottom-12 w-10 md:w-14 bg-[#9ca395]/15 pointer-events-none z-[2]"></div>

            <div class="absolute left-[3%] md:left-[5%] top-12 md:top-16 w-24 sm:w-28 md:w-36 z-[3] pointer-events-none">
                <img src="{{ asset('aset/section2_bunga.webp') }}" alt="Flower divider" class="w-full h-auto drop-shadow-lg" loading="lazy" />
            </div>

            <div class="absolute right-6 md:right-12 top-8 md:top-12 z-[4] font-script text-3xl sm:text-4xl md:text-5xl rotate-[-4deg] text-warmwhite drop-shadow-lg leading-[0.95]">
                Some<br>People<br>Brighter<br>Days <span class="text-lg align-bottom">&hearts;</span>
            </div>

            <div class="absolute right-2 sm:right-6 md:right-8 -bottom-2 md:bottom-0 z-[4] w-48 sm:w-56 md:w-64 lg:w-72 drop-shadow-2xl">
                <img src="{{ asset('aset/section2_tulisan_bg.webp') }}" alt="Terima kasih telah menjadi bagian dari kisah indah ini" class="w-full h-auto" loading="lazy" />
            </div>
        </div>

        <!-- Left content -->
        <div class="relative z-[3] max-w-xl md:max-w-md lg:max-w-lg order-1 md:order-none">
            @php
                $headlineLines = preg_split('/\r\n|\r|\n/', (string) $about['headline']);
                $lastLine = count($headlineLines) > 1 ? array_pop($headlineLines) : null;
            @endphp
            <h2 id="about-headline" class="font-display text-5xl sm:text-6xl md:text-7xl mb-8 leading-[1.08]">
                {!! implode('<br>', array_map('e', $headlineLines)) !!}@if($lastLine)<br><span class="italic text-5xl sm:text-6xl md:text-7xl text-warmwhite/85">{{ $lastLine }}</span>@endif
            </h2>
            <p id="about-intro-1" class="mb-6 text-sm md:text-base leading-relaxed text-warmwhite/90 max-w-md">
                {{ $about['intro_1'] }}
            </p>
            <p id="about-intro-2" class="text-sm md:text-base leading-relaxed text-warmwhite/90 max-w-md">
                {{ $about['intro_2'] }}
            </p>
        </div>
    </div>

    <!-- Profile Area (Mempelai Pria & Wanita) -->
    <div class="relative w-full bg-paper text-moss px-6 sm:px-10 md:px-16 lg:px-20 py-20 md:py-28 overflow-hidden">
        <img src="{{ asset('aset/section2_bunga(2).webp') }}" class="absolute top-2 left-0 w-32 sm:w-40 md:w-48 opacity-40 pointer-events-none -translate-x-4 z-0" alt="" loading="lazy" />
        <img src="{{ asset('aset/section2_bunga(3).webp') }}" class="absolute bottom-0 right-0 w-28 sm:w-36 md:w-44 opacity-95 pointer-events-none translate-x-2 translate-y-2 z-20" alt="" loading="lazy" />

        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-11 gap-16 lg:gap-4 items-center relative z-10">
            <!-- Groom Column -->
            <div class="lg:col-span-5 relative flex flex-col items-center lg:items-start">
                <div class="relative w-full max-w-[360px] sm:max-w-[400px] lg:max-w-none flex flex-col">
                    <div class="relative w-60 sm:w-68 md:w-72 h-80 sm:h-96 self-center lg:self-end lg:mr-4">
                        <div class="absolute -top-3 -right-3 w-full h-full bg-moss/30 z-0"></div>
                        <img id="about-groom-photo" src="{{ $about['groom_photo'] }}" data-fallback="/aset/section2_fotopengantin(2).webp" alt="{{ $about['groom_nickname'] }}" class="w-full h-full object-cover object-top relative z-10 shadow-xl" loading="lazy" decoding="async" />
                    </div>

                    <div class="relative lg:absolute lg:left-0 lg:bottom-8 z-20 mt-6 lg:mt-0 text-center lg:text-left bg-paper/85 lg:bg-transparent backdrop-blur-[2px] lg:backdrop-blur-none p-4 lg:p-0 rounded-xl lg:rounded-none">
                        <p class="text-[10px] uppercase tracking-[0.28em] mb-2 font-semibold text-moss/80">MEMPELAI PRIA</p>
                        <h3 id="front-groom-name" class="font-display text-4xl sm:text-5xl mb-3 text-moss leading-none drop-shadow-sm">{{ $about['groom_nickname'] }}</h3>
                        <p id="front-groom-fullname" class="font-display text-2xl sm:text-3xl text-moss/85 mb-4">{{ $about['groom_fullname'] }}</p>
                        <p id="front-groom-parents" class="text-xs sm:text-sm leading-relaxed text-moss/90 font-medium">{!! nl2br(e($about['groom_parents'])) !!}</p>
                    </div>

                    <div class="flex items-center justify-center lg:justify-start gap-4 mt-6 lg:mt-8 text-moss/80">
                        <a href="#about" aria-label="Profil {{ $about['groom_nickname'] }}" class="hover:text-moss transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <circle cx="12" cy="8" r="3.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 20a6.5 6.5 0 0113 0H5.5z" />
                            </svg>
                        </a>
                        @if (filled($about['groom_instagram'] ?? null))
                            <span class="w-px h-4 bg-moss/30"></span>
                            <a id="front-groom-instagram" href="{{ $about['groom_instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram {{ $about['groom_nickname'] }}" class="hover:text-moss transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <rect x="3.5" y="3.5" width="17" height="17" rx="4.5" />
                                    <circle cx="12" cy="12" r="4" />
                                    <circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Center Botanical Ampersand -->
            <div class="lg:col-span-1 flex justify-center items-center my-2 lg:my-0">
                <img src="{{ asset('aset/section2_ampersand_ornamen.webp') }}" alt="&amp;" class="w-24 sm:w-28 lg:w-32 h-auto select-none pointer-events-none" loading="lazy" />
            </div>

            <!-- Bride Column -->
            <div class="lg:col-span-5 relative flex flex-col items-center lg:items-end">
                <div class="relative w-full max-w-[360px] sm:max-w-[400px] lg:max-w-none flex flex-col">
                    <div class="relative w-60 sm:w-68 md:w-72 h-80 sm:h-96 self-center lg:self-start lg:ml-6">
                        <div class="absolute -top-5 left-16 w-[80%] h-[70%] bg-moss/35 z-0"></div>
                        <div class="absolute top-0 -right-12 sm:-right-20 w-36 sm:w-44 h-28 sm:h-32 bg-[#9aa091]/45 z-20 flex items-center justify-center overflow-hidden pointer-events-none">
                            <img src="{{ asset('aset/section5_bunga(2).webp') }}" alt="" class="w-28 opacity-25 rotate-[115deg] grayscale" loading="lazy" />
                        </div>
                        <img id="about-bride-photo" src="{{ $about['bride_photo'] }}" data-fallback="/aset/section2_fotopengantin(3).webp" alt="{{ $about['bride_nickname'] }}" class="w-full h-full object-cover object-top relative z-10 shadow-xl" loading="lazy" decoding="async" />
                    </div>

                    <div class="relative lg:absolute lg:right-0 lg:bottom-8 z-20 mt-6 lg:mt-0 text-center lg:text-left bg-paper/85 lg:bg-transparent backdrop-blur-[2px] lg:backdrop-blur-none p-4 lg:p-0 rounded-xl lg:rounded-none lg:max-w-[190px]">
                        <p class="text-[10px] uppercase tracking-[0.28em] mb-2 font-semibold text-moss/80">MEMPELAI WANITA</p>
                        <h3 id="front-bride-name" class="font-display text-4xl sm:text-5xl mb-3 text-moss leading-none drop-shadow-sm">{{ $about['bride_nickname'] }}</h3>
                        <p id="front-bride-fullname" class="font-display text-2xl sm:text-3xl text-moss/85 mb-4">{{ $about['bride_fullname'] }}</p>
                        <p id="front-bride-parents" class="text-xs sm:text-sm leading-relaxed text-moss/90 font-medium">{!! nl2br(e($about['bride_parents'])) !!}</p>
                    </div>

                    <div class="flex items-center justify-center lg:justify-end gap-4 mt-6 lg:mt-8 lg:pr-12 text-moss/80">
                        <a href="#about" aria-label="Profil {{ $about['bride_nickname'] }}" class="hover:text-moss transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <circle cx="12" cy="8" r="3.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 20a6.5 6.5 0 0113 0H5.5z" />
                            </svg>
                        </a>
                        @if (filled($about['bride_instagram'] ?? null))
                            <span class="w-px h-4 bg-moss/30"></span>
                            <a id="front-bride-instagram" href="{{ $about['bride_instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram {{ $about['bride_nickname'] }}" class="hover:text-moss transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <rect x="3.5" y="3.5" width="17" height="17" rx="4.5" />
                                    <circle cx="12" cy="12" r="4" />
                                    <circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-invitation.quote-banner
        variant="about"
        :image="asset('aset/section2_bagianbawah.webp')"
        imageAlt="Background leaves"
        :quote="$about['bottom_quote']"
        quoteId="about-bottom-quote"
    />
</section>
