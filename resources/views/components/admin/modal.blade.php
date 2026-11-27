@props([
    'id',
    'title',
    'maxWidth' => 'max-w-md',
])

<div id="{{ $id }}" data-modal class="fixed inset-0 bg-black/60 backdrop-blur-[2px] z-50 hidden items-center justify-center p-4">
    <div class="bg-cardBg w-full {{ $maxWidth }} rounded-3xl p-7 border border-borderSoft shadow-2xl relative max-h-[90vh] overflow-y-auto custom-scroll">
        <div class="flex items-center justify-between mb-5 border-b border-borderSoft/60 pb-3">
            <h3 class="font-display text-xl sm:text-2xl font-semibold text-inkAdmin">{{ $title }}</h3>
            <button type="button" data-modal-close="{{ $id }}" class="p-1.5 rounded-lg text-gray-400 hover:text-inkAdmin hover:bg-cardMuted transition">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{ $slot }}
    </div>
</div>
