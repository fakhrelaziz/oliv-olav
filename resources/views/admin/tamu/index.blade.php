@extends('layouts.admin', [
    'pageHeading' => 'Data Tamu',
    'pageSubtitle' => 'Kelola data tamu undangan dan tautan personal dengan mudah.',
    'previewUrl' => route('invitation.show'),
])

@section('title', 'Data Tamu Undangan — REL | LIV')

@section('content')
    {{-- 4 Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-admin.stat-card
            label="Total Tamu"
            :value="$stats['total_tamu']"
            :subtext="'▲ ' . $stats['total_pax'] . ' total pax diundang'"
            subtext-color="text-emerald-700 font-medium"
            icon="users"
            ornament="aset/section2_bunga.webp"
        />

        <x-admin.stat-card
            label="Hadir"
            :value="$stats['hadir']"
            value-color="text-emerald-700"
            :percent="$stats['pct_hadir']"
            bar-color="bg-emerald-600"
            :subtext="str_replace('.', ',', (string) $stats['pct_hadir']) . '% dari total'"
            icon="check-circle-2"
        />

        <x-admin.stat-card
            label="Tidak Hadir"
            :value="$stats['tidak_hadir']"
            value-color="text-rose-700"
            :percent="$stats['pct_tidak_hadir']"
            bar-color="bg-rose-500"
            :subtext="str_replace('.', ',', (string) $stats['pct_tidak_hadir']) . '% dari total'"
            icon="x-circle"
        />

        <x-admin.stat-card
            label="Belum Konfirmasi"
            :value="$stats['belum_konfirmasi']"
            value-color="text-amber-700"
            :percent="$stats['pct_belum_konfirmasi']"
            bar-color="bg-amber-500"
            :subtext="str_replace('.', ',', (string) $stats['pct_belum_konfirmasi']) . '% dari total'"
            icon="mail"
        />
    </div>

    {{-- Main Card: Daftar Tamu Table --}}
    <div class="bg-cardBg rounded-3xl p-7 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] relative overflow-hidden">
        <img src="{{ asset('aset/section2_bunga.webp') }}" alt="" class="absolute -bottom-6 -left-6 w-24 opacity-15 pointer-events-none">
        <img src="{{ asset('aset/section2_bunga(2).webp') }}" alt="" class="absolute -bottom-6 -right-6 w-24 opacity-15 pointer-events-none">

        {{-- Header & Filter Controls --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 relative z-10">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('aset/section3_bunga.webp') }}" alt="" class="w-5 opacity-40">
                <h3 class="font-display text-2xl font-semibold text-inkAdmin">Daftar Tamu</h3>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <form action="{{ route('admin.tamu.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <div class="relative w-56 sm:w-64">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input
                            type="text"
                            name="q"
                            value="{{ $search }}"
                            data-table-search="guestsTable"
                            placeholder="Cari nama tamu..."
                            class="w-full pl-8 pr-3 py-2 text-xs bg-cardMuted border border-borderSoft rounded-xl focus:outline-none focus:border-moss"
                        >
                    </div>

                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-cardMuted border border-borderSoft rounded-xl text-inkAdmin focus:outline-none focus:border-moss">
                        <option value="all" @selected($statusFilter === 'all')>Semua Status</option>
                        <option value="Hadir" @selected($statusFilter === 'Hadir')>Hadir</option>
                        <option value="Belum Konfirmasi" @selected($statusFilter === 'Belum Konfirmasi')>Belum Konfirmasi</option>
                        <option value="Masih Ragu" @selected($statusFilter === 'Masih Ragu')>Masih Ragu</option>
                        <option value="Tidak Hadir" @selected($statusFilter === 'Tidak Hadir')>Tidak Hadir</option>
                    </select>

                    @if ($search !== '' || $statusFilter !== 'all')
                        <a href="{{ route('admin.tamu.index') }}" class="inline-flex items-center space-x-1 px-3 py-2 text-xs bg-white hover:bg-rose-50 border border-borderSoft rounded-xl text-inkMuted hover:text-rose-600 transition" title="Reset pencarian & filter">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            <span>Reset</span>
                        </a>
                    @endif
                </form>

                <button type="button" data-modal-open="createGuestModal" class="px-4 py-2 rounded-xl bg-moss hover:bg-adminMossLight text-adminBg text-xs font-medium flex items-center space-x-2 transition shadow-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Tamu</span>
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto relative z-10">
            <table class="w-full text-left text-xs" id="guestsTable">
                <thead>
                    <tr class="border-b border-borderSoft text-inkMuted uppercase tracking-wider text-[11px] bg-cardMuted/50">
                        <th class="py-3 px-4 font-medium rounded-l-xl">No</th>
                        <th class="py-3 px-4 font-medium">Nama Tamu</th>
                        <th class="py-3 px-4 font-medium">Kontak / Alamat</th>
                        <th class="py-3 px-4 font-medium text-center">Jumlah Undangan</th>
                        <th class="py-3 px-4 font-medium">Status</th>
                        <th class="py-3 px-4 font-medium text-center rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-borderSoft/60">
                    @forelse ($guests as $guest)
                        @php
                            $inviteUrl = route('invitation.guest', $guest->slug);
                        @endphp
                        <tr class="hover:bg-cardMuted/80 transition" data-search-row="{{ mb_strtolower($guest->nama . ' ' . $guest->alamat . ' ' . $guest->no_telepon) }}">
                            <td class="py-3.5 px-4 text-inkMuted font-medium">{{ ($guests->currentPage() - 1) * $guests->perPage() + $loop->iteration }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-inkAdmin block">{{ $guest->nama }}</span>
                                <span class="text-[10px] text-inkMuted font-mono">/u/{{ $guest->slug }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-inkMuted">
                                @if ($guest->no_telepon)
                                    <span class="block font-mono text-[11px] text-inkAdmin">{{ $guest->no_telepon }}</span>
                                @endif
                                <span class="text-[11px]">{{ $guest->alamat ?: '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono text-inkAdmin">{{ $guest->jumlah_undangan }}</td>
                            <td class="py-3.5 px-4">
                                <x-admin.status-badge :status="$guest->status" />
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center space-x-1.5 text-inkMuted">
                                    <button type="button"
                                            data-edit-modal="editGuestModal"
                                            data-edit-action="{{ route('admin.tamu.update', $guest) }}"
                                            data-edit-payload="{{ json_encode([
                                                'nama' => $guest->nama,
                                                'no_telepon' => $guest->no_telepon,
                                                'alamat' => $guest->alamat,
                                                'jumlah_undangan' => $guest->jumlah_undangan,
                                                'status' => $guest->status,
                                            ]) }}"
                                            class="p-1.5 rounded-lg hover:bg-adminBg hover:text-moss transition"
                                            title="Edit Tamu">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </button>

                                    <button type="button"
                                            data-copy-link="{{ $inviteUrl }}"
                                            data-copy-label="Link undangan untuk {{ $guest->nama }} berhasil disalin!"
                                            class="p-1.5 rounded-lg hover:bg-adminBg hover:text-moss transition"
                                            title="Salin Link Undangan Personal">
                                        <i data-lucide="link" class="w-4 h-4"></i>
                                    </button>

                                    <form action="{{ route('admin.tamu.destroy', $guest) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" data-confirm="Apakah Anda yakin ingin menghapus data tamu '{{ $guest->nama }}'?" class="p-1.5 rounded-lg hover:bg-rose-50 hover:text-rose-600 transition" title="Hapus Tamu">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-inkMuted">
                                <i data-lucide="users" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                                <span class="block font-medium text-inkAdmin">Belum ada tamu yang sesuai kriteria.</span>
                                <span class="text-[11px]">Klik tombol "Tambah Tamu" di atas untuk menambahkan tamu baru.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-6 mt-4 border-t border-borderSoft/60 relative z-10 text-xs">
            <span class="text-inkMuted">
                Menampilkan {{ $guests->count() }} dari {{ $guests->total() }} tamu terdaftar
            </span>

            @if ($guests->hasPages())
                <div>
                    {{ $guests->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('modals')
    {{-- Modal Tambah Tamu --}}
    <x-admin.modal id="createGuestModal" title="Tambah Data Tamu">
        <form action="{{ route('admin.tamu.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-medium mb-1">Nama Lengkap Tamu</label>
                <input type="text" name="nama" required placeholder="Contoh: Raden Mas Danu" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-medium mb-1">No. Telepon / WA (Opsional)</label>
                    <input type="text" name="no_telepon" placeholder="0812xxxxxxxx" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                </div>
                <div>
                    <label class="block font-medium mb-1">Alamat / Grup (Opsional)</label>
                    <input type="text" name="alamat" placeholder="Jakarta Selatan / VIP" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-medium mb-1">Jumlah Undangan (Pax)</label>
                    <input type="number" name="jumlah_undangan" value="2" min="1" max="20" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                </div>
                <div>
                    <label class="block font-medium mb-1">Status Kehadiran</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                        <option value="Hadir">Hadir</option>
                        <option value="Belum Konfirmasi" selected>Belum Konfirmasi</option>
                        <option value="Masih Ragu">Masih Ragu</option>
                        <option value="Tidak Hadir">Tidak Hadir</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-3">
                <button type="button" data-modal-close="createGuestModal" class="px-4 py-2 rounded-xl border border-borderSoft text-inkAdmin hover:bg-cardMuted">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-moss text-adminBg font-medium hover:bg-adminMossLight transition shadow-sm">Simpan Tamu</button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Modal Edit Tamu --}}
    <x-admin.modal id="editGuestModal" title="Edit Data Tamu">
        <form data-edit-form action="#" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-medium mb-1">Nama Lengkap Tamu</label>
                <input type="text" name="nama" data-field="nama" required class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-medium mb-1">No. Telepon / WA (Opsional)</label>
                    <input type="text" name="no_telepon" data-field="no_telepon" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                </div>
                <div>
                    <label class="block font-medium mb-1">Alamat / Grup (Opsional)</label>
                    <input type="text" name="alamat" data-field="alamat" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-medium mb-1">Jumlah Undangan (Pax)</label>
                    <input type="number" name="jumlah_undangan" data-field="jumlah_undangan" min="1" max="20" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                </div>
                <div>
                    <label class="block font-medium mb-1">Status Kehadiran</label>
                    <select name="status" data-field="status" class="w-full px-3 py-2 rounded-xl bg-cardMuted border border-borderSoft focus:outline-none focus:border-moss">
                        <option value="Hadir">Hadir</option>
                        <option value="Belum Konfirmasi">Belum Konfirmasi</option>
                        <option value="Masih Ragu">Masih Ragu</option>
                        <option value="Tidak Hadir">Tidak Hadir</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-3">
                <button type="button" data-modal-close="editGuestModal" class="px-4 py-2 rounded-xl border border-borderSoft text-inkAdmin hover:bg-cardMuted">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-moss text-adminBg font-medium hover:bg-adminMossLight transition shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </x-admin.modal>
@endpush
