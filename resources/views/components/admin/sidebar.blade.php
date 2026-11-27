@php
    $isDashboard = request()->routeIs('admin.dashboard');
    $isKontenArea = request()->routeIs('admin.konten.*', 'admin.story.*', 'admin.galeri.*');
    $currentSection = request()->route('section');
    $isStory = request()->routeIs('admin.story.*');
    $isGaleri = request()->routeIs('admin.galeri.*');
    $isRsvp = request()->routeIs('admin.rsvp.*');
    $isTamu = request()->routeIs('admin.tamu.*');

    $sectionLinks = [
        ['label' => 'Sec 1: Hero & Pembuka', 'href' => route('admin.konten.edit', 'hero'), 'active' => $currentSection === 'hero'],
        ['label' => 'Sec 2: Mempelai', 'href' => route('admin.konten.edit', 'pengantin'), 'active' => $currentSection === 'pengantin'],
        ['label' => 'Sec 3: Our Story', 'href' => route('admin.story.index'), 'active' => $isStory],
        ['label' => 'Sec 4: Waktu & Lokasi', 'href' => route('admin.konten.edit', 'acara'), 'active' => $currentSection === 'acara'],
        ['label' => 'Sec 5: Galeri Foto', 'href' => route('admin.galeri.index'), 'active' => $isGaleri],
        ['label' => 'Sec 6: RSVP & Penutup', 'href' => route('admin.konten.edit', 'penutup'), 'active' => $currentSection === 'penutup'],
    ];
@endphp

<aside id="adminSidebar" class="sidebar-bg fixed inset-y-0 left-0 lg:static w-64 min-h-screen flex-shrink-0 flex flex-col justify-between overflow-hidden select-none z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <div class="p-6 relative z-10">
        <div class="flex items-center justify-between mb-10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <span class="font-display text-2xl font-semibold tracking-[0.28em] text-[#FAF8F2]">REL</span>
                <span class="text-[#7A8672] text-xl font-light">|</span>
                <span class="font-display text-2xl font-semibold tracking-[0.28em] text-[#FAF8F2]">LIV</span>
            </a>
            <button type="button" data-sidebar-close class="lg:hidden p-1.5 rounded-lg text-[#C7CFBF] hover:text-white hover:bg-[#323E2A]">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <nav class="space-y-1.5 text-sm font-medium">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-2xl transition {{ $isDashboard ? 'bg-sidebarActive text-[#FAF8F2] shadow-sm' : 'text-[#C7CFBF] hover:text-white hover:bg-[#323E2A]' }}">
                <i data-lucide="home" class="w-4 h-4"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.konten.index') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-2xl transition {{ $isKontenArea ? 'bg-sidebarActive text-[#FAF8F2] shadow-sm' : 'text-[#C7CFBF] hover:text-white hover:bg-[#323E2A]' }}">
                <i data-lucide="layers" class="w-4 h-4"></i>
                <span>Kelola Konten</span>
            </a>

            @if ($isKontenArea)
                <div class="pl-4 pr-1 py-1 space-y-1">
                    @foreach ($sectionLinks as $item)
                        <a href="{{ $item['href'] }}"
                           class="flex items-center space-x-2.5 px-3 py-1.5 rounded-xl text-xs transition {{ $item['active'] ? 'bg-[#405036] text-white font-medium' : 'text-[#A8B39E] hover:text-white hover:bg-[#323E2A]/70' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $item['active'] ? 'bg-emerald-400' : 'bg-[#7A8672]' }}"></span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            @unless ($isKontenArea)
                <a href="{{ route('admin.galeri.index') }}"
                   class="flex items-center space-x-3.5 px-4 py-3 rounded-2xl transition text-[#C7CFBF] hover:text-white hover:bg-[#323E2A]">
                    <i data-lucide="image" class="w-4 h-4"></i>
                    <span>Galeri Foto</span>
                </a>
            @endunless

            <a href="{{ route('admin.rsvp.index') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-2xl transition {{ $isRsvp ? 'bg-sidebarActive text-[#FAF8F2] shadow-sm' : 'text-[#C7CFBF] hover:text-white hover:bg-[#323E2A]' }}">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                <span>Tamu &amp; RSVP</span>
            </a>

            <a href="{{ route('admin.tamu.index') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-2xl transition {{ $isTamu ? 'bg-sidebarActive text-[#FAF8F2] shadow-sm' : 'text-[#C7CFBF] hover:text-white hover:bg-[#323E2A]' }}">
                <i data-lucide="user-check" class="w-4 h-4"></i>
                <span>Data Tamu</span>
            </a>
        </nav>
    </div>

    <div class="relative w-full pointer-events-none mt-auto">
        <img src="{{ asset('aset/section2_bunga.webp') }}" alt="" class="w-full opacity-20 invert object-contain translate-y-6">
    </div>
</aside>
