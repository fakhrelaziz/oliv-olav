@extends('layouts.admin', ['pageHeading' => 'Dashboard', 'previewUrl' => route('invitation.show')])

@section('title', 'Dashboard Overview — REL | LIV')

@section('content')
    {{-- SECTION 1: Welcome & Completion Progress + Live Preview Box --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7 bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] flex flex-col justify-between">
            <div>
                <h2 class="font-display text-3xl font-semibold text-inkAdmin leading-tight">
                    Selamat datang, {{ auth()->user()?->name ?? 'Admin' }}!
                </h2>
                <p class="text-xs text-inkMuted mt-1 mb-5">Kelola undangan pernikahan Anda dengan mudah.</p>

                <a href="{{ route('admin.konten.index') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium transition shadow-sm">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                    <span>Lanjutkan Edit Undangan</span>
                </a>
            </div>

            <div class="mt-8 pt-6 border-t border-borderSoft/60">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-xs font-semibold text-inkAdmin">Kelengkapan Undangan</span>
                    <span class="text-xs font-bold text-moss">{{ $completion['completed'] }}/{{ $completion['total'] }}</span>
                </div>

                <div class="w-full bg-[#EBE9DE] h-2 rounded-full overflow-hidden mb-6">
                    <div class="bg-moss h-full rounded-full transition-all duration-500" style="width: {{ $completion['percent'] }}%;"></div>
                </div>

                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 text-center">
                    @foreach ($completion['steps'] as $step)
                        <a href="{{ $step['route'] }}" class="flex flex-col items-center group">
                            @if ($step['done'])
                                <div class="w-9 h-9 rounded-full bg-[#E5ECE0] text-[#345230] flex items-center justify-center mb-1.5 shadow-sm group-hover:scale-105 transition">
                                    <i data-lucide="check" class="w-4 h-4 stroke-[2.5]"></i>
                                </div>
                                <span class="text-[11px] font-medium text-inkAdmin">{{ $step['label'] }}</span>
                            @else
                                <div class="w-9 h-9 rounded-full bg-[#ECEAE0] text-gray-400 flex items-center justify-center mb-1.5 group-hover:scale-105 transition">
                                    <i data-lucide="circle" class="w-3.5 h-3.5"></i>
                                </div>
                                <span class="text-[11px] font-medium text-inkMuted">{{ $step['label'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-5 bg-cardBg rounded-3xl p-6 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] flex flex-col justify-between">
            <div class="relative w-full h-48 sm:h-56 rounded-2xl overflow-hidden bg-[#D7E3D4] flex items-center justify-center border border-borderSoft group">
                <img src="{{ $heroPreview }}" data-fallback="{{ asset('aset/background_herosection(desktop).webp') }}" alt="Wedding Hero Preview" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition flex items-center justify-center">
                    <a href="{{ route('invitation.show') }}" target="_blank" class="px-4 py-2 rounded-full bg-white/90 backdrop-blur text-xs font-semibold text-inkAdmin shadow-md hover:bg-white transition flex items-center space-x-1.5">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        <span>Preview Website Undangan</span>
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 mt-2 border-t border-borderSoft/60">
                <div>
                    <p class="text-xs font-semibold text-inkAdmin">Tema Botanical Luxury</p>
                    <p class="text-[11px] text-inkMuted">{{ config('app.name', 'Fakhrel & Olivia') }}</p>
                </div>
                <a href="{{ route('admin.konten.index') }}" class="text-xs font-medium text-moss hover:underline">Kelola &rarr;</a>
            </div>
        </div>
    </div>

    {{-- SECTION 2: 3 Quick Metric Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-admin.stat-card
            variant="wide"
            label="Pengunjung"
            :value="$visitStats['total_display']"
            :subtext="$visitStats['growth']"
            :subtext-color="$visitStats['today'] > 0 ? 'text-emerald-700' : 'text-inkMuted'"
            icon="users"
        />

        <x-admin.stat-card
            variant="wide"
            label="Data Tamu"
            :value="$guestStats['total_tamu']"
            :subtext="$guestStats['hadir'] . ' Hadir • ' . $guestStats['tidak_hadir'] . ' Tidak • ' . $guestStats['belum_konfirmasi'] . ' Belum'"
            icon="mail-check"
        />

        <x-admin.stat-card
            variant="wide"
            label="Ucapan"
            :value="$rsvpStats['total_ucapan']"
            subtext="Doa restu dari para sahabat"
            icon="message-square"
        />
    </div>

    {{-- SECTION 3: Recent RSVP & Quick Event Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7 bg-cardBg rounded-3xl p-6 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)]">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-display text-xl font-semibold text-inkAdmin">Recent RSVP</h3>
                <a href="{{ route('admin.rsvp.index') }}" class="text-xs font-medium text-inkMuted hover:text-moss transition">Lihat Semua</a>
            </div>

            <div class="space-y-3.5">
                @forelse ($recentWishes as $wish)
                    <div class="flex items-center justify-between gap-3 p-3 rounded-2xl hover:bg-cardMuted transition border border-transparent hover:border-borderSoft/60">
                        <div class="flex items-center space-x-3.5 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-adminBg border border-borderSoft flex items-center justify-center text-inkAdmin font-semibold text-xs shrink-0">
                                {{ $wish->initials }}
                            </div>
                            <div class="min-w-0">
                                <span class="text-xs font-semibold text-inkAdmin block truncate">{{ $wish->nama_pengirim }}</span>
                                <span class="text-[11px] text-inkMuted line-clamp-1 max-w-xs">{{ $wish->pesan_ucapan }}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 shrink-0">
                            <x-admin.status-badge :status="$wish->status_kehadiran" />
                            <span class="text-[11px] text-inkMuted font-mono whitespace-nowrap hidden sm:inline">{{ $wish->formatted_date }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-inkMuted text-xs">
                        <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 opacity-40"></i>
                        <span>Belum ada konfirmasi kehadiran dari tamu.</span>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-5 bg-cardBg rounded-3xl p-6 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)]">
            <h3 class="font-display text-xl font-semibold text-inkAdmin mb-5">Info Acara</h3>

            <div class="grid grid-cols-3 gap-3.5">
                <a href="{{ route('admin.konten.edit', 'acara') }}" class="p-4 rounded-2xl bg-cardMuted hover:bg-[#F2EFE6] border border-borderSoft/70 flex flex-col items-center justify-center text-center group transition">
                    <div class="w-12 h-12 rounded-full bg-white text-moss flex items-center justify-center mb-3 shadow-sm group-hover:scale-105 transition">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                    </div>
                    <span class="text-xs font-semibold text-inkAdmin block">Lokasi</span>
                    <span class="text-[10px] text-inkMuted mt-0.5 line-clamp-1">{{ $eventSummary['venue_name'] }}</span>
                </a>

                <a href="{{ route('admin.konten.edit', 'acara') }}" class="p-4 rounded-2xl bg-cardMuted hover:bg-[#F2EFE6] border border-borderSoft/70 flex flex-col items-center justify-center text-center group transition">
                    <div class="w-12 h-12 rounded-full bg-white text-moss flex items-center justify-center mb-3 shadow-sm group-hover:scale-105 transition">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <span class="text-xs font-semibold text-inkAdmin block">Tanggal</span>
                    <span class="text-[10px] text-inkMuted mt-0.5 line-clamp-1">{{ $eventSummary['date'] }}</span>
                </a>

                <a href="{{ route('admin.konten.index') }}" class="p-4 rounded-2xl bg-cardMuted hover:bg-[#F2EFE6] border border-borderSoft/70 flex flex-col items-center justify-center text-center group transition">
                    <div class="w-12 h-12 rounded-full bg-white text-moss flex items-center justify-center mb-3 shadow-sm group-hover:scale-105 transition">
                        <i data-lucide="layers" class="w-5 h-5"></i>
                    </div>
                    <span class="text-xs font-semibold text-inkAdmin block">Kelola Section</span>
                    <span class="text-[10px] text-inkMuted mt-0.5">6 Section</span>
                </a>
            </div>

            <div class="mt-6 p-4 rounded-2xl bg-[#F4F6F1] border border-[#DEE7D9] flex items-start space-x-3">
                <i data-lucide="sparkles" class="w-4 h-4 text-moss flex-shrink-0 mt-0.5"></i>
                <p class="text-[11px] text-inkAdmin leading-relaxed">
                    <strong>Tips Admin:</strong> Salin tautan personal setiap tamu di halaman
                    <a href="{{ route('admin.tamu.index') }}" class="text-moss font-semibold underline">Data Tamu</a>
                    agar nama mereka langsung tampil di cover undangan.
                </p>
            </div>
        </div>
    </div>
@endsection
