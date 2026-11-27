@extends('layouts.admin', ['pageHeading' => 'Galeri Foto', 'previewUrl' => route('invitation.show') . '#gallery'])

@section('title', 'Galeri Foto — REL | LIV')

@section('content')
    {{-- Breadcrumbs --}}
    <div class="flex items-center space-x-2 text-xs text-inkMuted">
        <a href="{{ route('admin.konten.index') }}" class="hover:text-moss">Kelola Konten</a>
        <span>/</span>
        <span class="font-semibold text-inkAdmin">Section 5: Galeri Foto</span>
    </div>

    {{-- Hero Banner --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] relative overflow-hidden flex flex-col justify-between">
            <img src="{{ asset('aset/section4_bunga.webp') }}" alt="" class="absolute -top-4 right-10 w-28 opacity-25 pointer-events-none">
            <div class="relative z-10">
                <span class="text-[11px] font-semibold uppercase tracking-widest text-moss">GALERI</span>
                <h2 class="font-display text-4xl font-semibold text-inkAdmin mt-1">{{ str_replace("\n", ' ', $header['headline']) }}</h2>
                <p class="text-xs text-inkMuted mt-1 mb-5 max-w-lg leading-relaxed">{{ $header['intro'] }}</p>

                <div class="flex flex-wrap items-center gap-2.5">
                    <button type="button" data-modal-open="editGalleryHeaderModal" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-cardMuted hover:bg-[#F0EEE2] border border-borderSoft text-xs font-medium text-inkAdmin transition shadow-sm">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-moss"></i>
                        <span>Ubah Headline &amp; Quote Section 5</span>
                    </button>

                    <form action="{{ route('admin.konten.reset', 'galeri') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" data-confirm="Kembalikan Header & Quote Galeri ke pengaturan default?" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-white hover:bg-rose-50 border border-borderSoft text-xs font-medium text-inkMuted hover:text-rose-600 transition shadow-sm">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                            <span>Reset Header</span>
                        </button>
                    </form>

                    <button type="button" data-modal-open="uploadPhotoModal" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium transition shadow-sm">
                        <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                        <span>Unggah Foto Baru</span>
                    </button>

                    <a href="{{ route('invitation.show') }}#gallery" target="_blank" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-cardBg border border-borderSoft text-xs font-medium text-inkAdmin hover:bg-cardMuted transition shadow-sm">
                        <i data-lucide="eye" class="w-3.5 h-3.5 text-inkMuted"></i>
                        <span>Pratinjau di Web</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 bg-cardBg rounded-3xl p-5 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] flex flex-col justify-center items-center text-center">
            <div class="w-full h-32 rounded-2xl overflow-hidden bg-[#D8E4D5] relative group">
                <img src="{{ $header['hero_photo'] }}" data-fallback="{{ asset('aset/section5_fotopengantin.webp') }}" alt="Preview Banner Galeri" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/25 flex items-center justify-center">
                    <a href="{{ route('invitation.show') }}#gallery" target="_blank" class="px-3.5 py-1.5 rounded-full bg-white/95 text-[11px] font-semibold text-inkAdmin shadow">Preview Website</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Category Filter Tabs --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2" data-filter-tabs>
            @foreach (['all' => 'Semua', 'prewedding' => 'Prewedding', 'acara' => 'Acara', 'keseharian' => 'Keseharian', 'lainnya' => 'Lainnya'] as $catKey => $catLabel)
                <button type="button"
                        data-gallery-filter="{{ $catKey }}"
                        class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $catKey === 'all' ? 'bg-moss text-adminBg shadow-sm' : 'bg-cardBg text-inkAdmin hover:bg-cardMuted border border-borderSoft' }}">
                    {{ $catLabel }}
                </button>
            @endforeach
        </div>

        <span class="text-xs text-inkMuted font-medium bg-cardBg border border-borderSoft px-3.5 py-1.5 rounded-full">
            Total: <strong class="text-inkAdmin">{{ $photos->count() }}</strong> Foto
        </span>
    </div>

    {{-- Photo Grid --}}
    <div id="adminGalleryGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
        @forelse ($photos as $photo)
            @php
                $imgUrl = \App\Services\MediaStorageService::normalizeAssetUrl($photo->foto_url, '/aset/section5_galerifoto.webp');
            @endphp
            <div data-gallery-item data-category="{{ $photo->kategori ?? 'prewedding' }}" class="group aspect-[4/3] rounded-2xl overflow-hidden bg-cardMuted border border-borderSoft shadow-sm relative">
                <img src="{{ $imgUrl }}" data-fallback="{{ asset('aset/section5_galerifoto.webp') }}" alt="{{ $photo->judul }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">

                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/45 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                    <div class="flex items-center space-x-2">
                        <button type="button" data-preview-image="{{ $imgUrl }}" data-preview-title="{{ $photo->judul }}" class="w-8 h-8 rounded-full bg-white/95 text-moss hover:bg-white flex items-center justify-center shadow" title="Perbesar">
                            <i data-lucide="maximize-2" class="w-4 h-4"></i>
                        </button>

                        <button type="button"
                                data-edit-modal="editGalleryModal"
                                data-edit-action="{{ route('admin.galeri.update', $photo) }}"
                                data-edit-payload="{{ json_encode([
                                    'judul' => $photo->judul,
                                    'kategori' => $photo->kategori,
                                    'urutan' => $photo->urutan,
                                    'foto_url' => $imgUrl,
                                ]) }}"
                                class="w-8 h-8 rounded-full bg-white/95 text-inkAdmin hover:bg-white flex items-center justify-center shadow"
                                title="Edit / Ganti Foto">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </button>

                        <form action="{{ route('admin.galeri.destroy', $photo) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-confirm="Apakah Anda yakin ingin menghapus foto '{{ $photo->judul }}'?" class="w-8 h-8 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center shadow" title="Hapus Foto">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <span class="absolute top-2 right-2 px-2 py-0.5 rounded-md bg-moss/80 text-white text-[9px] font-mono backdrop-blur-sm">
                    #{{ $photo->urutan }}
                </span>

                <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded-md bg-black/60 text-white text-[9px] font-medium backdrop-blur-sm truncate max-w-[82%]">
                    {{ $photo->judul ?: 'Momen Spesial' }}
                </span>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-inkMuted text-xs bg-cardBg rounded-3xl border border-borderSoft">
                <i data-lucide="image" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                <span class="block font-medium text-inkAdmin">Belum ada foto di galeri basis data.</span>
                <span class="text-[11px]">Klik tombol "Unggah Foto Baru" di atas untuk menambahkan foto pertama.</span>
            </div>
        @endforelse
    </div>

    {{-- Bottom Banner Quote --}}
    <div class="bg-cardBg rounded-3xl p-6 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] text-center relative overflow-hidden">
        <img src="{{ asset('aset/section5_bunga.webp') }}" alt="" class="absolute -left-6 top-1/2 -translate-y-1/2 w-24 opacity-20 pointer-events-none">
        <img src="{{ asset('aset/section5_bunga(2).webp') }}" alt="" class="absolute -right-6 top-1/2 -translate-y-1/2 w-24 opacity-20 pointer-events-none">

        <p class="font-display italic text-lg text-inkAdmin/90 relative z-10 max-w-lg mx-auto">
            &ldquo;{{ trim($header['bottom_quote'], '“”"') }}&rdquo;
        </p>
        <div class="w-12 h-0.5 bg-moss/20 mx-auto mt-2"></div>
    </div>
@endsection

@push('modals')
    {{-- Modal Unggah Foto Baru --}}
    <x-admin.modal id="uploadPhotoModal" title="Unggah Foto Galeri">
        <form action="{{ route('admin.galeri.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-medium mb-1">Judul / Keterangan Foto</label>
                <input type="text" name="judul" required placeholder="Contoh: Momen Bahagia di Taman" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-medium mb-1">Kategori</label>
                    <select name="kategori" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                        <option value="prewedding">Prewedding</option>
                        <option value="acara">Acara</option>
                        <option value="keseharian">Keseharian</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium mb-1">Urutan Tampil</label>
                    <input type="number" name="urutan" min="1" value="{{ $photos->count() + 1 }}" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                </div>
            </div>

            <div data-upload-wrapper>
                <label class="block font-medium mb-1.5">Unggah File Foto dari Perangkat</label>
                <div data-upload-trigger="new_gallery_photo" class="w-full h-40 rounded-2xl border-2 border-dashed border-borderSoft hover:border-moss bg-cardMuted flex flex-col items-center justify-center cursor-pointer overflow-hidden relative group transition">
                    <img id="preview_new_gallery_photo" src="" alt="Preview Upload" class="w-full h-full object-cover hidden">
                    <div data-upload-placeholder class="flex flex-col items-center justify-center p-4 text-center">
                        <i data-lucide="upload-cloud" class="w-7 h-7 text-moss mb-1.5"></i>
                        <span class="text-xs font-semibold text-inkAdmin">Klik untuk Pilih Foto dari Perangkat</span>
                        <span id="status_new_gallery_photo" class="text-[10px] text-inkMuted mt-0.5">Mendukung JPG, PNG, WEBP (Maks 10MB)</span>
                    </div>
                </div>
                <input type="hidden" id="input_new_gallery_photo" name="foto_url" required>
                <input type="file" id="file_new_gallery_photo" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden"
                       data-upload-input
                       data-target-input="input_new_gallery_photo"
                       data-target-preview="preview_new_gallery_photo"
                       data-target-status="status_new_gallery_photo"
                       data-upload-prefix="gallery">
            </div>

            <div class="flex justify-end space-x-2 pt-3">
                <button type="button" data-modal-close="uploadPhotoModal" class="px-4 py-2 rounded-xl border border-borderSoft text-inkAdmin hover:bg-cardMuted">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-moss text-adminBg font-medium hover:bg-adminMossLight transition shadow-sm">Simpan ke Galeri</button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Modal Edit Foto Galeri --}}
    <x-admin.modal id="editGalleryModal" title="Edit Foto Galeri">
        <form data-edit-form action="#" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-medium mb-1">Judul / Caption Foto</label>
                <input type="text" name="judul" data-field="judul" required class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-medium mb-1">Kategori</label>
                    <select name="kategori" data-field="kategori" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                        <option value="prewedding">Prewedding</option>
                        <option value="acara">Acara</option>
                        <option value="keseharian">Keseharian</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium mb-1">Urutan Tampil</label>
                    <input type="number" name="urutan" data-field="urutan" min="1" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                </div>
            </div>

            <div class="space-y-2" data-upload-wrapper>
                <label class="block font-medium">Ganti Foto (Opsional)</label>
                <div class="flex items-center space-x-3 p-3 rounded-2xl bg-cardMuted border border-borderSoft">
                    <img id="preview_edit_gallery_photo" data-field-preview="foto_url" src="/aset/section5_galerifoto.webp" data-fallback="/aset/section5_galerifoto.webp" alt="Preview Edit" data-upload-trigger="edit_gallery_photo" class="w-16 h-16 object-cover rounded-xl border border-borderSoft shadow-sm cursor-pointer">
                    <div class="flex-1">
                        <input type="hidden" id="input_edit_gallery_photo" name="foto_url" data-field="foto_url">
                        <input type="file" id="file_edit_gallery_photo" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden"
                               data-upload-input
                               data-target-input="input_edit_gallery_photo"
                               data-target-preview="preview_edit_gallery_photo"
                               data-target-status="status_edit_gallery_photo"
                               data-upload-prefix="gallery">
                        <button type="button" data-upload-trigger="edit_gallery_photo" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-white hover:bg-[#E5ECE0] border border-borderSoft text-xs font-semibold text-moss transition shadow-sm">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                            <span>Upload Foto Baru</span>
                        </button>
                        <span id="status_edit_gallery_photo" class="text-[10px] text-inkMuted block mt-1">Biarkan bila hanya mengganti judul/kategori</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-3">
                <button type="button" data-modal-close="editGalleryModal" class="px-4 py-2 rounded-xl border border-borderSoft text-inkAdmin hover:bg-cardMuted">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-moss text-adminBg font-medium hover:bg-adminMossLight transition shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Modal Ubah Header & Quote Section 5 --}}
    <x-admin.modal id="editGalleryHeaderModal" title="Ubah Teks, Foto & Quote Section 5" max-width="max-w-lg">
        <form action="{{ route('admin.konten.update', 'galeri') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-medium mb-1">Judul Headline Section</label>
                <textarea name="headline" rows="2" required class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">{{ $header['headline'] }}</textarea>
            </div>

            <div>
                <label class="block font-medium mb-1">Teks Pengantar Galeri</label>
                <textarea name="intro" rows="3" required class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">{{ $header['intro'] }}</textarea>
            </div>

            <x-admin.image-upload-field
                name="hero_photo"
                label="Foto Hero Galeri Utama"
                :value="$header['hero_photo']"
                :default-url="$defaults['hero_photo']"
                prefix="gallery_hero"
                button-label="Upload Foto Hero Galeri"
                thumb-class="w-20 h-14"
                section-slug="galeri"
                field-key="hero_photo"
            />

            <div>
                <label class="block font-medium mb-1">Quote Penutup Section 5</label>
                <textarea name="bottom_quote" rows="2" required class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">{{ $header['bottom_quote'] }}</textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-4 border-t border-borderSoft/60">
                <button type="button" data-modal-close="editGalleryHeaderModal" class="px-4 py-2 rounded-xl border border-borderSoft text-inkAdmin hover:bg-cardMuted">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-moss text-adminBg font-medium hover:bg-adminMossLight transition shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Lightbox Preview Modal --}}
    <div id="adminLightboxModal" data-modal class="fixed inset-0 bg-black/85 z-50 hidden items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-[85vh]">
            <button type="button" data-modal-close="adminLightboxModal" class="absolute -top-10 right-0 text-white/80 hover:text-white flex items-center space-x-1 text-xs">
                <i data-lucide="x" class="w-5 h-5"></i>
                <span>Tutup</span>
            </button>
            <img id="adminLightboxImg" src="" alt="Preview Foto" class="rounded-2xl shadow-2xl object-contain max-h-[80vh] w-auto">
            <p id="adminLightboxCaption" class="text-center text-white/80 text-xs mt-3"></p>
        </div>
    </div>
@endpush
