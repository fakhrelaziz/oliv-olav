@extends('layouts.admin', ['containerClass' => 'max-w-[1200px]'])

@section('title', 'Kelola Section 2: Profil Mempelai — REL | LIV')

@section('topbar')
    <x-admin.topbar
        title="Profil Kedua Mempelai"
        breadcrumb="Section 2: Mempelai (About)"
        :back-url="route('admin.konten.index')"
        :preview-url="route('invitation.show') . '#about'"
        submit-form-id="formSectionMempelai"
    />
@endsection

@section('content')
    <x-admin.section-editor-header
        badge="Section 02"
        title="Pengaturan Profil Pengantin Pria & Wanita"
        description="Kelola nama lengkap, nama orang tua, media sosial Instagram, foto kedua mempelai, serta kalimat puitis pembuka dan quote penutup Section 2."
        :updated-at="$section['updated_at']"
    />

    <form id="formSectionMempelai" action="{{ route('admin.konten.update', 'pengantin') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Card 1: Headline & Pengantar --}}
        <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-5">
            <h3 class="font-display text-xl font-semibold text-inkAdmin border-b border-borderSoft/60 pb-3 flex items-center space-x-2">
                <i data-lucide="quote" class="w-4 h-4 text-moss"></i>
                <span>Headline &amp; Pengantar Section 2</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="headline" class="block text-xs font-semibold text-inkAdmin mb-1.5">Headline Pembuka</label>
                    <textarea id="headline" name="headline" rows="3" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('headline', $data['headline']) }}</textarea>
                    <span class="text-[10px] text-inkMuted mt-1 block">Gunakan baris baru untuk memisahkan baris judul</span>
                </div>

                <div class="space-y-3">
                    <div>
                        <label for="intro_1" class="block text-xs font-semibold text-inkAdmin mb-1">Paragraf Pengantar 1</label>
                        <textarea id="intro_1" name="intro_1" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('intro_1', $data['intro_1']) }}</textarea>
                    </div>
                    <div>
                        <label for="intro_2" class="block text-xs font-semibold text-inkAdmin mb-1">Paragraf Pengantar 2</label>
                        <textarea id="intro_2" name="intro_2" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('intro_2', $data['intro_2']) }}</textarea>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <x-admin.image-upload-field
                        name="photo_couple"
                        label="Foto Kolase Pasangan (Kanan Atas Section 2)"
                        :value="old('photo_couple', $data['photo_couple'])"
                        :default-url="$defaults['photo_couple']"
                        prefix="couple"
                        button-label="Pilih / Upload Foto Pasangan"
                        thumb-class="w-24 h-16"
                        section-slug="pengantin"
                        field-key="photo_couple"
                    />
                </div>
            </div>
        </div>

        {{-- Card 2: Profil Groom & Bride --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Groom --}}
            <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-4">
                <div class="flex items-center justify-between border-b border-borderSoft/60 pb-3">
                    <h4 class="font-display text-lg font-semibold text-inkAdmin flex items-center space-x-2">
                        <i data-lucide="user" class="w-4 h-4 text-moss"></i>
                        <span>Mempelai Pria (Groom)</span>
                    </h4>
                    <span class="px-2 py-0.5 rounded-full bg-[#EBEFE8] text-moss text-[10px] font-medium">Groom</span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="groom_nickname" class="block text-xs font-semibold text-inkAdmin mb-1">Nama Panggilan</label>
                        <input type="text" id="groom_nickname" name="groom_nickname" value="{{ old('groom_nickname', $data['groom_nickname']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition">
                    </div>
                    <div>
                        <label for="groom_fullname" class="block text-xs font-semibold text-inkAdmin mb-1">Nama Belakang / Lengkap</label>
                        <input type="text" id="groom_fullname" name="groom_fullname" value="{{ old('groom_fullname', $data['groom_fullname']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition">
                    </div>
                </div>

                <div>
                    <label for="groom_parents" class="block text-xs font-semibold text-inkAdmin mb-1">Nama Kedua Orang Tua</label>
                    <textarea id="groom_parents" name="groom_parents" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('groom_parents', $data['groom_parents']) }}</textarea>
                </div>

                <div>
                    <label for="groom_instagram" class="block text-xs font-semibold text-inkAdmin mb-1">Tautan Akun Instagram</label>
                    <input type="text" id="groom_instagram" name="groom_instagram" value="{{ old('groom_instagram', $data['groom_instagram']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition" placeholder="https://instagram.com/username">
                </div>

                <x-admin.image-upload-field
                    name="groom_photo"
                    label="Foto Mempelai Pria"
                    :value="old('groom_photo', $data['groom_photo'])"
                    :default-url="$defaults['groom_photo']"
                    prefix="groom"
                    button-label="Upload Foto Mempelai Pria"
                    thumb-class="w-16 h-20"
                    section-slug="pengantin"
                    field-key="groom_photo"
                />
            </div>

            {{-- Bride --}}
            <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-4">
                <div class="flex items-center justify-between border-b border-borderSoft/60 pb-3">
                    <h4 class="font-display text-lg font-semibold text-inkAdmin flex items-center space-x-2">
                        <i data-lucide="user-check" class="w-4 h-4 text-moss"></i>
                        <span>Mempelai Wanita (Bride)</span>
                    </h4>
                    <span class="px-2 py-0.5 rounded-full bg-[#EBEFE8] text-moss text-[10px] font-medium">Bride</span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="bride_nickname" class="block text-xs font-semibold text-inkAdmin mb-1">Nama Panggilan</label>
                        <input type="text" id="bride_nickname" name="bride_nickname" value="{{ old('bride_nickname', $data['bride_nickname']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition">
                    </div>
                    <div>
                        <label for="bride_fullname" class="block text-xs font-semibold text-inkAdmin mb-1">Nama Belakang / Lengkap</label>
                        <input type="text" id="bride_fullname" name="bride_fullname" value="{{ old('bride_fullname', $data['bride_fullname']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition">
                    </div>
                </div>

                <div>
                    <label for="bride_parents" class="block text-xs font-semibold text-inkAdmin mb-1">Nama Kedua Orang Tua</label>
                    <textarea id="bride_parents" name="bride_parents" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('bride_parents', $data['bride_parents']) }}</textarea>
                </div>

                <div>
                    <label for="bride_instagram" class="block text-xs font-semibold text-inkAdmin mb-1">Tautan Akun Instagram</label>
                    <input type="text" id="bride_instagram" name="bride_instagram" value="{{ old('bride_instagram', $data['bride_instagram']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-2.5 text-xs focus:outline-none focus:border-moss transition" placeholder="https://instagram.com/username">
                </div>

                <x-admin.image-upload-field
                    name="bride_photo"
                    label="Foto Mempelai Wanita"
                    :value="old('bride_photo', $data['bride_photo'])"
                    :default-url="$defaults['bride_photo']"
                    prefix="bride"
                    button-label="Upload Foto Mempelai Wanita"
                    thumb-class="w-16 h-20"
                    section-slug="pengantin"
                    field-key="bride_photo"
                />
            </div>
        </div>

        {{-- Card 3: Quote Penutup --}}
        <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-4">
            <h3 class="font-display text-xl font-semibold text-inkAdmin border-b border-borderSoft/60 pb-3 flex items-center space-x-2">
                <i data-lucide="sparkles" class="w-4 h-4 text-moss"></i>
                <span>Quote Penutup Bagian Bawah Section 2</span>
            </h3>

            <div>
                <label for="bottom_quote" class="block text-xs font-semibold text-inkAdmin mb-1.5">Quote Mutiara Cinta</label>
                <textarea id="bottom_quote" name="bottom_quote" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('bottom_quote', $data['bottom_quote']) }}</textarea>
                <span class="text-[10px] text-inkMuted mt-1 block">Ditampilkan di area gelap bawah Section 2 dengan ornamen dedaunan</span>
            </div>
        </div>

        {{-- Action Bar --}}
        <div class="flex flex-wrap items-center justify-between gap-3 pt-4 pb-12">
            <a href="{{ route('admin.konten.index') }}" class="inline-flex items-center space-x-2 text-xs font-medium text-inkMuted hover:text-moss transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Pusat Konten</span>
            </a>

            <div class="flex items-center space-x-3">
                <button type="submit" form="resetPengantinSectionForm" data-confirm="Kembalikan seluruh konten Section 2 (Mempelai) ke pengaturan default?" class="inline-flex items-center space-x-2 px-4 py-3 rounded-2xl bg-white hover:bg-rose-50 border border-borderSoft text-xs font-medium text-inkMuted hover:text-rose-600 transition">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    <span>Reset ke Default</span>
                </button>
                <button type="submit" class="inline-flex items-center space-x-2 px-6 py-3 rounded-2xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium transition shadow-md hover:shadow-lg">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Perubahan Section 2</span>
                </button>
            </div>
        </div>
    </form>

    <form id="resetPengantinSectionForm" action="{{ route('admin.konten.reset', 'pengantin') }}" method="POST" class="hidden">
        @csrf
    </form>
@endsection
