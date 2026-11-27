<!-- SECTION 1B - DEDICATED EDITORIAL COUNTDOWN INTERLUDE -->
@php
    $calendarTitle = urlencode("Pernikahan {$hero['groom_nickname']} & {$hero['bride_nickname']}");
    $calendarLocation = urlencode($event['akad_place'] ?? 'The Glass House, Jakarta');
    $calendarDetails = urlencode("Dengan penuh rasa syukur, kami mengundang Anda dalam hari bahagia {$hero['groom_nickname']} & {$hero['bride_nickname']}.");

    $monthMap = [
        'januari' => '01', 'january' => '01', 'jan' => '01',
        'februari' => '02', 'february' => '02', 'feb' => '02',
        'maret' => '03', 'march' => '03', 'mar' => '03',
        'april' => '04', 'apr' => '04',
        'mei' => '05', 'may' => '05',
        'juni' => '06', 'june' => '06', 'jun' => '06',
        'juli' => '07', 'july' => '07', 'jul' => '07',
        'agustus' => '08', 'august' => '08', 'aug' => '08', 'agu' => '08',
        'september' => '09', 'sep' => '09',
        'oktober' => '10', 'october' => '10', 'oct' => '10', 'okt' => '10',
        'november' => '11', 'nov' => '11',
        'desember' => '12', 'december' => '12', 'dec' => '12', 'des' => '12',
    ];
    $calDateYmd = '20261225';
    if (preg_match('/(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/', (string) ($hero['wedding_date'] ?? ''), $dm)) {
        $mon = $monthMap[strtolower($dm[2])] ?? null;
        if ($mon) {
            $calDateYmd = $dm[3] . $mon . str_pad($dm[1], 2, '0', STR_PAD_LEFT);
        }
    }
@endphp

<section id="countdown" class="relative w-full overflow-hidden bg-[#22291C] text-warmwhite border-y border-warmwhite/10">
    <img src="{{ asset('aset/section2_bunga(2).webp') }}" alt="" class="absolute -left-8 -bottom-10 w-32 sm:w-44 opacity-[0.12] pointer-events-none select-none rotate-12" loading="lazy" />
    <img src="{{ asset('aset/section2_bunga(3).webp') }}" alt="" class="absolute -right-8 -top-8 w-32 sm:w-44 opacity-[0.12] pointer-events-none select-none -rotate-12" loading="lazy" />

    <div class="relative z-10 max-w-3xl mx-auto px-6 py-14 sm:py-16 md:py-20 text-center">
        <p class="text-[10px] sm:text-xs uppercase tracking-[0.34em] text-warmwhite/65 mb-2">Save The Date</p>
        <p class="font-script text-3xl sm:text-4xl md:text-5xl text-warmwhite/95 leading-tight mb-1">Menuju Hari Bahagia</p>
        <p id="countdown-wedding-date" class="text-xs sm:text-sm uppercase tracking-[0.3em] text-warmwhite/75 mb-7 sm:mb-9">{{ $hero['wedding_date'] }}</p>

        <div id="hero-countdown"
             data-wedding-date="{{ $hero['wedding_date'] }}"
             class="py-6 sm:py-7 border-y border-warmwhite/20 flex items-center justify-center gap-5 sm:gap-10 md:gap-14 max-w-xl mx-auto"
             aria-label="Hitung mundur menuju hari pernikahan">
            @foreach ([['id' => 'cd-days', 'label' => 'Hari'], ['id' => 'cd-hours', 'label' => 'Jam'], ['id' => 'cd-minutes', 'label' => 'Menit'], ['id' => 'cd-seconds', 'label' => 'Detik']] as $unit)
                @if (! $loop->first)
                    <span class="text-warmwhite/35 text-xs select-none" aria-hidden="true">&#10022;</span>
                @endif
                <div class="text-center min-w-[3.4rem] sm:min-w-[4.5rem]">
                    <span id="{{ $unit['id'] }}" class="block font-display text-4xl sm:text-5xl md:text-6xl leading-none text-warmwhite font-light" style="font-variant-numeric: lining-nums tabular-nums;">00</span>
                    <span class="block font-display italic text-xs sm:text-sm md:text-base text-warmwhite/70 tracking-wide mt-2">{{ $unit['label'] }}</span>
                </div>
            @endforeach
        </div>

        <div class="mt-7 sm:mt-8">
            <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text={{ $calendarTitle }}&dates={{ $calDateYmd }}T010000Z/{{ $calDateYmd }}T070000Z&details={{ $calendarDetails }}&location={{ $calendarLocation }}"
               target="_blank"
               rel="noopener"
               class="inline-flex items-center gap-2 text-[11px] sm:text-xs uppercase tracking-[0.26em] text-warmwhite/80 hover:text-warmwhite border-b border-warmwhite/35 hover:border-warmwhite pb-1 transition">
                <span>Simpan Tanggal di Kalender</span>
                <span aria-hidden="true">&#8599;</span>
            </a>
        </div>
    </div>
</section>
