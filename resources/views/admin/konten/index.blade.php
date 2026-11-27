@extends('layouts.admin', [
    'pageHeading' => 'Pusat Kelola Konten',
    'pageBadge' => '6 Section Aktif',
    'previewUrl' => route('invitation.show'),
])

@section('title', 'Pusat Kelola Konten — REL | LIV')

@php
    $metaBySlug = [
        'hero' => ['anchor' => '#home', 'subtitle' => 'Hero & Pembuka Undangan', 'tags' => ['Nama Pengantin', 'Tanggal Acara', 'Salam Undangan', 'Background']],
        'pengantin' => ['anchor' => '#about', 'subtitle' => 'Profil Mempelai (About)', 'tags' => ['Groom', 'Bride', 'Nama Orang Tua', 'Quotes Cinta']],
        'story' => ['anchor' => '#story', 'subtitle' => 'Our Story & Kisah Cinta', 'tags' => ['Headline Cerita', 'Paragraf Intro', 'Milestone Timeline', 'Quote Section']],
        'acara' => ['anchor' => '#event', 'subtitle' => 'Waktu & Lokasi Acara', 'tags' => ['Akad Nikah', 'Resepsi', 'Venue & Alamat', 'Google Maps']],
        'galeri' => ['anchor' => '#gallery', 'subtitle' => 'Galeri Foto & Momen', 'tags' => ['Headline Galeri', 'Hero Galeri', 'Foto Bento', 'Kategori & Caption']],
        'penutup' => ['anchor' => '#rsvp', 'subtitle' => 'RSVP, Ucapan & Penutup', 'tags' => ['Doa & Ucapan', 'DANA & SeaBank', 'Quote Syukur', 'Footer Penutup']],
    ];
@endphp

@section('content')
    {{-- Hero Hub Banner --}}
    <div class="bg-cardBg rounded-3xl p-8 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
        <img src="{{ asset('aset/section3_bunga.webp') }}" alt="" class="absolute -top-6 right-6 w-36 opacity-15 pointer-events-none">

        <div class="relative z-10 max-w-2xl">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#EBEFE8] text-moss text-xs font-medium mb-3">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                <span>CMS Terintegrasi Laravel</span>
            </div>
            <h2 class="font-display text-3xl sm:text-4xl font-semibold text-inkAdmin leading-tight">
                Pusat Pengelolaan 6 Section Website Undangan
            </h2>
            <p class="text-xs sm:text-sm text-inkMuted mt-2 leading-relaxed">
                Pilih subhalaman di bawah untuk mengedit masing-masing section. Setiap perubahan teks, gambar, jadwal, lokasi peta, dan kutipan langsung tersimpan ke database dan tampil otomatis di halaman publik.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 relative z-10 w-full md:w-auto">
            <a href="{{ route('invitation.show') }}" target="_blank" class="inline-flex items-center justify-center space-x-2 px-5 py-3 rounded-2xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium transition shadow-sm text-center">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Buka Website Undangan</span>
            </a>
        </div>
    </div>

    {{-- Quick Metrics 3 Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-admin.stat-card variant="wide" label="Status Integrasi" value="100% Dinamis" subtext="Semua section terhubung DB" subtext-color="text-emerald-700" icon="check-circle-2" />
        <x-admin.stat-card variant="wide" label="Jumlah Section" value="6 Subhalaman" subtext="Section 1 s.d. Section 6" icon="layout-grid" />
        <x-admin.stat-card variant="wide" label="Penyimpanan" value="Persisten" subtext="Eloquent ORM + Storage Public" icon="refresh-cw" />
    </div>

    {{-- 6 Section Hub Cards --}}
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-display text-2xl font-semibold text-inkAdmin">Daftar Subhalaman Pengelola Section</h3>
                <p class="text-xs text-inkMuted mt-0.5">Klik subhalaman untuk melihat dan mengedit konten spesifik masing-masing section.</p>
            </div>
            <span class="text-xs text-inkMuted font-medium bg-cardBg border border-borderSoft px-3 py-1.5 rounded-full">
                Total: {{ count($sections) }} Section
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($sections as $sec)
                @php
                    $meta = $metaBySlug[$sec['slug']] ?? ['anchor' => '', 'subtitle' => $sec['name'], 'tags' => []];
                    $editUrl = route($sec['route'], $sec['route_params']);
                @endphp
                <div class="bg-cardBg rounded-3xl border border-borderSoft/80 overflow-hidden shadow-[0_2px_15px_rgba(40,51,34,0.03)] flex flex-col justify-between hover:shadow-md transition group">
                    <div>
                        <div class="h-44 w-full bg-[#D8E4D5] relative overflow-hidden">
                            <img src="{{ $sec['thumbnail'] }}" data-fallback="{{ asset('aset/background_herosection(desktop).webp') }}" alt="{{ $sec['name'] }}" class="w-full h-full object-cover {{ $sec['slug'] === 'pengantin' ? 'object-top' : '' }} group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-moss/80 backdrop-blur-sm text-adminBg font-mono text-[11px] font-semibold">
                                Section 0{{ $sec['number'] }}
                            </span>
                            <span class="absolute bottom-3 left-4 text-white text-xs font-medium drop-shadow">
                                {{ $meta['subtitle'] }}
                            </span>
                        </div>

                        <div class="p-6">
                            <h4 class="font-display text-xl font-semibold text-inkAdmin mb-1.5">{{ $sec['name'] }}</h4>
                            <p class="text-xs text-inkMuted leading-relaxed mb-4">{{ $sec['description'] }}</p>
                            <div class="flex flex-wrap gap-1.5 mb-2">
                                @foreach ($meta['tags'] as $tag)
                                    <span class="px-2.5 py-0.5 rounded-md bg-cardMuted text-[10px] text-inkMuted border border-borderSoft">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="p-6 pt-4 border-t border-borderSoft/60 flex items-center justify-between gap-3 mt-auto">
                        <a href="{{ route('invitation.show') . $meta['anchor'] }}" target="_blank" class="px-3 py-2 rounded-xl bg-cardMuted hover:bg-[#F0EEE2] text-xs font-medium text-inkAdmin border border-borderSoft transition flex items-center space-x-1.5">
                            <i data-lucide="eye" class="w-3.5 h-3.5 text-inkMuted"></i>
                            <span>Preview</span>
                        </a>
                        <a href="{{ $editUrl }}" class="flex-1 py-2.5 px-4 rounded-xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium transition text-center shadow-sm flex items-center justify-center space-x-2">
                            <span>Kelola Section {{ $sec['number'] }}</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
