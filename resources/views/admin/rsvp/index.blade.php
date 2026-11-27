@extends('layouts.admin', ['pageHeading' => 'Doa dan Ucapan', 'previewUrl' => route('invitation.show') . '#rsvp'])

@section('title', 'Doa dan Ucapan — REL | LIV')

@section('content')
    {{-- Hero Banner --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] relative overflow-hidden flex flex-col justify-between">
            <img src="{{ asset('aset/section2_bunga.webp') }}" alt="" class="absolute -top-4 right-10 w-28 opacity-25 pointer-events-none">
            <div class="relative z-10">
                <span class="text-[11px] font-semibold uppercase tracking-widest text-moss">DOA &amp; UCAPAN</span>
                <h2 class="font-display text-4xl font-semibold text-inkAdmin mt-1">Terima Kasih atas Doa dan Ucapan</h2>
                <p class="text-xs text-inkMuted mt-1 mb-5 max-w-lg leading-relaxed">
                    Setiap doa dan ucapan yang dikirimkan tamu adalah hadiah yang sangat berarti. Kelola visibilitas dan moderasi ucapan secara langsung dari sini.
                </p>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.tamu.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium transition shadow-sm">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                        <span>Kelola Data Tamu</span>
                    </a>
                    <span class="text-xs text-inkMuted">Terhubung langsung dengan form RSVP website</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 bg-cardBg rounded-3xl p-5 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] flex flex-col justify-center items-center text-center">
            <div class="w-full h-32 rounded-2xl overflow-hidden bg-[#D8E4D5] relative group">
                <img src="{{ asset('aset/background_herosection(desktop).webp') }}" alt="Preview Banner" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/25 flex items-center justify-center">
                    <a href="{{ route('invitation.show') }}#rsvp" target="_blank" class="px-3.5 py-1.5 rounded-full bg-white/95 text-[11px] font-semibold text-inkAdmin shadow">Preview Website</a>
                </div>
            </div>
        </div>
    </div>

    {{-- 4 Metric Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-admin.stat-card label="Total Ucapan" :value="$stats['total_ucapan']" subtext="Ucapan diterima" icon="message-square" ornament="aset/section2_bunga(2).webp" />
        <x-admin.stat-card label="Hadir" :value="$stats['hadir']" value-color="text-emerald-700" subtext="Konfirmasi hadir" icon="user-check" ornament="aset/section3_bunga.webp" />
        <x-admin.stat-card label="Tidak Hadir" :value="$stats['tidak_hadir']" value-color="text-rose-700" subtext="Konfirmasi tidak hadir" icon="user-x" ornament="aset/section4_bunga.webp" />
        <x-admin.stat-card label="Masih Ragu" :value="$stats['masih_ragu']" value-color="text-amber-700" subtext="Belum konfirmasi pasti" icon="help-circle" ornament="aset/section5_bunga.webp" />
    </div>

    {{-- Main Layout: Table Ucapan & Rasio Kehadiran --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Table Ucapan (8 Cols) --}}
        <div class="lg:col-span-8 bg-cardBg rounded-3xl p-6 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h3 class="font-display text-2xl font-semibold text-inkAdmin">Ucapan dari Tamu</h3>

                <div class="flex flex-wrap items-center gap-2">
                    <form action="{{ route('admin.rsvp.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                        <div class="relative">
                            <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input
                                type="text"
                                name="q"
                                value="{{ $search }}"
                                data-table-search="wishesTable"
                                placeholder="Cari nama atau pesan..."
                                class="pl-8 pr-3 py-1.5 text-xs bg-cardMuted border border-borderSoft rounded-xl focus:outline-none focus:border-moss"
                            >
                        </div>

                        <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 text-xs bg-cardMuted border border-borderSoft rounded-xl text-inkAdmin focus:outline-none focus:border-moss">
                            <option value="all" @selected($statusFilter === 'all')>Semua Status</option>
                            <option value="Hadir" @selected($statusFilter === 'Hadir')>Hadir</option>
                            <option value="Tidak Hadir" @selected($statusFilter === 'Tidak Hadir')>Tidak Hadir</option>
                            <option value="Masih Ragu" @selected($statusFilter === 'Masih Ragu')>Masih Ragu</option>
                        </select>
                    </form>

                    <a href="{{ route('admin.rsvp.export') }}" class="px-3 py-1.5 text-xs bg-cardMuted hover:bg-[#EFECE0] border border-borderSoft rounded-xl text-inkAdmin flex items-center space-x-1 transition">
                        <i data-lucide="download" class="w-3.5 h-3.5 text-inkMuted"></i>
                        <span>Export</span>
                    </a>

                    <button type="button" data-modal-open="addWishModal" class="px-3 py-1.5 text-xs bg-moss hover:bg-adminMossLight text-adminBg rounded-xl flex items-center space-x-1 transition shadow-sm">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="wishesTable">
                    <thead>
                        <tr class="border-b border-borderSoft text-inkMuted uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-3 font-medium">No</th>
                            <th class="py-3 px-3 font-medium">Nama Tamu</th>
                            <th class="py-3 px-3 font-medium">Status</th>
                            <th class="py-3 px-4 font-medium">Ucapan &amp; Doa</th>
                            <th class="py-3 px-3 font-medium">Tanggal</th>
                            <th class="py-3 px-3 font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-borderSoft/60">
                        @forelse ($wishes as $wish)
                            @php $isVisible = (bool) $wish->is_tampil; @endphp
                            <tr class="hover:bg-cardMuted/80 transition {{ $isVisible ? '' : 'opacity-65 bg-stone-50/60' }}" data-search-row="{{ mb_strtolower($wish->nama_pengirim . ' ' . $wish->pesan_ucapan) }}">
                                <td class="py-3 px-3 font-medium text-inkMuted">{{ ($wishes->currentPage() - 1) * $wishes->perPage() + $loop->iteration }}</td>
                                <td class="py-3 px-3">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-7 h-7 rounded-full bg-[#E5ECE0] text-[#345230] flex items-center justify-center font-semibold text-[11px] shrink-0">{{ $wish->initials }}</div>
                                        <div>
                                            <span class="font-semibold text-inkAdmin block">{{ $wish->nama_pengirim }}</span>
                                            @unless ($isVisible)
                                                <span class="text-[10px] font-medium text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded">Disembunyikan</span>
                                            @endunless
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <x-admin.status-badge :status="$wish->status_kehadiran" />
                                </td>
                                <td class="py-3 px-4 text-inkAdmin max-w-xs leading-relaxed">{{ $wish->pesan_ucapan }}</td>
                                <td class="py-3 px-3 text-inkMuted font-mono text-[11px] whitespace-nowrap">{{ $wish->formatted_date }}</td>
                                <td class="py-3 px-3 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <button type="button"
                                                data-edit-modal="editWishModal"
                                                data-edit-action="{{ route('admin.rsvp.update', $wish) }}"
                                                data-edit-payload="{{ json_encode([
                                                    'nama_pengirim' => $wish->nama_pengirim,
                                                    'status_kehadiran' => $wish->status_kehadiran,
                                                    'pesan_ucapan' => $wish->pesan_ucapan,
                                                    'is_tampil' => (bool) $wish->is_tampil,
                                                ]) }}"
                                                class="p-1.5 rounded-lg hover:bg-adminBg text-inkMuted hover:text-moss transition"
                                                title="Edit Ucapan">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>

                                        <form action="{{ route('admin.rsvp.toggle', $wish) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-1.5 rounded-lg hover:bg-adminBg text-inkMuted hover:text-amber-700 transition" title="{{ $isVisible ? 'Sembunyikan dari Publik' : 'Tampilkan ke Publik' }}">
                                                <i data-lucide="{{ $isVisible ? 'eye' : 'eye-off' }}" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.rsvp.destroy', $wish) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" data-confirm="Apakah Anda yakin ingin menghapus ucapan dari '{{ $wish->nama_pengirim }}'?" class="p-1.5 rounded-lg hover:bg-rose-50 text-inkMuted hover:text-rose-600 transition" title="Hapus Ucapan">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-inkMuted">
                                    <i data-lucide="message-square" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                                    <span class="block font-medium text-inkAdmin">Belum ada doa dan ucapan di basis data.</span>
                                    <span class="text-[11px]">Doa dari tamu yang mengisi undangan di website publik akan otomatis muncul di sini.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($wishes->hasPages())
                <div class="pt-5 mt-4 border-t border-borderSoft/60">
                    {{ $wishes->withQueryString()->links() }}
                </div>
            @endif
        </div>

        {{-- Right Side: Konfirmasi Kehadiran & Quote (4 Cols) --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-cardBg rounded-3xl p-6 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] relative overflow-hidden">
                <img src="{{ asset('aset/section2_bunga.webp') }}" alt="" class="absolute -top-3 -right-3 w-16 opacity-20 pointer-events-none">
                <h3 class="font-display text-xl font-semibold text-inkAdmin mb-5">Konfirmasi Kehadiran</h3>

                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 rounded-full bg-[#E5ECE0] text-[#345230] flex items-center justify-center">
                                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                                </div>
                                <span class="font-medium text-inkAdmin">Hadir ({{ $stats['hadir'] }} orang)</span>
                            </div>
                            <span class="font-semibold text-emerald-700">{{ round($stats['pct_hadir']) }}%</span>
                        </div>
                        <div class="w-full bg-[#EBE9DE] h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-600 h-full rounded-full transition-all duration-500" style="width: {{ $stats['pct_hadir'] }}%;"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 rounded-full bg-badgeRed text-textRed flex items-center justify-center">
                                    <i data-lucide="user-x" class="w-3.5 h-3.5"></i>
                                </div>
                                <span class="font-medium text-inkAdmin">Tidak Hadir ({{ $stats['tidak_hadir'] }} orang)</span>
                            </div>
                            <span class="font-semibold text-rose-700">{{ round($stats['pct_tidak_hadir']) }}%</span>
                        </div>
                        <div class="w-full bg-[#EBE9DE] h-2 rounded-full overflow-hidden">
                            <div class="bg-rose-500 h-full rounded-full transition-all duration-500" style="width: {{ $stats['pct_tidak_hadir'] }}%;"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 rounded-full bg-badgeAmber text-textAmber flex items-center justify-center">
                                    <i data-lucide="help-circle" class="w-3.5 h-3.5"></i>
                                </div>
                                <span class="font-medium text-inkAdmin">Masih Ragu ({{ $stats['masih_ragu'] }} orang)</span>
                            </div>
                            <span class="font-semibold text-amber-700">{{ round($stats['pct_masih_ragu']) }}%</span>
                        </div>
                        <div class="w-full bg-[#EBE9DE] h-2 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ $stats['pct_masih_ragu'] }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-cardBg rounded-3xl p-6 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] text-center relative overflow-hidden flex flex-col justify-center items-center">
                <img src="{{ asset('aset/section3_bunga.webp') }}" alt="" class="absolute -bottom-4 -left-4 w-16 opacity-20 pointer-events-none">
                <span class="font-serif text-3xl text-moss/30 leading-none">&ldquo;</span>
                <p class="font-display italic text-base text-inkAdmin/90 max-w-[240px] leading-relaxed -mt-2">
                    Terima kasih telah menjadi bagian dari hari istimewa kami.
                </p>
                <div class="w-10 h-0.5 bg-borderSoft mt-3"></div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    {{-- Modal Tambah Ucapan --}}
    <x-admin.modal id="addWishModal" title="Tambah Doa & Ucapan">
        <form action="{{ route('admin.rsvp.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-medium mb-1">Nama Pengirim</label>
                <input type="text" name="nama_pengirim" required placeholder="Contoh: Raden Mas Danu" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
            </div>

            <div>
                <label class="block font-medium mb-1">Status Kehadiran</label>
                <select name="status_kehadiran" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                    <option value="Hadir">Hadir</option>
                    <option value="Tidak Hadir">Tidak Hadir</option>
                    <option value="Masih Ragu">Masih Ragu</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Pesan Doa &amp; Ucapan</label>
                <textarea name="pesan_ucapan" rows="3" required placeholder="Tuliskan ucapan selamat dan doa..." class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss resize-none"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-3">
                <button type="button" data-modal-close="addWishModal" class="px-4 py-2 rounded-xl border border-borderSoft text-inkAdmin hover:bg-cardMuted">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-moss text-adminBg font-medium hover:bg-adminMossLight transition shadow-sm">Simpan Ucapan</button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Modal Edit Ucapan --}}
    <x-admin.modal id="editWishModal" title="Edit Doa & Ucapan">
        <form data-edit-form action="#" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-inkMuted font-medium mb-1">Nama Pengirim</label>
                <input type="text" name="nama_pengirim" data-field="nama_pengirim" required class="w-full px-3.5 py-2.5 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
            </div>
            <div>
                <label class="block text-inkMuted font-medium mb-1">Status Kehadiran</label>
                <select name="status_kehadiran" data-field="status_kehadiran" class="w-full px-3.5 py-2.5 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                    <option value="Hadir">Hadir</option>
                    <option value="Masih Ragu">Masih Ragu</option>
                    <option value="Tidak Hadir">Tidak Hadir</option>
                </select>
            </div>
            <div>
                <label class="block text-inkMuted font-medium mb-1">Pesan / Doa Restu</label>
                <textarea name="pesan_ucapan" data-field="pesan_ucapan" rows="3" required class="w-full px-3.5 py-2.5 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss resize-none"></textarea>
            </div>
            <div class="flex items-center space-x-2">
                <input type="hidden" name="is_tampil" value="0">
                <input type="checkbox" id="editWishVisible" name="is_tampil" value="1" data-field="is_tampil" class="rounded border-borderSoft text-moss focus:ring-moss accent-moss">
                <label for="editWishVisible" class="text-inkAdmin font-medium cursor-pointer">Tampilkan di halaman publik website</label>
            </div>
            <div class="flex justify-end space-x-2 pt-3">
                <button type="button" data-modal-close="editWishModal" class="px-4 py-2 rounded-xl border border-borderSoft text-inkAdmin hover:bg-cardMuted">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-moss text-adminBg font-medium hover:bg-adminMossLight transition shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </x-admin.modal>
@endpush
