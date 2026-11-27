<!-- OPENING COVER (LUXURY BOTANICAL EDITORIAL GATE) -->
<div id="opening-cover" class="opening-cover" role="dialog" aria-modal="true" aria-label="Cover Undangan Pernikahan {{ $hero['groom_nickname'] }} dan {{ $hero['bride_nickname'] }}">
    <!-- Layer 1: Curated 4-Slide Couple Photo & Video Stage -->
    <div class="opening-cover-stage" aria-hidden="true">
        <div class="opening-cover-slide is-active" data-cover-slide="0">
            <picture>
                <source id="cover-slide0-source" media="(min-width: 768px)" srcset="{{ $hero['bg_desktop'] }}" />
                <img id="cover-slide0-img" src="{{ $hero['bg_mobile'] }}" alt="" class="opening-cover-media opening-cover-media-s1" />
            </picture>
        </div>
        <div class="opening-cover-slide" data-cover-slide="1">
            <picture>
                <source media="(min-width: 768px)" srcset="{{ asset('aset/section5_fotopengantin.webp') }}" />
                <img src="{{ asset('aset/foto_hd_soft.webp') }}" alt="" class="opening-cover-media opening-cover-media-s2" />
            </picture>
        </div>
        <div class="opening-cover-slide" data-cover-slide="2">
            <picture>
                <source media="(min-width: 768px)" srcset="{{ asset('aset/section5_galerifoto(5).webp') }}" />
                <img src="{{ asset('aset/pohon_gandengan_hd.webp') }}" alt="" class="opening-cover-media opening-cover-media-s3" />
            </picture>
        </div>
        <div class="opening-cover-slide" data-cover-slide="3">
            <video id="opening-cover-video" class="opening-cover-media opening-cover-media-s5" src="{{ asset('aset/vid_1.mp4') }}" poster="{{ asset('aset/cover_hd.webp') }}" muted loop playsinline preload="metadata"></video>
        </div>
    </div>

    <!-- Layer 2: Selective Editorial Gradient, Drapery, Frame & Botanical Ornaments -->
    <div class="opening-cover-overlay" aria-hidden="true">
        <div class="opening-cover-drapes">
            <img src="{{ asset('aset/cover_drape_top.webp') }}" alt="" class="opening-cover-drape-top" />
            <img src="{{ asset('aset/cover_drape_sheer.webp') }}" alt="" class="opening-cover-drape opening-cover-drape-left" />
            <img src="{{ asset('aset/cover_drape_sheer.webp') }}" alt="" class="opening-cover-drape opening-cover-drape-right" />
            <img src="{{ asset('aset/cover_floral_left.webp') }}" alt="" class="opening-cover-floral opening-cover-floral-left" />
            <img src="{{ asset('aset/cover_floral_right.webp') }}" alt="" class="opening-cover-floral opening-cover-floral-right" />
        </div>
        <div class="opening-cover-frame">
            <img src="{{ asset('aset/section3_bunga.webp') }}" alt="" class="opening-cover-ornament opening-cover-ornament-bl hidden md:block" />
            <img src="{{ asset('aset/section5_bunga(2).webp') }}" alt="" class="opening-cover-ornament opening-cover-ornament-tr hidden md:block" />
        </div>
    </div>

    <!-- Layer 3: Editorial Content & Cardless Guest Personalization -->
    <div class="opening-cover-content">
        <div class="opening-cover-topbar">
            <span class="font-display text-lg sm:text-2xl uppercase tracking-[0.28em] text-warmwhite/95">REL|LIV</span>
            <span class="opening-cover-topbar-line" aria-hidden="true"></span>
            <span id="cover-city" class="text-[9px] sm:text-xs uppercase tracking-[0.28em] text-warmwhite/70">{{ $hero['city'] }}</span>
        </div>

        <div class="opening-cover-center">
            <div class="flex items-center justify-center gap-2.5 sm:gap-4 mb-2 sm:mb-3">
                <span class="h-px w-6 sm:w-12 bg-warmwhite/50"></span>
                <p id="cover-tagline" class="text-[9px] sm:text-xs uppercase tracking-[0.34em] text-warmwhite/85">{{ $hero['tagline'] }}</p>
                <span class="h-px w-6 sm:w-12 bg-warmwhite/50"></span>
            </div>

            <h1 class="opening-cover-title font-display text-warmwhite font-light">
                <span id="cover-groom-name">{{ $hero['groom_nickname'] }}</span>
                <span class="opening-cover-and font-script italic lowercase">and</span>
                <span id="cover-bride-name">{{ $hero['bride_nickname'] }}</span>
            </h1>

            <p id="cover-wedding-date" class="mt-2 sm:mt-3.5 text-[10px] sm:text-xs uppercase tracking-[0.32em] text-warmwhite/85">
                {{ $hero['wedding_date'] }}
            </p>
        </div>

        <div class="opening-cover-bottom">
            <div class="opening-cover-guest">
                <p class="opening-cover-salutation">
                    <span class="font-display italic text-sm sm:text-base text-warmwhite/85">Kepada Yth.</span>
                    <span class="text-[9px] sm:text-[10px] uppercase tracking-[0.22em] text-warmwhite/60 ml-1.5">Bapak / Ibu / Saudara / i</span>
                </p>
                <p id="cover-nama-tamu" class="font-display text-[1.8rem] sm:text-3xl md:text-4xl text-warmwhite font-normal leading-tight mt-1 mb-2">
                    {{ $guestName ?: 'Tamu Undangan' }}
                </p>
                <p id="cover-salam-text" class="text-[11px] sm:text-xs text-warmwhite/75 max-w-[19rem] sm:max-w-sm mx-auto leading-relaxed mb-4 sm:mb-5">
                    {{ $hero['salam_text'] }}
                </p>

                <button type="button" id="btn-open-invitation" class="opening-cover-btn group">
                    <span>Buka Undangan</span>
                    <svg class="w-3.5 h-3.5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.35" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5" />
                    </svg>
                </button>
            </div>

            <div class="opening-cover-dots" role="tablist" aria-label="Slide Foto Cover">
                <button type="button" class="opening-cover-dot is-active" data-cover-dot="0" aria-label="Slide 1"></button>
                <button type="button" class="opening-cover-dot" data-cover-dot="1" aria-label="Slide 2"></button>
                <button type="button" class="opening-cover-dot" data-cover-dot="2" aria-label="Slide 3"></button>
                <button type="button" class="opening-cover-dot" data-cover-dot="3" aria-label="Slide 4 Video"></button>
            </div>
        </div>
    </div>
</div>
