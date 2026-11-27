<!-- SECTION 1 - HERO -->
<section id="home" class="relative min-h-[100svh] w-full overflow-hidden bg-moss">
    <picture class="absolute inset-0 z-0">
        <source id="hero-bg-source" media="(min-width: 768px)" srcset="{{ $hero['bg_desktop'] }}">
        <img id="hero-bg-img" src="{{ $hero['bg_mobile'] }}" data-fallback="/aset/background_herosection.webp" alt="{{ $hero['groom_nickname'] }} &amp; {{ $hero['bride_nickname'] }}" class="h-full w-full object-cover object-[50%_22%] md:object-center" />
    </picture>

    <!-- Selective Editorial Gradient Overlay -->
    <div class="absolute inset-0 z-0 pointer-events-none md:hidden" style="background: linear-gradient(180deg, rgba(24,30,19,0.74) 0%, rgba(24,30,19,0.34) 24%, rgba(24,30,19,0.0) 40%, rgba(24,30,19,0.0) 60%, rgba(24,30,19,0.68) 80%, rgba(24,30,19,0.92) 100%);"></div>
    <div class="absolute inset-0 z-0 pointer-events-none hidden md:block" style="background: linear-gradient(90deg, rgba(24,30,19,0.72) 0%, rgba(24,30,19,0.36) 38%, rgba(24,30,19,0.0) 66%), linear-gradient(180deg, rgba(24,30,19,0.42) 0%, rgba(24,30,19,0.0) 28%, rgba(24,30,19,0.0) 74%, rgba(24,30,19,0.74) 100%);"></div>

    <header class="wedding-header absolute top-0 left-0 w-full z-30 px-6 py-5 md:px-16 md:py-8">
        <div class="flex items-center justify-between">
            <a href="#home" class="text-xl sm:text-2xl font-display uppercase tracking-widest text-warmwhite" aria-label="Kembali ke halaman utama">REL|LIV</a>
        </div>

        <x-invitation.floating-nav />
    </header>

    <div class="relative z-10 flex h-full min-h-[100svh] flex-col justify-between md:justify-center pt-[4.5rem] pb-14 sm:pt-20 sm:pb-16 md:pt-24 md:pb-40 lg:pb-44 px-6 md:px-16">
        <div class="max-w-xl">
            <div class="flex items-center gap-3 mb-1.5 md:mb-3">
                <div class="h-px w-8 md:w-12 bg-warmwhite/80"></div>
                <p id="hero-tagline" class="text-[10px] sm:text-xs uppercase tracking-editorial text-warmwhite/90">{{ $hero['tagline'] }}</p>
            </div>

            <h1 id="hero-couple-title" class="font-display text-[3.1rem] sm:text-[3.75rem] md:text-[5rem] lg:text-[5.75rem] leading-[0.86] text-warmwhite font-light">
                <span id="hero-groom-name">{{ $hero['groom_nickname'] }}</span>
                <span class="block font-script text-[1.6rem] sm:text-3xl md:text-4xl lg:text-5xl italic lowercase leading-[0.62] -my-0.5 md:my-0.5 pl-2 md:pl-3 text-warmwhite/95">and</span>
                <span id="hero-bride-name">{{ $hero['bride_nickname'] }}</span>
            </h1>

            <p id="hero-wedding-date" class="mt-2.5 md:mt-4 text-[11px] sm:text-xs md:text-sm uppercase tracking-[0.32em] text-warmwhite/95 font-medium">
                {{ $hero['wedding_date'] }}
            </p>
        </div>

        <div class="max-w-sm md:max-w-md mt-auto md:mt-10 pb-2 md:pb-0">
            <p class="text-[10px] sm:text-xs uppercase tracking-[0.24em] text-warmwhite/75 mb-1">Selamat Datang,</p>
            <p id="nama-tamu" class="text-xl sm:text-2xl md:text-3xl font-display text-warmwhite mb-1.5 leading-tight">
                {{ $guestName ?: 'Bapak/Ibu/Saudara/i' }}
            </p>
            <p id="hero-salam-text" class="text-xs sm:text-sm text-warmwhite/80 max-w-xs sm:max-w-sm mb-4 leading-relaxed">
                {{ $hero['salam_text'] }}
            </p>

            <div class="flex items-center">
                <a href="#countdown" class="inline-flex items-center gap-3 bg-warmwhite text-moss px-6 py-2.5 md:py-3 rounded-full text-xs sm:text-sm md:text-base font-medium hover:bg-white transition shadow-md">
                    <span>Jelajahi Undangan</span>
                    <span aria-hidden="true">&darr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Handwritten Script Accent -->
    <div class="absolute top-[4.4rem] right-5 sm:top-20 sm:right-8 md:top-auto md:bottom-24 md:right-28 lg:right-36 z-10 text-right rotate-[-7deg] md:rotate-[-10deg] pointer-events-none select-none">
        <p id="hero-script-text" class="font-script text-[1.4rem] sm:text-2xl md:text-4xl lg:text-5xl leading-[0.94] text-warmwhite/75 md:text-warmwhite/90 drop-shadow-sm">{!! nl2br(e($hero['script_text'])) !!}</p>
    </div>

    <picture class="absolute -bottom-px left-0 z-[5] pointer-events-none w-[72%] sm:w-[56%] md:w-[44%] lg:w-[42%] max-w-[600px] opacity-90">
        <source media="(min-width: 768px)" srcset="{{ asset('aset/elemen_bagian_hero_section(desktop).webp') }}">
        <img src="{{ asset('aset/elemen_bagian_hero_section(mobile).webp') }}" alt="Leaves decoration" class="block w-full h-auto object-contain object-left-bottom" />
    </picture>

    <div id="hero-city" class="absolute bottom-8 right-6 md:right-24 z-20 hidden md:block text-xs uppercase tracking-[0.3em] text-warmwhite/50">
        {{ $hero['city'] }}
    </div>
</section>
