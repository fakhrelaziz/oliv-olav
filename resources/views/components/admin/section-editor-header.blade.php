@props([
    'badge' => 'Section 01',
    'title',
    'description',
    'updatedAt' => null,
])

<div class="bg-cardBg rounded-3xl p-6 border border-borderSoft/80 shadow-[0_2px_15px_rgba(40,51,34,0.03)] flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <span class="px-2.5 py-0.5 rounded-full bg-badgeGreen text-textGreen text-[11px] font-semibold">{{ $badge }}</span>
        <h2 class="font-display text-2xl font-semibold text-inkAdmin mt-1">{{ $title }}</h2>
        <p class="text-xs text-inkMuted mt-1 leading-relaxed max-w-xl">{{ $description }}</p>
    </div>
    <div class="flex items-center space-x-2 text-xs text-inkMuted bg-cardMuted px-4 py-2 rounded-2xl border border-borderSoft shrink-0">
        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
        <span>Terakhir diperbarui: <strong class="text-inkAdmin">{{ $updatedAt ? $updatedAt->timezone(config('app.timezone'))->format('d M Y, H:i') : 'Default' }}</strong></span>
    </div>
</div>
