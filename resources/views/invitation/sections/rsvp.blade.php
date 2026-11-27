<!-- SECTION 6 - RSVP, WISHES, TANDA KASIH & FOOTER -->
<section id="rsvp" class="relative w-full overflow-hidden bg-paper text-moss">
    <!-- Top Title Area -->
    <div class="bg-moss text-warmwhite px-6 md:px-16 lg:px-24 pt-24 pb-12 md:pb-0 flex flex-col md:flex-row justify-between items-end relative">
        <div class="relative z-10 w-full md:w-[42%] pb-8 md:pb-16">
            <p class="text-[10px] md:text-xs uppercase tracking-[0.3em] mb-4 text-warmwhite/70 font-semibold">UCAPAN &amp; RSVP</p>
            <h2 id="rsvp-headline" class="font-display text-5xl sm:text-6xl md:text-7xl mb-6 leading-[1.08]">{!! nl2br(e($rsvp['headline'])) !!}</h2>
            <div class="w-12 h-px bg-warmwhite/50 mb-6"></div>
            <p id="rsvp-intro" class="text-sm md:text-base leading-relaxed text-warmwhite/90 max-w-sm">{{ $rsvp['intro'] }}</p>
        </div>

        <div class="relative z-20 w-full md:w-[58%] flex justify-end md:-mb-8">
            <div class="relative w-full max-w-xl">
                <img id="rsvp-banner-photo" src="{{ $rsvp['banner_photo'] }}" data-fallback="/aset/section6(01).webp" class="w-full h-auto drop-shadow-2xl block" alt="Terima kasih atas doa dan kehadirannya" loading="lazy" decoding="async" />
            </div>
        </div>
    </div>

    <!-- Forms & Wishes Area -->
    <div class="px-6 sm:px-10 md:px-16 lg:px-20 pt-20 pb-16 relative max-w-6xl mx-auto flex flex-col md:flex-row gap-12 lg:gap-16">
        <!-- Form Left -->
        <div class="w-full md:w-[46%]">
            <h3 id="rsvp-form-title" class="font-display text-3xl sm:text-4xl mb-2 text-moss">{{ $rsvp['form_title'] }}</h3>
            <p id="rsvp-form-intro" class="text-xs sm:text-sm text-moss/80 mb-6">{{ $rsvp['form_intro'] }}</p>

            <form id="form-kirim-ucapan"
                  action="{{ route('rsvp.store') }}"
                  method="POST"
                  data-wishes-url="{{ route('rsvp.wishes') }}"
                  class="space-y-4">
                @csrf
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-moss/70">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <circle cx="12" cy="8" r="3.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 20a6.5 6.5 0 0113 0H5.5z" />
                        </svg>
                    </span>
                    <input id="input-nama-tamu"
                           name="nama_pengirim"
                           type="text"
                           required
                           value="{{ $isPersonalizedGuest ? $guestName : '' }}"
                           placeholder="Nama Anda"
                           class="w-full bg-warmwhite/50 border border-moss/20 rounded-xl py-3.5 pl-11 pr-4 text-xs sm:text-sm focus:outline-none focus:border-moss transition" />
                </div>

                <div class="relative">
                    <span class="absolute left-4 top-4 text-moss/70">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16a1 1 0 011 1v10a1 1 0 01-1 1H8l-4 3V6a1 1 0 011-1z" />
                            <path stroke-linecap="round" d="M8 10h8M8 13h5" />
                        </svg>
                    </span>
                    <textarea id="input-pesan-tamu"
                              name="pesan_ucapan"
                              required
                              placeholder="Tulis ucapan dan doa..."
                              rows="4"
                              class="w-full bg-warmwhite/50 border border-moss/20 rounded-xl py-3.5 pl-11 pr-4 text-xs sm:text-sm focus:outline-none focus:border-moss transition resize-none"></textarea>
                </div>

                <div class="pt-2">
                    <p class="text-xs sm:text-sm font-medium mb-3 text-moss">Konfirmasi Kehadiran</p>
                    <div class="flex flex-col sm:flex-row gap-2 bg-warmwhite/40 border border-moss/20 rounded-full p-2 px-4">
                        @foreach (['Hadir', 'Tidak Hadir', 'Masih Ragu'] as $statusOption)
                            <label class="flex-1 flex items-center gap-2.5 cursor-pointer py-1.5 px-2 rounded-full transition">
                                <input type="radio" name="attendance" value="{{ $statusOption }}" class="w-4 h-4 accent-moss" @checked($statusOption === 'Hadir') />
                                <span class="text-xs sm:text-sm font-medium">{{ $statusOption }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <p id="rsvp-feedback" class="hidden text-xs rounded-xl px-4 py-2.5 font-medium"></p>

                <button type="submit" class="w-full bg-moss text-warmwhite py-3.5 rounded-full font-medium flex items-center justify-center gap-2.5 mt-4 hover:bg-mossLight transition shadow-md text-xs sm:text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" />
                    </svg>
                    <span>Kirim Ucapan</span>
                </button>
            </form>
        </div>

        <!-- Messages Right -->
        <div class="w-full md:w-[54%]">
            <h3 class="font-display text-3xl sm:text-4xl mb-2 text-moss">Ucapan dari Tamu</h3>
            <div class="w-10 h-px bg-moss/40 mb-6"></div>

            <div id="wishes-feed-container" class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                @forelse ($wishes as $wish)
                    <x-invitation.wish-card :wish="$wish" />
                @empty
                    <div class="bg-warmwhite/75 p-5 rounded-xl shadow-sm border border-moss/10 text-center">
                        <p class="text-xs sm:text-sm text-moss/75">Belum ada doa &amp; ucapan. Jadilah yang pertama mengirimkan doa restu!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tanda Kasih / Wedding Gift (DANA & SeaBank) -->
    <div class="px-6 sm:px-10 md:px-16 lg:px-20 pb-20 relative max-w-6xl mx-auto">
        <div class="bg-[#b2b5a6]/85 rounded-2xl p-7 sm:p-8 md:p-10 flex flex-col lg:flex-row gap-8 items-center shadow-md border border-moss/10">
            <div class="lg:w-[34%] text-center lg:text-left">
                <span class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-[0.25em] text-moss/80 bg-paper/70 px-3 py-1 rounded-full mb-3 border border-moss/15">
                    <svg class="w-3.5 h-3.5 text-moss" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                    <span>Amplop Digital</span>
                </span>
                <h3 id="rsvp-confirm-title" class="font-display text-2xl sm:text-3xl mb-3 text-moss leading-snug">{!! nl2br(e($rsvp['confirm_box_title'])) !!}</h3>
                <p id="rsvp-confirm-intro" class="text-xs sm:text-sm text-moss/85 leading-relaxed">{{ $rsvp['confirm_box_intro'] }}</p>
            </div>

            <div class="lg:w-[66%] grid grid-cols-1 sm:grid-cols-2 gap-5 w-full items-stretch">
                <!-- Card 1: E-Wallet DANA -->
                <div class="bg-paper/95 border border-moss/20 rounded-xl p-5 text-left shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4 pb-2.5 border-b border-moss/10">
                            <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-moss/75">E-Wallet</span>
                            <span class="text-[10px] font-bold tracking-wider bg-moss text-warmwhite px-2.5 py-0.5 rounded">{{ strtoupper($rsvp['ewallet_name']) }}</span>
                        </div>
                        <p id="rsvp-ewallet-name" class="font-display text-xl text-moss font-semibold mb-3">{{ $rsvp['ewallet_name'] }}</p>
                        <div class="bg-white/85 border border-moss/15 rounded-xl p-3.5 mb-3">
                            <span class="block text-[10px] uppercase tracking-[0.18em] text-moss/60 mb-1">Nomor {{ $rsvp['ewallet_name'] }}</span>
                            <div class="flex items-center justify-between gap-2">
                                <span id="rsvp-ewallet-number" class="font-mono text-base sm:text-lg font-bold tracking-wider text-moss select-all">{{ $rsvp['ewallet_number'] }}</span>
                                <button type="button" data-copy-target="rsvp-ewallet-number" class="inline-flex items-center gap-1 bg-moss text-warmwhite hover:bg-moss/90 text-[11px] font-medium px-3 py-1.5 rounded-lg transition shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path>
                                    </svg>
                                    <span data-copy-label>Salin</span>
                                </button>
                            </div>
                        </div>
                        <p class="text-xs text-moss/80">Atas Nama: <strong id="rsvp-ewallet-holder" class="font-semibold text-moss">{{ $rsvp['ewallet_holder'] }}</strong></p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-moss/10 flex items-center gap-2 text-[11px] text-moss/75 leading-snug">
                        <svg class="w-4 h-4 text-moss shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Klik tombol <strong>Salin</strong> untuk menyalin nomor <strong>{{ $rsvp['ewallet_name'] }}</strong> di atas.</span>
                    </div>
                </div>

                <!-- Card 2: Transfer Rekening SeaBank -->
                <div class="bg-paper/95 border border-moss/20 rounded-xl p-5 text-left shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4 pb-2.5 border-b border-moss/10">
                            <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-moss/75">Transfer Bank</span>
                            <svg class="w-5 h-5 text-moss/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <p id="rsvp-bank-name" class="font-display text-xl text-moss font-semibold mb-3">{{ $rsvp['bank_name'] }}</p>
                        <div class="bg-white/85 border border-moss/15 rounded-xl p-3.5 mb-3">
                            <span class="block text-[10px] uppercase tracking-[0.18em] text-moss/60 mb-1">Nomor Rekening</span>
                            <div class="flex items-center justify-between gap-2">
                                <span id="rsvp-bank-number" class="font-mono text-base sm:text-lg font-bold tracking-wider text-moss select-all">{{ $rsvp['bank_number'] }}</span>
                                <button type="button" id="btn-copy-rekening" data-copy-target="rsvp-bank-number" class="inline-flex items-center gap-1 bg-moss text-warmwhite hover:bg-moss/90 text-[11px] font-medium px-3 py-1.5 rounded-lg transition shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path>
                                    </svg>
                                    <span id="btn-copy-rekening-label" data-copy-label>Salin</span>
                                </button>
                            </div>
                        </div>
                        <p class="text-xs text-moss/80">Atas Nama: <strong id="rsvp-bank-holder" class="font-semibold text-moss">{{ $rsvp['bank_holder'] }}</strong></p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-moss/10 flex items-center gap-2 text-[11px] text-moss/75 leading-snug">
                        <svg class="w-4 h-4 text-moss shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Klik tombol <strong>Salin</strong> untuk menyalin nomor rekening <strong>{{ $rsvp['bank_name'] }}</strong> di atas.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Closing Footer Area -->
    <div class="px-6 sm:px-10 md:px-16 lg:px-20 pb-24 pt-6 flex flex-col md:flex-row items-center justify-between relative max-w-6xl mx-auto">
        <div class="w-full md:w-5/12 mb-12 md:mb-0 flex justify-center md:justify-start pl-0 md:pl-8">
            <div class="text-center">
                <p id="rsvp-thanks-script" class="font-script text-5xl sm:text-6xl text-moss drop-shadow-sm -rotate-[8deg] leading-[0.95]">{!! nl2br(e($rsvp['thanks_script'])) !!}</p>
                <p class="text-moss/70 text-lg mt-2">&hearts;</p>
            </div>
        </div>

        <div class="w-full md:w-7/12 flex flex-col items-center md:items-end">
            <div class="w-full max-w-md text-center md:text-left">
                <h2 id="rsvp-closing-quote" class="font-display text-2xl sm:text-3xl md:text-4xl mb-4 leading-snug text-moss">&ldquo;{!! nl2br(e($rsvp['closing_quote'])) !!}&rdquo;</h2>
                <div class="w-10 h-px bg-moss/50 mx-auto md:mx-0 mb-4"></div>
                <p id="rsvp-closing-subtext" class="text-xs sm:text-sm text-moss/85 mb-10 leading-relaxed">{!! nl2br(e($rsvp['closing_subtext'])) !!}</p>

                <div class="flex flex-col items-center md:items-start relative pt-2">
                    <div class="w-10 h-px bg-moss/40 mb-4"></div>
                    <p id="rsvp-footer-couple" class="text-xs uppercase tracking-[0.28em] font-semibold text-moss mb-1">{{ $rsvp['footer_couple'] }}</p>
                    <p id="rsvp-footer-date" class="text-[11px] uppercase tracking-[0.28em] text-moss/80 font-medium">{{ $rsvp['footer_date'] }}</p>
                    <img src="{{ asset('aset/section5_bunga(2).webp') }}" class="absolute -top-4 right-2 sm:right-8 w-20 sm:w-24 opacity-90 pointer-events-none" alt="" loading="lazy" />
                </div>
            </div>
        </div>
    </div>
</section>
