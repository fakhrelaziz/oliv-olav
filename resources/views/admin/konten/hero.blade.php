@extends('layouts.admin', ['containerClass' => 'max-w-[1200px]'])

@section('title', 'Kelola Section 1: Hero & Pembuka — REL | LIV')

@section('topbar')
    <x-admin.topbar
        title="Hero & Pembuka Undangan"
        breadcrumb="Section 1: Hero & Pembuka"
        :back-url="route('admin.konten.index')"
        :preview-url="route('invitation.show') . '#home'"
        submit-form-id="formSectionHero"
    />
@endsection

@section('content')
    <x-admin.section-editor-header
        badge="Section 01"
        title="Pengaturan Hero & Pembuka"
        description="Section ini adalah tampilan pertama yang dilihat tamu saat membuka tautan undangan. Seluruh perubahan teks dan gambar langsung tersimpan ke basis data."
        :updated-at="$section['updated_at']"
    />

    <form id="formSectionHero" action="{{ route('admin.konten.update', 'hero') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Card 1: Teks Utama & Pasangan --}}
        <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-5">
            <h3 class="font-display text-xl font-semibold text-inkAdmin border-b border-borderSoft/60 pb-3 flex items-center space-x-2">
                <i data-lucide="type" class="w-4 h-4 text-moss"></i>
                <span>Teks Utama &amp; Judul Mempelai</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="tagline" class="block text-xs font-semibold text-inkAdmin mb-1.5">Tagline Atas</label>
                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $data['tagline']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition" placeholder="THE WEDDING OF">
                    <span class="text-[10px] text-inkMuted mt-1 block">Teks kecil di atas judul utama</span>
                </div>

                <div>
                    <label for="wedding_date" class="block text-xs font-semibold text-inkAdmin mb-1.5">Tanggal Pernikahan (Format Tampilan Hero)</label>
                    <input type="text" id="wedding_date" name="wedding_date" value="{{ old('wedding_date', $data['wedding_date']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition" placeholder="25 DESEMBER 2026">
                    <span class="text-[10px] text-inkMuted mt-1 block">Contoh: 25 DESEMBER 2026</span>
                </div>

                <div>
                    <label for="groom_nickname" class="block text-xs font-semibold text-inkAdmin mb-1.5">Nama Panggilan Mempelai Pria (Groom)</label>
                    <input type="text" id="groom_nickname" name="groom_nickname" value="{{ old('groom_nickname', $data['groom_nickname']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition" placeholder="Fakhrel">
                </div>

                <div>
                    <label for="bride_nickname" class="block text-xs font-semibold text-inkAdmin mb-1.5">Nama Panggilan Mempelai Wanita (Bride)</label>
                    <input type="text" id="bride_nickname" name="bride_nickname" value="{{ old('bride_nickname', $data['bride_nickname']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition" placeholder="Olivia">
                </div>

                <div class="md:col-span-2">
                    <label for="salam_text" class="block text-xs font-semibold text-inkAdmin mb-1.5">Teks Salam Pembuka Undangan</label>
                    <textarea id="salam_text" name="salam_text" rows="3" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none" placeholder="Tanpa mengurangi rasa hormat, kami mengundang Anda untuk hadir dalam hari bahagia kami.">{{ old('salam_text', $data['salam_text']) }}</textarea>
                    <span class="text-[10px] text-inkMuted mt-1 block">Ditampilkan di bawah nama tamu personal pada cover</span>
                </div>
            </div>
        </div>

        {{-- Card 2: Ornamen Kaligrafi & Lokasi Kota --}}
        <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-5">
            <h3 class="font-display text-xl font-semibold text-inkAdmin border-b border-borderSoft/60 pb-3 flex items-center space-x-2">
                <i data-lucide="feather" class="w-4 h-4 text-moss"></i>
                <span>Ornamen Kaligrafi &amp; Lokasi Kota</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="script_text" class="block text-xs font-semibold text-inkAdmin mb-1.5">Teks Script Kaligrafi Bawah</label>
                    <textarea id="script_text" name="script_text" rows="4" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('script_text', $data['script_text']) }}</textarea>
                    <span class="text-[10px] text-inkMuted mt-1 block">Teks tulisan sambung miring di kanan bawah hero (gunakan baris baru)</span>
                </div>

                <div>
                    <label for="city" class="block text-xs font-semibold text-inkAdmin mb-1.5">Kota / Lokasi Dasar</label>
                    <input type="text" id="city" name="city" value="{{ old('city', $data['city']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition" placeholder="JAKARTA, INDONESIA">
                    <span class="text-[10px] text-inkMuted mt-1 block">Teks kecil di sudut kanan atas cover &amp; kanan bawah hero</span>
                </div>
            </div>
        </div>

        {{-- Card 3: Background Hero Images --}}
        <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-5">
            <h3 class="font-display text-xl font-semibold text-inkAdmin border-b border-borderSoft/60 pb-3 flex items-center space-x-2">
                <i data-lucide="image" class="w-4 h-4 text-moss"></i>
                <span>Gambar Background Hero Cover</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-admin.image-upload-field
                    name="bg_desktop"
                    label="Background Versi Desktop (Layar Lebar)"
                    :value="old('bg_desktop', $data['bg_desktop'])"
                    :default-url="$defaults['bg_desktop']"
                    prefix="hero_desktop"
                    button-label="Upload Foto Desktop"
                    layout="banner"
                    section-slug="hero"
                    field-key="bg_desktop"
                />

                <x-admin.image-upload-field
                    name="bg_mobile"
                    label="Background Versi Mobile (Smartphone)"
                    :value="old('bg_mobile', $data['bg_mobile'])"
                    :default-url="$defaults['bg_mobile']"
                    prefix="hero_mobile"
                    button-label="Upload Foto Mobile"
                    layout="banner"
                    section-slug="hero"
                    field-key="bg_mobile"
                />
            </div>
        </div>

        {{-- Action Bar --}}
        <div class="flex flex-wrap items-center justify-between gap-3 pt-4 pb-12">
            <a href="{{ route('admin.konten.index') }}" class="inline-flex items-center space-x-2 text-xs font-medium text-inkMuted hover:text-moss transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Pusat Konten</span>
            </a>

            <div class="flex items-center space-x-3">
                <button type="submit" form="resetHeroSectionForm" data-confirm="Kembalikan seluruh konten Section 1 (Hero) ke pengaturan default?" class="inline-flex items-center space-x-2 px-4 py-3 rounded-2xl bg-white hover:bg-rose-50 border border-borderSoft text-xs font-medium text-inkMuted hover:text-rose-600 transition">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    <span>Reset ke Default</span>
                </button>
                <button type="submit" class="inline-flex items-center space-x-2 px-6 py-3 rounded-2xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium transition shadow-md hover:shadow-lg">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Perubahan Section 1</span>
                </button>
            </div>
        </div>
    </form>

    <form id="resetHeroSectionForm" action="{{ route('admin.konten.reset', 'hero') }}" method="POST" class="hidden">
        @csrf
    </form>
@endsection
