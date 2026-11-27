<!-- SECTION 5 - GALLERY -->
<section id="gallery" class="relative w-full overflow-hidden bg-paper text-moss">
    <!-- Top Title Area -->
    <div class="bg-moss text-warmwhite px-6 md:px-16 lg:px-24 pt-24 pb-0 flex flex-col md:flex-row justify-between items-end relative overflow-hidden">
        <div class="relative z-10 w-full md:w-5/12 pb-12 md:pb-20">
            <p class="text-xs md:text-sm mb-4 text-warmwhite/80 font-serif">Gallery</p>
            <h2 id="gallery-headline" class="font-display text-5xl sm:text-6xl md:text-7xl mb-6 leading-[1.08]">{!! nl2br(e($gallery['headline'])) !!}</h2>
            <div class="w-12 h-px bg-warmwhite/50 mb-6"></div>
            <p id="gallery-intro" class="text-sm md:text-base leading-relaxed text-warmwhite/90 max-w-xs">{{ $gallery['intro'] }}</p>
        </div>

        <div class="relative z-10 w-full md:w-7/12 flex justify-end">
            <div class="relative w-full max-w-2xl">
                <div class="w-full overflow-hidden rounded-tl-[80px] sm:rounded-tl-[130px] md:rounded-tl-[170px] shadow-2xl">
                    <img id="gallery-hero-photo" src="{{ $gallery['hero_photo'] }}" data-fallback="/aset/section5_fotopengantin.webp" class="w-full h-auto object-cover block" alt="Gallery Hero" loading="lazy" decoding="async" />
                </div>
                <img src="{{ asset('aset/section5_bunga.webp') }}" class="absolute -bottom-4 -left-10 sm:-left-16 w-28 sm:w-36 md:w-44 z-20 pointer-events-none drop-shadow-xl" alt="" loading="lazy" />
                <p id="gallery-hero-script" class="absolute top-[16%] right-6 sm:right-10 font-script text-3xl sm:text-4xl md:text-5xl text-warmwhite rotate-[-4deg] drop-shadow-lg leading-[0.95]">{!! nl2br(e($gallery['hero_script'])) !!}</p>
            </div>
        </div>
    </div>

    <!-- Gallery Grid & Photobooth Strips -->
    <div class="px-6 sm:px-10 md:px-16 lg:px-20 pt-10 md:pt-14 pb-16 max-w-7xl mx-auto relative z-10">
        <!-- Category Filter Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8 pb-4 border-b border-moss/15">
            <div>
                <p class="text-[10px] uppercase tracking-[0.28em] text-moss/65 font-semibold mb-1">KURASI VISUAL</p>
                <h3 class="font-display text-2xl sm:text-3xl text-moss">Koleksi Foto &amp; Momen</h3>
            </div>
            <div id="gallery-filter-bar" class="flex flex-wrap items-center gap-2">
                @foreach ([
                    'all' => 'Semua Momen',
                    'prewedding' => 'Prewedding Outdoor',
                    'keseharian' => 'Keseharian & Candid',
                    'acara' => 'Detail & Cincin',
                    'photobooth' => 'Photobooth Strip',
                ] as $catKey => $catLabel)
                    <button type="button"
                            data-gallery-filter="{{ $catKey }}"
                            class="gallery-filter-pill px-4 py-2 rounded-full text-xs font-medium transition {{ $catKey === 'all' ? 'bg-moss text-warmwhite shadow-sm' : 'bg-warmwhite/80 text-moss hover:bg-moss/10 border border-moss/20' }}">
                        {{ $catLabel }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Dynamic Bento Grid (SSR from Eloquent) -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 md:gap-4 items-stretch" id="gallery-bento-grid">
            @if ($bentoPhotos->isEmpty())
                <div class="md:col-span-12 py-12 text-center bg-warmwhite/40 rounded-xl border border-moss/15">
                    <p class="text-sm text-moss/75 font-serif">Belum ada foto di galeri.</p>
                </div>
            @elseif ($bentoPhotos->count() < 10)
                <div class="md:col-span-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 md:gap-4">
                    @foreach ($bentoPhotos as $photo)
                        <x-invitation.gallery-card :photo="$photo" wrapperClasses="h-64 sm:h-72" />
                    @endforeach
                </div>
            @else
                <div class="md:col-span-4 flex flex-col gap-3.5 md:gap-4">
                    <x-invitation.gallery-card :photo="$bentoPhotos[0]" wrapperClasses="flex-1 min-h-[300px] md:min-h-[360px]" />
                    <x-invitation.gallery-card :photo="$bentoPhotos[1]" wrapperClasses="h-48 sm:h-52 md:h-56" />
                </div>

                <div class="md:col-span-8 flex flex-col gap-3.5 md:gap-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 md:gap-4 h-auto sm:h-48 md:h-52">
                        <x-invitation.gallery-card :photo="$bentoPhotos[2]" wrapperClasses="h-52 sm:h-full" imgPosition="object-center" />
                        <x-invitation.gallery-card :photo="$bentoPhotos[3]" wrapperClasses="h-52 sm:h-full" imgPosition="object-[center_35%]" />
                        <x-invitation.gallery-card :photo="$bentoPhotos[4]" wrapperClasses="h-52 sm:h-full" imgPosition="object-[center_45%]" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 md:gap-4 flex-1">
                        <div class="sm:col-span-8 flex flex-col gap-3.5 md:gap-4">
                            <div class="grid grid-cols-2 gap-3.5 md:gap-4 h-44 sm:h-48">
                                <x-invitation.gallery-card :photo="$bentoPhotos[5]" wrapperClasses="h-full" imgPosition="object-bottom" />
                                <x-invitation.gallery-card :photo="$bentoPhotos[6]" wrapperClasses="h-full" />
                            </div>
                            <div class="flex gap-3.5 md:gap-4 h-48 sm:h-52 md:h-56 w-full">
                                <x-invitation.gallery-card :photo="$bentoPhotos[7]" wrapperClasses="h-full min-w-0" extraStyle="flex: 7 1 58%; width: 58%;" imgPosition="object-bottom" />
                                <x-invitation.gallery-card :photo="$bentoPhotos[8]" wrapperClasses="h-full min-w-0" extraStyle="flex: 5 1 42%; width: 42%;" imgPosition="object-center" />
                            </div>
                        </div>

                        <div class="sm:col-span-4 relative overflow-visible">
                            <x-invitation.gallery-card :photo="$bentoPhotos[9]" wrapperClasses="w-full h-80 sm:h-full" />
                            <img src="{{ asset('aset/section5_bunga(2).webp') }}" class="absolute -bottom-14 -right-6 sm:-right-10 w-28 sm:w-36 md:w-40 z-20 pointer-events-none drop-shadow-md" alt="" loading="lazy" />
                        </div>
                    </div>
                </div>

                @if ($bentoPhotos->count() > 10)
                    <div class="md:col-span-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5 md:gap-4 mt-1">
                        @foreach ($bentoPhotos->slice(10) as $extraPhoto)
                            <x-invitation.gallery-card :photo="$extraPhoto" wrapperClasses="h-60 sm:h-64" />
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        <!-- Photobooth Strips Showcase -->
        @if ($stripPhotos->isNotEmpty())
            <div id="gallery-photobooth-section" class="mt-16 pt-12 border-t border-moss/15">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-3">
                    <div>
                        <span class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-[0.26em] text-moss/75 bg-moss/10 px-3 py-1 rounded-full mb-2.5">
                            <svg class="w-3.5 h-3.5 text-moss" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <rect x="4" y="3" width="16" height="18" rx="2" />
                                <line x1="8" y1="7" x2="16" y2="7" />
                                <line x1="8" y1="12" x2="16" y2="12" />
                                <line x1="8" y1="17" x2="16" y2="17" />
                            </svg>
                            <span>Picapica Memories · 23.09.2026</span>
                        </span>
                        <h3 class="font-display text-2xl sm:text-3xl md:text-4xl text-moss">Our Photobooth Strips</h3>
                    </div>
                </div>

                <div id="gallery-photobooth-grid" class="grid grid-cols-2 sm:grid-cols-4 gap-5 sm:gap-6 md:gap-8 items-start">
                    @foreach ($stripPhotos as $idx => $strip)
                        @php
                            $src = \App\Services\MediaStorageService::normalizeAssetUrl($strip->foto_url, '/aset/photobooth_strip_1.webp');
                            $isNoir = str_contains(strtolower($src), 'strip_3') || str_contains(strtolower((string) $strip->judul), 'noir');
                            $frameBg = $isNoir ? 'bg-[#1a1d17] border-moss/25' : 'bg-white border-moss/15';
                            $tilt = $idx % 2 === 0 ? '-rotate-1' : 'rotate-1';
                        @endphp
                        <div class="gallery-item cursor-pointer group flex flex-col items-center"
                             data-category="photobooth {{ $strip->kategori ?: 'keseharian' }}"
                             data-lightbox-src="{{ $src }}"
                             data-caption="{{ $strip->keterangan ?: ($strip->judul ?: 'Picapica Photobooth Strip') }}">
                            <div class="w-full max-w-[200px] {{ $frameBg }} p-2 sm:p-2.5 rounded-xl shadow-lg border transform {{ $tilt }} group-hover:rotate-0 group-hover:-translate-y-1.5 transition-all duration-300">
                                <img src="{{ $src }}" alt="{{ $strip->judul ?: 'Photobooth Strip' }}" data-fallback="/aset/photobooth_strip_1.webp" loading="lazy" decoding="async" class="w-full h-auto object-contain rounded-lg block dynamic-gallery-img" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Quote below Gallery Grid -->
        <div class="relative w-full pt-12 pb-4 flex flex-col items-center justify-center text-center">
            <p id="gallery-bottom-quote" class="font-display text-lg sm:text-xl md:text-2xl italic leading-relaxed z-10 max-w-2xl text-moss">
                &ldquo;{!! nl2br(e($gallery['bottom_quote'])) !!}&rdquo;
            </p>
            <div class="w-12 h-px bg-moss/40 mx-auto mt-4 z-10"></div>
        </div>
    </div>

    <x-invitation.quote-banner
        variant="gallery"
        :image="asset('aset/section5_bagianbawah.webp')"
        imageAlt="Flowers background"
    >
        <p class="font-script text-4xl sm:text-5xl md:text-6xl text-warmwhite rotate-[-6deg] quote-banner-text-shadow text-center leading-[0.95]">
            More<br>Memories<br>to Come<br><span class="text-xl">&hearts;</span>
        </p>
    </x-invitation.quote-banner>
</section>
