<!-- SECTION 4 - EVENTS -->
<section id="event" class="relative w-full overflow-hidden text-moss">
    <!-- Top Title Area -->
    <div class="bg-moss text-warmwhite px-6 md:px-16 lg:px-24 pt-24 pb-0 flex flex-col md:flex-row justify-between items-end relative overflow-hidden">
        <div class="relative z-10 w-full md:w-[44%] md:pr-8 pb-12 md:pb-16 flex flex-col justify-center">
            <p class="text-[10px] md:text-xs uppercase tracking-[0.3em] mb-4 text-warmwhite/70 font-semibold">DETAIL ACARA</p>
            <h2 id="event-headline" class="font-display text-5xl sm:text-6xl md:text-7xl mb-6 leading-[1.08]">{!! nl2br(e($event['headline'])) !!}</h2>
            <div class="w-12 h-px bg-warmwhite/50 mb-6"></div>
            <p id="event-intro" class="text-sm md:text-base leading-relaxed text-warmwhite/90 max-w-sm">{{ $event['intro'] }}</p>
        </div>

        <div class="relative z-10 w-full md:w-[56%] flex justify-end items-end">
            <div class="relative w-full max-w-2xl">
                <img id="event-header-photo" src="{{ $event['header_photo'] }}" data-fallback="/aset/section4_header_cincin.webp" class="w-full h-auto object-cover block" alt="Cincin Pernikahan - A Special Day" loading="lazy" decoding="async" />
            </div>
        </div>
    </div>

    <!-- Event Cards -->
    <div class="bg-paper px-6 sm:px-10 md:px-16 lg:px-20 py-20 md:py-24 relative overflow-hidden">
        <img src="{{ asset('aset/section4_bunga.webp') }}" class="absolute -top-6 right-0 w-28 sm:w-36 md:w-44 opacity-95 pointer-events-none z-20" alt="" loading="lazy" />
        <img src="{{ asset('aset/section4_bunga(2).webp') }}" class="absolute top-1/2 -translate-y-1/2 left-0 w-20 sm:w-28 md:w-32 opacity-90 pointer-events-none z-20" alt="" loading="lazy" />

        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-10 relative z-10">
            <x-invitation.event-card
                type="akad"
                prefix="event-akad"
                :name="$event['akad_name']"
                :date="$event['akad_date']"
                :time="$event['akad_time']"
                :place="$event['akad_place']"
                :address="$event['akad_address']"
                :photo="$event['akad_photo']"
                photoFallback="/aset/section4_fotoakadnikah.webp"
            />
            <x-invitation.event-card
                type="resepsi"
                prefix="event-resepsi"
                :name="$event['resepsi_name']"
                :date="$event['resepsi_date']"
                :time="$event['resepsi_time']"
                :place="$event['resepsi_place']"
                :address="$event['resepsi_address']"
                :photo="$event['resepsi_photo']"
                photoFallback="/aset/section4_fotoresepsipernikahan.webp"
            />
        </div>
    </div>

    <!-- Location Map -->
    <div class="bg-paper px-6 sm:px-10 md:px-16 lg:px-20 pt-8 pb-24 relative overflow-hidden">
        <img src="{{ asset('aset/section4_bunga(3).webp') }}" class="absolute top-2 right-0 w-28 sm:w-36 md:w-44 opacity-90 pointer-events-none z-20" alt="" loading="lazy" />

        <div class="max-w-6xl mx-auto flex flex-col lg:flex-row gap-12 lg:gap-16 relative z-10 items-start">
            <div class="w-full lg:w-5/12">
                <p class="text-[10px] md:text-xs uppercase tracking-[0.3em] font-semibold mb-4 text-moss/80">LOKASI ACARA</p>
                <h2 id="event-venue-headline" class="font-display text-5xl sm:text-6xl mb-5 leading-[1.08] text-moss">{!! nl2br(e($event['venue_headline'])) !!}</h2>
                <div class="w-10 h-px bg-moss/50 mb-5"></div>
                <p id="event-venue-intro" class="text-xs sm:text-sm leading-relaxed mb-8 text-moss/85 max-w-sm">{{ $event['venue_intro'] }}</p>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-full bg-moss flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-4 h-4 text-warmwhite" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6-5.2-6-10a6 6 0 1112 0c0 4.8-6 10-6 10z" />
                                <circle cx="12" cy="11" r="2" />
                            </svg>
                        </div>
                        <div>
                            <p id="event-venue-name" class="font-medium text-xs sm:text-sm text-moss">{{ $event['venue_name'] }}</p>
                            <p id="event-venue-address" class="text-xs text-moss/75">{{ $event['venue_address'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-9 h-9 rounded-full bg-moss flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-4 h-4 text-warmwhite" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 17h14v-5l-2-5H7L5 12v5zm2 0v2m10-2v2M7.5 14.5h.01m8.99 0h.01" />
                            </svg>
                        </div>
                        <p id="event-feature-parking" class="text-xs sm:text-sm text-moss/90">{{ $event['features_parking'] }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-9 h-9 rounded-full bg-moss flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-4 h-4 text-warmwhite" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <rect x="6" y="3" width="12" height="14" rx="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 11h12M9 17l-2 3m8-3l2 3M9 14h.01M15 14h.01" />
                            </svg>
                        </div>
                        <p id="event-feature-transit" class="text-xs sm:text-sm text-moss/90">{{ $event['features_transit'] }}</p>
                    </div>
                </div>

                <div class="relative mt-8 sm:mt-10 -ml-4 sm:-ml-6 max-w-lg">
                    <img id="event-venue-photo" src="{{ $event['venue_photo'] }}" data-fallback="/aset/section4_bagianbawah.webp" alt="Our Wedding Venue" class="w-full h-auto drop-shadow-2xl" loading="lazy" decoding="async" />
                </div>
            </div>

            <div class="w-full lg:w-7/12 flex flex-col lg:pt-8">
                <div class="w-full h-[320px] sm:h-[380px] border border-warmwhite rounded-2xl overflow-hidden shadow-xl bg-white p-1.5">
                    <div class="w-full h-full rounded-xl overflow-hidden relative">
                        <iframe id="event-maps-iframe" src="{{ $event['maps_embed'] }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" title="Peta Lokasi Acara"></iframe>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 mt-5">
                    <a id="event-maps-link" href="{{ $event['maps_link'] }}" target="_blank" rel="noopener" class="flex-1 bg-moss text-warmwhite text-center py-3.5 px-6 rounded-xl text-xs sm:text-sm font-medium hover:bg-mossLight transition flex items-center justify-center gap-2.5 shadow-md">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" />
                        </svg>
                        Buka di Google Maps
                    </a>
                    <a id="event-routes-link" href="{{ $event['routes_link'] ?: $event['maps_link'] }}" target="_blank" rel="noopener" class="flex-1 bg-transparent border border-moss/60 text-moss text-center py-3.5 px-6 rounded-xl text-xs sm:text-sm font-medium hover:bg-moss/5 transition flex items-center justify-center gap-2.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 17h14v-5l-2-5H7L5 12v5zm2 0v2m10-2v2" />
                        </svg>
                        Dapatkan Rute
                    </a>
                </div>

                <div class="text-center mt-14 sm:mt-16">
                    <p id="event-venue-quote" class="font-display text-lg sm:text-xl md:text-2xl leading-relaxed text-moss/90">
                        &ldquo;{!! nl2br(e($event['bottom_quote'])) !!}&rdquo;
                    </p>
                    <div class="h-px w-12 bg-moss/50 mx-auto mt-5"></div>
                </div>
            </div>
        </div>
    </div>
</section>
