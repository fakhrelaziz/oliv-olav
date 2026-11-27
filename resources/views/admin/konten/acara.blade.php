@extends('layouts.admin', ['containerClass' => 'max-w-[1200px]'])

@section('title', 'Kelola Section 4: Waktu & Lokasi Acara — REL | LIV')

@section('topbar')
    <x-admin.topbar
        title="Waktu & Lokasi Acara"
        breadcrumb="Section 4: Waktu & Lokasi Acara"
        :back-url="route('admin.konten.index')"
        :preview-url="route('invitation.show') . '#event'"
        submit-form-id="formSectionAcara"
    />
@endsection

@section('content')
    <x-admin.section-editor-header
        badge="Section 04"
        title="Pengaturan Jadwal Acara & Peta Lokasi"
        description="Kelola detail pelaksanaan Akad Nikah, Resepsi Pernikahan, serta integrasi peta lokasi Google Maps interaktif yang memandu tamu ke venue acara."
        :updated-at="$section['updated_at']"
    />

    <form id="formSectionAcara" action="{{ route('admin.konten.update', 'acara') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Card 1: Header Acara --}}
        <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-5">
            <h3 class="font-display text-xl font-semibold text-inkAdmin border-b border-borderSoft/60 pb-3 flex items-center space-x-2">
                <i data-lucide="calendar" class="w-4 h-4 text-moss"></i>
                <span>Header Section Acara</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="headline" class="block text-xs font-semibold text-inkAdmin mb-1.5">Judul Utama Section</label>
                    <textarea id="headline" name="headline" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('headline', $data['headline']) }}</textarea>
                    <span class="text-[10px] text-inkMuted mt-1 block">Gunakan baris baru untuk memisahkan baris judul</span>
                </div>

                <div>
                    <label for="intro" class="block text-xs font-semibold text-inkAdmin mb-1.5">Teks Pengantar Undangan Acara</label>
                    <textarea id="intro" name="intro" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('intro', $data['intro']) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <x-admin.image-upload-field
                        name="header_photo"
                        label="Foto Banner Header Section 4"
                        :value="old('header_photo', $data['header_photo'])"
                        :default-url="$defaults['header_photo']"
                        prefix="event_header"
                        button-label="Upload Foto Header"
                        thumb-class="w-24 h-14"
                        section-slug="acara"
                        field-key="header_photo"
                    />
                </div>
            </div>
        </div>

        {{-- Card 2: Akad Nikah & Resepsi --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Akad Nikah --}}
            <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-4">
                <div class="flex items-center justify-between border-b border-borderSoft/60 pb-3">
                    <h4 class="font-display text-lg font-semibold text-inkAdmin flex items-center space-x-2">
                        <i data-lucide="gem" class="w-4 h-4 text-moss"></i>
                        <span>1. Akad Nikah</span>
                    </h4>
                    <span class="px-2 py-0.5 rounded-full bg-[#EBEFE8] text-moss text-[10px] font-medium">Sesi Sakral</span>
                </div>

                <div>
                    <label for="akad_name" class="block text-xs font-semibold text-inkAdmin mb-1">Nama Acara</label>
                    <input type="text" id="akad_name" name="akad_name" value="{{ old('akad_name', $data['akad_name']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="akad_date" class="block text-xs font-semibold text-inkAdmin mb-1">Hari &amp; Tanggal</label>
                        <input type="text" id="akad_date" name="akad_date" value="{{ old('akad_date', $data['akad_date']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition">
                    </div>
                    <div>
                        <label for="akad_time" class="block text-xs font-semibold text-inkAdmin mb-1">Waktu Pelaksanaan</label>
                        <input type="text" id="akad_time" name="akad_time" value="{{ old('akad_time', $data['akad_time']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition">
                    </div>
                </div>

                <div>
                    <label for="akad_place" class="block text-xs font-semibold text-inkAdmin mb-1">Nama Tempat / Gedung</label>
                    <input type="text" id="akad_place" name="akad_place" value="{{ old('akad_place', $data['akad_place']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div>
                    <label for="akad_address" class="block text-xs font-semibold text-inkAdmin mb-1">Alamat Lengkap</label>
                    <textarea id="akad_address" name="akad_address" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('akad_address', $data['akad_address']) }}</textarea>
                </div>

                <x-admin.image-upload-field
                    name="akad_photo"
                    label="Foto / Dekorasi Lengkung Akad"
                    :value="old('akad_photo', $data['akad_photo'])"
                    :default-url="$defaults['akad_photo']"
                    prefix="event_akad"
                    button-label="Upload Foto Akad"
                    thumb-class="w-14 h-20"
                    section-slug="acara"
                    field-key="akad_photo"
                />
            </div>

            {{-- Resepsi Pernikahan --}}
            <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-4">
                <div class="flex items-center justify-between border-b border-borderSoft/60 pb-3">
                    <h4 class="font-display text-lg font-semibold text-inkAdmin flex items-center space-x-2">
                        <i data-lucide="party-popper" class="w-4 h-4 text-moss"></i>
                        <span>2. Resepsi Pernikahan</span>
                    </h4>
                    <span class="px-2 py-0.5 rounded-full bg-[#EBEFE8] text-moss text-[10px] font-medium">Sesi Perayaan</span>
                </div>

                <div>
                    <label for="resepsi_name" class="block text-xs font-semibold text-inkAdmin mb-1">Nama Acara</label>
                    <input type="text" id="resepsi_name" name="resepsi_name" value="{{ old('resepsi_name', $data['resepsi_name']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="resepsi_date" class="block text-xs font-semibold text-inkAdmin mb-1">Hari &amp; Tanggal</label>
                        <input type="text" id="resepsi_date" name="resepsi_date" value="{{ old('resepsi_date', $data['resepsi_date']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition">
                    </div>
                    <div>
                        <label for="resepsi_time" class="block text-xs font-semibold text-inkAdmin mb-1">Waktu Pelaksanaan</label>
                        <input type="text" id="resepsi_time" name="resepsi_time" value="{{ old('resepsi_time', $data['resepsi_time']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition">
                    </div>
                </div>

                <div>
                    <label for="resepsi_place" class="block text-xs font-semibold text-inkAdmin mb-1">Nama Tempat / Gedung</label>
                    <input type="text" id="resepsi_place" name="resepsi_place" value="{{ old('resepsi_place', $data['resepsi_place']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div>
                    <label for="resepsi_address" class="block text-xs font-semibold text-inkAdmin mb-1">Alamat Lengkap</label>
                    <textarea id="resepsi_address" name="resepsi_address" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('resepsi_address', $data['resepsi_address']) }}</textarea>
                </div>

                <x-admin.image-upload-field
                    name="resepsi_photo"
                    label="Foto / Dekorasi Lengkung Resepsi"
                    :value="old('resepsi_photo', $data['resepsi_photo'])"
                    :default-url="$defaults['resepsi_photo']"
                    prefix="event_resepsi"
                    button-label="Upload Foto Resepsi"
                    thumb-class="w-14 h-20"
                    section-slug="acara"
                    field-key="resepsi_photo"
                />
            </div>
        </div>

        {{-- Card 3: Venue & Google Maps --}}
        <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-5">
            <h3 class="font-display text-xl font-semibold text-inkAdmin border-b border-borderSoft/60 pb-3 flex items-center space-x-2">
                <i data-lucide="map-pin" class="w-4 h-4 text-moss"></i>
                <span>Lokasi Venue &amp; Peta Google Maps</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="venue_headline" class="block text-xs font-semibold text-inkAdmin mb-1.5">Headline Area Lokasi</label>
                    <input type="text" id="venue_headline" name="venue_headline" value="{{ old('venue_headline', $data['venue_headline']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div>
                    <label for="venue_name" class="block text-xs font-semibold text-inkAdmin mb-1.5">Nama Tempat / Kafe / Venue</label>
                    <input type="text" id="venue_name" name="venue_name" value="{{ old('venue_name', $data['venue_name']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div class="md:col-span-2">
                    <label for="venue_intro" class="block text-xs font-semibold text-inkAdmin mb-1.5">Teks Pengantar Lokasi Venue</label>
                    <textarea id="venue_intro" name="venue_intro" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('venue_intro', $data['venue_intro']) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="venue_address" class="block text-xs font-semibold text-inkAdmin mb-1.5">Alamat Lengkap Venue</label>
                    <input type="text" id="venue_address" name="venue_address" value="{{ old('venue_address', $data['venue_address']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div>
                    <label for="features_parking" class="block text-xs font-semibold text-inkAdmin mb-1.5">Info Fasilitas Parkir</label>
                    <input type="text" id="features_parking" name="features_parking" value="{{ old('features_parking', $data['features_parking']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div>
                    <label for="features_transit" class="block text-xs font-semibold text-inkAdmin mb-1.5">Info Akses Transportasi</label>
                    <input type="text" id="features_transit" name="features_transit" value="{{ old('features_transit', $data['features_transit']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div class="md:col-span-2">
                    <label for="maps_embed" class="block text-xs font-semibold text-inkAdmin mb-1.5">URL Embed Iframe Google Maps</label>
                    <input type="text" id="maps_embed" name="maps_embed" value="{{ old('maps_embed', $data['maps_embed']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition font-mono">
                    <span class="text-[10px] text-inkMuted mt-1 block">Tautan iframe embed peta Google Maps untuk menampilkan peta interaktif di website</span>
                </div>

                <div>
                    <label for="maps_link" class="block text-xs font-semibold text-inkAdmin mb-1.5">Link Direct Google Maps (Tombol Navigasi)</label>
                    <input type="text" id="maps_link" name="maps_link" value="{{ old('maps_link', $data['maps_link']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div>
                    <label for="routes_link" class="block text-xs font-semibold text-inkAdmin mb-1.5">Link Dapatkan Rute (Tombol Rute)</label>
                    <input type="text" id="routes_link" name="routes_link" value="{{ old('routes_link', $data['routes_link']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div class="md:col-span-2">
                    <x-admin.image-upload-field
                        name="venue_photo"
                        label="Foto Polaroid Venue / Suasana"
                        :value="old('venue_photo', $data['venue_photo'])"
                        :default-url="$defaults['venue_photo']"
                        prefix="event_venue"
                        button-label="Upload Foto Venue"
                        thumb-class="w-20 h-14"
                        section-slug="acara"
                        field-key="venue_photo"
                    />
                </div>

                <div class="md:col-span-2">
                    <label for="bottom_quote" class="block text-xs font-semibold text-inkAdmin mb-1.5">Quote Penutup Section 4</label>
                    <textarea id="bottom_quote" name="bottom_quote" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('bottom_quote', $data['bottom_quote']) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Action Bar --}}
        <div class="flex flex-wrap items-center justify-between gap-3 pt-4 pb-12">
            <a href="{{ route('admin.konten.index') }}" class="inline-flex items-center space-x-2 text-xs font-medium text-inkMuted hover:text-moss transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Pusat Konten</span>
            </a>

            <div class="flex items-center space-x-3">
                <button type="submit" form="resetAcaraSectionForm" data-confirm="Kembalikan seluruh konten Section 4 (Waktu & Lokasi Acara) ke pengaturan default?" class="inline-flex items-center space-x-2 px-4 py-3 rounded-2xl bg-white hover:bg-rose-50 border border-borderSoft text-xs font-medium text-inkMuted hover:text-rose-600 transition">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    <span>Reset ke Default</span>
                </button>
                <button type="submit" class="inline-flex items-center space-x-2 px-6 py-3 rounded-2xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium transition shadow-md hover:shadow-lg">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Perubahan Section 4</span>
                </button>
            </div>
        </div>
    </form>

    <form id="resetAcaraSectionForm" action="{{ route('admin.konten.reset', 'acara') }}" method="POST" class="hidden">
        @csrf
    </form>
@endsection
