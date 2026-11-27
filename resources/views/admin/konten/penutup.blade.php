@extends('layouts.admin', ['containerClass' => 'max-w-[1200px]'])

@section('title', 'Kelola Section 6: RSVP & Penutup — REL | LIV')

@section('topbar')
    <x-admin.topbar
        title="RSVP, Ucapan & Penutup Undangan"
        breadcrumb="Section 6: RSVP, Ucapan & Penutup"
        :back-url="route('admin.konten.index')"
        :preview-url="route('invitation.show') . '#rsvp'"
        submit-form-id="formSectionPenutup"
    />
@endsection

@section('content')
    <x-admin.section-editor-header
        badge="Section 06"
        title="Pengaturan Doa, Ucapan, Tanda Kasih & Penutup"
        description="Kelola teks pengantar buku tamu, judul formulir doa & ucapan, QRIS / rekening tanda kasih, hingga kata-kata mutiara penutup website undangan."
        :updated-at="$section['updated_at']"
    />

    <form id="formSectionPenutup" action="{{ route('admin.konten.update', 'penutup') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Card 1: Header Doa & Ucapan --}}
        <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-5">
            <h3 class="font-display text-xl font-semibold text-inkAdmin border-b border-borderSoft/60 pb-3 flex items-center space-x-2">
                <i data-lucide="message-square" class="w-4 h-4 text-moss"></i>
                <span>Header Doa &amp; Ucapan</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="headline" class="block text-xs font-semibold text-inkAdmin mb-1.5">Judul Headline Section</label>
                    <textarea id="headline" name="headline" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('headline', $data['headline']) }}</textarea>
                    <span class="text-[10px] text-inkMuted mt-1 block">Gunakan baris baru untuk memisahkan baris judul</span>
                </div>

                <div>
                    <label for="intro" class="block text-xs font-semibold text-inkAdmin mb-1.5">Teks Pengantar Doa &amp; Ucapan</label>
                    <textarea id="intro" name="intro" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('intro', $data['intro']) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <x-admin.image-upload-field
                        name="banner_photo"
                        label="Foto Banner Kanan Atas Section 6"
                        :value="old('banner_photo', $data['banner_photo'])"
                        :default-url="$defaults['banner_photo']"
                        prefix="rsvp_banner"
                        button-label="Pilih / Upload Foto Banner"
                        thumb-class="w-24 h-16"
                        section-slug="penutup"
                        field-key="banner_photo"
                    />
                </div>
            </div>
        </div>

        {{-- Card 2: Form Ucapan & Box Tanda Kasih (QRIS & Rekening) --}}
        <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-5">
            <h3 class="font-display text-xl font-semibold text-inkAdmin border-b border-borderSoft/60 pb-3 flex items-center space-x-2">
                <i data-lucide="qr-code" class="w-4 h-4 text-moss"></i>
                <span>Form Ucapan &amp; Box Tanda Kasih (QRIS / Rekening)</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="form_title" class="block text-xs font-semibold text-inkAdmin mb-1.5">Judul Form Kirim Ucapan</label>
                    <input type="text" id="form_title" name="form_title" value="{{ old('form_title', $data['form_title']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div>
                    <label for="form_intro" class="block text-xs font-semibold text-inkAdmin mb-1.5">Subteks Form Kirim Ucapan</label>
                    <input type="text" id="form_intro" name="form_intro" value="{{ old('form_intro', $data['form_intro']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                </div>

                <div>
                    <label for="confirm_box_title" class="block text-xs font-semibold text-inkAdmin mb-1.5">Judul Box Tanda Kasih (QRIS)</label>
                    <textarea id="confirm_box_title" name="confirm_box_title" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('confirm_box_title', $data['confirm_box_title']) }}</textarea>
                </div>

                <div>
                    <label for="confirm_box_intro" class="block text-xs font-semibold text-inkAdmin mb-1.5">Teks Pengantar Box Tanda Kasih</label>
                    <textarea id="confirm_box_intro" name="confirm_box_intro" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('confirm_box_intro', $data['confirm_box_intro']) }}</textarea>
                </div>
            </div>

            <div class="border-t border-borderSoft/60 pt-5 grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                <div class="md:col-span-5">
                    <x-admin.image-upload-field
                        name="qris_photo"
                        label="Gambar Kode QRIS"
                        :value="old('qris_photo', $data['qris_photo'])"
                        :default-url="$defaults['qris_photo']"
                        prefix="rsvp_qris"
                        button-label="Upload QRIS"
                        hint="Gunakan gambar QRIS rasio 1:1 yang jelas dipindai"
                        layout="square"
                        section-slug="penutup"
                        field-key="qris_photo"
                    />
                </div>

                <div class="md:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="qris_name" class="block text-xs font-semibold text-inkAdmin mb-1.5">Nama Merchant / Atas Nama QRIS</label>
                        <input type="text" id="qris_name" name="qris_name" value="{{ old('qris_name', $data['qris_name']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                    </div>
                    <div>
                        <label for="bank_name" class="block text-xs font-semibold text-inkAdmin mb-1.5">Nama Bank / E-Wallet</label>
                        <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $data['bank_name']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                    </div>
                    <div>
                        <label for="bank_number" class="block text-xs font-semibold text-inkAdmin mb-1.5">Nomor Rekening</label>
                        <input type="text" id="bank_number" name="bank_number" value="{{ old('bank_number', $data['bank_number']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition font-mono">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="bank_holder" class="block text-xs font-semibold text-inkAdmin mb-1.5">Atas Nama Pemilik Rekening</label>
                        <input type="text" id="bank_holder" name="bank_holder" value="{{ old('bank_holder', $data['bank_holder']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Footer & Quote Penutup --}}
        <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] space-y-5">
            <h3 class="font-display text-xl font-semibold text-inkAdmin border-b border-borderSoft/60 pb-3 flex items-center space-x-2">
                <i data-lucide="heart" class="w-4 h-4 text-moss"></i>
                <span>Kata Mutiara Penutup &amp; Footer</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="thanks_script" class="block text-xs font-semibold text-inkAdmin mb-1.5">Teks Kaligrafi Terima Kasih</label>
                    <textarea id="thanks_script" name="thanks_script" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('thanks_script', $data['thanks_script']) }}</textarea>
                    <span class="text-[10px] text-inkMuted mt-1 block">Teks sambung miring penutup</span>
                </div>

                <div>
                    <label for="closing_quote" class="block text-xs font-semibold text-inkAdmin mb-1.5">Quote Penutup</label>
                    <textarea id="closing_quote" name="closing_quote" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('closing_quote', $data['closing_quote']) }}</textarea>
                </div>

                <div>
                    <label for="closing_subtext" class="block text-xs font-semibold text-inkAdmin mb-1.5">Subteks Terima Kasih</label>
                    <textarea id="closing_subtext" name="closing_subtext" rows="2" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition leading-relaxed resize-none">{{ old('closing_subtext', $data['closing_subtext']) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="footer_couple" class="block text-xs font-semibold text-inkAdmin mb-1.5">Nama Pasangan di Footer</label>
                        <input type="text" id="footer_couple" name="footer_couple" value="{{ old('footer_couple', $data['footer_couple']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                    </div>
                    <div>
                        <label for="footer_date" class="block text-xs font-semibold text-inkAdmin mb-1.5">Tanggal di Footer</label>
                        <input type="text" id="footer_date" name="footer_date" value="{{ old('footer_date', $data['footer_date']) }}" class="w-full bg-cardMuted border border-borderSoft rounded-xl p-3 text-xs focus:outline-none focus:border-moss transition">
                    </div>
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
                <button type="submit" form="resetPenutupSectionForm" data-confirm="Kembalikan seluruh konten Section 6 (RSVP & Penutup) ke pengaturan default?" class="inline-flex items-center space-x-2 px-4 py-3 rounded-2xl bg-white hover:bg-rose-50 border border-borderSoft text-xs font-medium text-inkMuted hover:text-rose-600 transition">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    <span>Reset ke Default</span>
                </button>
                <button type="submit" class="inline-flex items-center space-x-2 px-6 py-3 rounded-2xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium transition shadow-md hover:shadow-lg">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Perubahan Section 6</span>
                </button>
            </div>
        </div>
    </form>

    <form id="resetPenutupSectionForm" action="{{ route('admin.konten.reset', 'penutup') }}" method="POST" class="hidden">
        @csrf
    </form>
@endsection
