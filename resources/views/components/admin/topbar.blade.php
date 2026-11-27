@props([
    'title' => 'Dashboard',
    'subtitle' => null,
    'badge' => null,
    'previewUrl' => null,
    'backUrl' => null,
    'breadcrumb' => null,
    'submitFormId' => null,
])

@php
    $userName = auth()->user()?->name ?? 'Fakhrel & Olivia';
    $initials = collect(preg_split('/[\s&]+/', trim($userName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('') ?: 'FO';
@endphp

<header class="min-h-[5rem] py-3 bg-adminBg/90 backdrop-blur-md px-5 sm:px-8 flex items-center justify-between gap-4 border-b border-borderSoft/60 sticky top-0 z-20">
    <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
        <button type="button" data-sidebar-open class="lg:hidden p-2 rounded-xl bg-cardBg border border-borderSoft text-inkAdmin hover:bg-cardMuted transition">
            <i data-lucide="menu" class="w-4 h-4"></i>
        </button>

        @if ($backUrl)
            <a href="{{ $backUrl }}" class="p-2 rounded-xl bg-cardBg border border-borderSoft text-inkAdmin hover:bg-cardMuted transition shrink-0" title="Kembali ke Pusat Konten">
                <i data-lucide="arrow-left" class="w-4 h-4 text-inkMuted"></i>
            </a>
        @endif

        <div class="min-w-0">
            @if ($breadcrumb)
                <div class="flex items-center space-x-2 text-[11px] text-inkMuted">
                    <a href="{{ route('admin.konten.index') }}" class="hover:underline">Kelola Konten</a>
                    <span>/</span>
                    <span class="text-inkAdmin font-semibold truncate">{{ $breadcrumb }}</span>
                </div>
            @endif

            <div class="flex items-center space-x-3">
                <h1 class="{{ $subtitle ? 'font-display text-2xl font-semibold' : 'font-sans text-lg sm:text-xl font-bold' }} text-inkAdmin truncate">{{ $title }}</h1>
                @if ($badge)
                    <span class="hidden sm:inline-block px-2.5 py-0.5 rounded-full bg-badgeGreen text-textGreen text-xs font-semibold shrink-0">{{ $badge }}</span>
                @endif
            </div>

            @if ($subtitle)
                <p class="text-xs text-inkMuted -mt-0.5 hidden sm:block">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
        @if ($previewUrl)
            <a href="{{ $previewUrl }}" target="_blank" class="inline-flex items-center space-x-2 px-3 sm:px-4 py-2 rounded-xl bg-cardBg border border-borderSoft text-xs font-medium text-inkAdmin hover:bg-cardMuted hover:border-moss/40 transition shadow-sm">
                <i data-lucide="eye" class="w-3.5 h-3.5 text-inkMuted"></i>
                <span class="hidden sm:inline">{{ $submitFormId ? 'Pratinjau di Web' : 'Lihat Website' }}</span>
            </a>
        @endif

        @if ($submitFormId)
            <button type="submit" form="{{ $submitFormId }}" class="inline-flex items-center space-x-2 px-4 sm:px-5 py-2.5 rounded-xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium transition shadow-sm">
                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                <span>Simpan Perubahan</span>
            </button>
        @else
            <button type="button" data-copy-link="{{ route('invitation.show') }}" class="inline-flex items-center space-x-2 px-3 sm:px-4 py-2 rounded-xl bg-cardBg border border-borderSoft text-xs font-medium text-inkAdmin hover:bg-cardMuted hover:border-moss/40 transition shadow-sm">
                <i data-lucide="share-2" class="w-3.5 h-3.5 text-inkMuted"></i>
                <span class="hidden sm:inline">Bagikan</span>
            </button>
        @endif

        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            @if ($submitFormId)
                <button type="submit" title="Keluar / Logout dari Admin Panel" class="p-2.5 rounded-xl bg-cardBg border border-borderSoft text-inkAdmin hover:border-rose-300 hover:text-rose-700 transition shadow-sm">
                    <i data-lucide="log-out" class="w-4 h-4 text-gray-400"></i>
                </button>
            @else
                <button type="submit" title="Keluar / Logout dari Admin Panel" class="inline-flex items-center space-x-2.5 pl-2 pr-3 py-1.5 rounded-full bg-cardBg border border-borderSoft hover:border-rose-300 hover:text-rose-700 transition shadow-sm">
                    <div class="w-7 h-7 rounded-full bg-[#E5ECE0] text-[#35492E] flex items-center justify-center font-display font-semibold text-xs">{{ $initials }}</div>
                    <span class="text-xs font-medium text-inkAdmin hidden md:inline">{{ $userName }}</span>
                    <i data-lucide="log-out" class="w-3.5 h-3.5 text-gray-400"></i>
                </button>
            @endif
        </form>
    </div>
</header>
