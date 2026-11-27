<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="upload-url" content="{{ route('admin.media.upload') }}">
    <title>@yield('title', 'Dashboard — REL | LIV')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="bg-adminBg text-inkAdmin font-sans min-h-screen flex antialiased">
    {{-- Mobile Sidebar Backdrop --}}
    <div id="adminSidebarBackdrop" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden" data-sidebar-close></div>

    {{-- Sidebar Navigation --}}
    <x-admin.sidebar />

    {{-- Main Content Column --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto custom-scroll">
        {{-- Topbar --}}
        @hasSection('topbar')
            @yield('topbar')
        @else
            <x-admin.topbar
                :title="$pageHeading ?? 'Dashboard'"
                :subtitle="$pageSubtitle ?? null"
                :badge="$pageBadge ?? null"
                :preview-url="$previewUrl ?? route('invitation.show')"
            />
        @endif

        {{-- Main Body --}}
        <main class="p-5 sm:p-8 space-y-6 {{ $containerClass ?? 'max-w-[1440px]' }} mx-auto w-full">
            {{-- Flash Status Alert --}}
            @if (session('status'))
                <div data-flash-alert class="flex items-center justify-between gap-3 px-5 py-3.5 rounded-2xl bg-badgeGreen border border-moss/20 text-textGreen text-xs font-medium shadow-sm">
                    <div class="flex items-center space-x-2.5">
                        <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                    <button type="button" onclick="this.closest('[data-flash-alert]').remove()" class="text-textGreen/70 hover:text-textGreen">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            {{-- Flash Error Alert --}}
            @if (session('error'))
                <div data-flash-alert class="flex items-center justify-between gap-3 px-5 py-3.5 rounded-2xl bg-badgeRed border border-textRed/20 text-textRed text-xs font-medium shadow-sm">
                    <div class="flex items-center space-x-2.5">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.closest('[data-flash-alert]').remove()" class="text-textRed/70 hover:text-textRed">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            {{-- Validation Errors Summary --}}
            @if ($errors->any())
                <div class="px-5 py-4 rounded-2xl bg-badgeRed border border-textRed/20 text-textRed text-xs shadow-sm space-y-1.5">
                    <div class="flex items-center space-x-2 font-semibold">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        <span>Mohon periksa kembali input formulir berikut:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- Reusable Floating Toast --}}
    <div id="adminToast" class="fixed bottom-6 right-6 z-50 bg-moss text-adminBg px-5 py-3.5 rounded-2xl shadow-xl flex items-center space-x-3 transition-all duration-300 translate-y-20 opacity-0 pointer-events-none">
        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400 shrink-0"></i>
        <span id="adminToastMsg" class="text-xs font-medium">Perubahan berhasil disimpan!</span>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>
