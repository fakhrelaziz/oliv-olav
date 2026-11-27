@extends('layouts.admin', ['pageHeading' => 'Our Story', 'previewUrl' => route('invitation.show') . '#story'])

@section('title', 'Our Story — REL | LIV')

@section('content')
    {{-- Breadcrumbs --}}
    <div class="flex items-center space-x-2 text-xs text-inkMuted">
        <a href="{{ route('admin.konten.index') }}" class="hover:text-moss">Kelola Konten</a>
        <span>/</span>
        <span class="font-semibold text-inkAdmin">Section 3: Our Story</span>
    </div>

    {{-- Hero Banner --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] relative overflow-hidden flex flex-col justify-between">
            <img src="{{ asset('aset/section3_bunga.webp') }}" alt="" class="absolute -top-4 right-10 w-28 opacity-25 pointer-events-none">
            <div class="relative z-10">
                <span class="text-[11px] font-semibold uppercase tracking-widest text-moss">OUR STORY</span>
                <h2 class="font-display text-4xl font-semibold text-inkAdmin mt-1">{{ str_replace("\n", ' ', $header['headline']) }}</h2>
                <p class="text-xs text-inkMuted mt-1 mb-5 max-w-lg leading-relaxed">{{ $header['intro'] }}</p>

                <div class="flex flex-wrap gap-2.5">
                    <button type="button" data-modal-open="editStoryHeaderModal" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-cardMuted hover:bg-[#F0EEE2] border border-borderSoft text-xs font-medium text-inkAdmin transition shadow-sm">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-moss"></i>
                        <span>Ubah Teks &amp; Quote Section 3</span>
                    </button>

                    <form action="{{ route('admin.konten.reset', 'story') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" data-confirm="Kembalikan Header & Quote Section 3 ke pengaturan default?" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-white hover:bg-rose-50 border border-borderSoft text-xs font-medium text-inkMuted hover:text-rose-600 transition shadow-sm">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                            <span>Reset Header</span>
                        </button>
                    </form>

                    <a href="{{ route('invitation.show') }}#story" target="_blank" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium transition shadow-sm">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        <span>Lihat Timeline di Website</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 bg-cardBg rounded-3xl p-5 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] flex flex-col justify-center items-center text-center">
            <div class="w-full h-32 rounded-2xl overflow-hidden bg-[#D8E4D5] relative group">
                <img src="{{ $header['photo_hero'] }}" data-fallback="{{ asset('aset/section3_fotopengantin.webp') }}" alt="Preview Banner Story" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/25 flex items-center justify-center">
                    <a href="{{ route('invitation.show') }}#story" target="_blank" class="px-3.5 py-1.5 rounded-full bg-white/95 text-[11px] font-semibold text-inkAdmin shadow">Preview Website</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Two Columns: Timeline & Side Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left: Milestone Timeline --}}
        <div class="lg:col-span-7 bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] relative">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-display text-2xl font-semibold text-inkAdmin">Timeline Perjalanan</h3>
                <button type="button" data-modal-open="createMomentModal" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium transition shadow-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Momen</span>
                </button>
            </div>

            <div class="relative border-l-2 border-borderSoft ml-4 pl-6 space-y-7">
                @forelse ($stories as $st)
                    @php
                        $isLast = $loop->last;
                        $thumbUrl = \App\Services\MediaStorageService::normalizeAssetUrl($st->foto_url, '/aset/section3_fotopengantin(2).webp');
                    @endphp
                    <div class="relative group">
                        <div class="absolute -left-[31px] top-1.5 w-3.5 h-3.5 rounded-full {{ $isLast ? 'bg-emerald-600' : 'bg-moss' }} border-2 border-white shadow-sm"></div>

                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start space-x-4 flex-1 min-w-0">
                                <div class="w-16 h-16 rounded-xl overflow-hidden border border-borderSoft flex-shrink-0 shadow-sm bg-cardMuted">
                                    <img src="{{ $thumbUrl }}" data-fallback="{{ asset('aset/section3_fotopengantin(2).webp') }}" alt="{{ $st->judul }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-mono {{ $isLast ? 'text-emerald-700 font-semibold' : 'text-inkMuted font-medium' }}">{{ $st->tanggal_momen }}</span>
                                        <span class="text-[10px] text-inkMuted/60">#{{ $st->urutan }}</span>
                                    </div>
                                    <h4 class="font-display text-base font-semibold text-inkAdmin mt-0.5">{{ $st->judul }}</h4>
                                    <p class="text-xs text-inkMuted mt-1 leading-relaxed">{{ $st->deskripsi }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-1 shrink-0">
                                <button type="button"
                                        data-edit-modal="editMomentModal"
                                        data-edit-action="{{ route('admin.story.update', $st) }}"
                                        data-edit-payload="{{ json_encode([
                                            'judul' => $st->judul,
                                            'tanggal_momen' => $st->tanggal_momen,
                                            'urutan' => $st->urutan,
                                            'foto_url' => $thumbUrl,
                                            'deskripsi' => $st->deskripsi,
                                        ]) }}"
                                        class="p-1.5 rounded-lg text-inkMuted hover:text-moss hover:bg-[#EBEFE8] transition"
                                        title="Edit Momen">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>

                                <form action="{{ route('admin.story.destroy', $st) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" data-confirm="Apakah Anda yakin ingin menghapus momen '{{ $st->judul }}'?" class="p-1.5 rounded-lg text-inkMuted hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus Momen">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-inkMuted text-xs">
                        <i data-lucide="book-open" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                        <span class="block font-medium text-inkAdmin">Belum ada tonggak kisah cinta di basis data.</span>
                        <span class="text-[11px]">Klik "Tambah Momen" untuk menambahkan kisah pertama Anda.</span>
                    </div>
                @endforelse
            </div>

            <img src="{{ asset('aset/section2_bunga(2).webp') }}" alt="" class="absolute bottom-4 right-4 w-20 opacity-20 pointer-events-none">
        </div>

        {{-- Right: Quote & Memory Photo --}}
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] relative overflow-hidden flex flex-col justify-center items-center text-center">
                <img src="{{ asset('aset/section2_bunga.webp') }}" alt="" class="absolute -top-4 -right-4 w-20 opacity-25 pointer-events-none rotate-45">
                <p class="font-display italic text-lg text-inkAdmin/90 max-w-xs leading-relaxed relative z-10">
                    &ldquo;{{ trim($header['bottom_quote'], '“”"') }}&rdquo;
                </p>
                <div class="w-12 h-0.5 bg-moss/20 mx-auto mt-4"></div>
            </div>

            <div class="bg-cardBg rounded-3xl p-6 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] flex flex-col items-center justify-center">
                <img src="{{ $header['photo_hero'] }}" data-fallback="{{ asset('aset/section3_fotopengantin.webp') }}" alt="Foto Highlight Section 3" class="w-full h-56 rounded-2xl object-cover border border-borderSoft shadow-inner">
            </div>
        </div>
    </div>
@endsection

@push('modals')
    {{-- Modal Tambah Momen --}}
    <x-admin.modal id="createMomentModal" title="Tambah Momen Cerita">
        <form action="{{ route('admin.story.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-medium mb-1">Judul Momen</label>
                <input type="text" name="judul" required placeholder="Contoh: Pertama Kali Bertemu" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div class="col-span-2">
                    <label class="block font-medium mb-1">Tanggal</label>
                    <input type="text" name="tanggal_momen" required placeholder="Contoh: 12 Maret 2020" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                </div>
                <div>
                    <label class="block font-medium mb-1">Urutan</label>
                    <input type="number" name="urutan" min="1" value="{{ $stories->count() + 1 }}" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                </div>
            </div>

            <x-admin.image-upload-field
                name="foto_url"
                label="Foto Momen Cerita"
                value="/aset/section3_fotopengantin(3).webp"
                default-url="/aset/section3_fotopengantin(3).webp"
                prefix="story_moment"
                button-label="Upload Foto Momen"
                thumb-class="w-16 h-16"
            />

            <div>
                <label class="block font-medium mb-1">Deskripsi Cerita</label>
                <textarea name="deskripsi" rows="3" required placeholder="Tuliskan kisah singkat perjalanan ini..." class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss resize-none"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-3">
                <button type="button" data-modal-close="createMomentModal" class="px-4 py-2 rounded-xl border border-borderSoft text-inkAdmin hover:bg-cardMuted">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-moss text-adminBg font-medium hover:bg-adminMossLight transition shadow-sm">Simpan Momen</button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Modal Edit Momen --}}
    <x-admin.modal id="editMomentModal" title="Edit Momen Cerita">
        <form data-edit-form action="#" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-medium mb-1">Judul Momen</label>
                <input type="text" name="judul" data-field="judul" required class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div class="col-span-2">
                    <label class="block font-medium mb-1">Tanggal</label>
                    <input type="text" name="tanggal_momen" data-field="tanggal_momen" required class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                </div>
                <div>
                    <label class="block font-medium mb-1">Urutan</label>
                    <input type="number" name="urutan" data-field="urutan" min="1" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                </div>
            </div>

            <div class="space-y-2" data-upload-wrapper>
                <label class="block text-xs font-semibold text-inkAdmin">Foto Momen Cerita</label>
                <div class="flex items-center space-x-3 p-3 rounded-2xl bg-cardMuted border border-borderSoft">
                    <img id="preview_edit_story_photo" data-field-preview="foto_url" src="/aset/section3_fotopengantin(3).webp" data-fallback="/aset/section3_fotopengantin(3).webp" alt="Preview Momen" data-upload-trigger="edit_story_photo" class="w-16 h-16 object-cover rounded-xl border border-borderSoft shadow-sm cursor-pointer">
                    <div class="flex-1">
                        <input type="hidden" id="input_edit_story_photo" name="foto_url" data-field="foto_url">
                        <input type="file" id="file_edit_story_photo" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden"
                               data-upload-input
                               data-target-input="input_edit_story_photo"
                               data-target-preview="preview_edit_story_photo"
                               data-target-status="status_edit_story_photo"
                               data-upload-prefix="story_moment">
                        <button type="button" data-upload-trigger="edit_story_photo" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-white hover:bg-[#E5ECE0] border border-borderSoft text-xs font-semibold text-moss transition shadow-sm">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                            <span>Ganti / Upload Foto Baru</span>
                        </button>
                        <span id="status_edit_story_photo" class="text-[10px] text-inkMuted block mt-1">Klik untuk mengganti foto momen</span>
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-medium mb-1">Deskripsi Cerita</label>
                <textarea name="deskripsi" data-field="deskripsi" rows="3" required class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss resize-none"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-3">
                <button type="button" data-modal-close="editMomentModal" class="px-4 py-2 rounded-xl border border-borderSoft text-inkAdmin hover:bg-cardMuted">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-moss text-adminBg font-medium hover:bg-adminMossLight transition shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Modal Edit Header & Quote Section 3 --}}
    <x-admin.modal id="editStoryHeaderModal" title="Ubah Teks, Foto & Quote Section 3" max-width="max-w-lg">
        <form action="{{ route('admin.konten.update', 'story') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-medium mb-1">Judul Headline Section</label>
                <textarea name="headline" rows="2" required class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">{{ $header['headline'] }}</textarea>
                <span class="text-[10px] text-inkMuted mt-1 block">Gunakan baris baru untuk memisahkan baris judul</span>
            </div>

            <div>
                <label class="block font-medium mb-1">Paragraf Pengantar Cerita</label>
                <textarea name="intro" rows="3" required class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">{{ $header['intro'] }}</textarea>
            </div>

            <x-admin.image-upload-field
                name="photo_hero"
                label="Foto Highlight Section 3 (Kanan Atas)"
                :value="$header['photo_hero']"
                :default-url="$defaults['photo_hero']"
                prefix="story_hero"
                button-label="Upload Foto Highlight"
                thumb-class="w-20 h-14"
                section-slug="story"
                field-key="photo_hero"
            />

            <div>
                <label class="block font-medium mb-1">Quote Penutup Section 3</label>
                <textarea name="bottom_quote" rows="2" required class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">{{ $header['bottom_quote'] }}</textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-4 border-t border-borderSoft/60">
                <button type="button" data-modal-close="editStoryHeaderModal" class="px-4 py-2 rounded-xl border border-borderSoft text-inkAdmin hover:bg-cardMuted">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-moss text-adminBg font-medium hover:bg-adminMossLight transition shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </x-admin.modal>
@endpush
