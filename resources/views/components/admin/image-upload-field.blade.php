@props([
    'name',
    'label',
    'value' => '',
    'defaultUrl' => '',
    'prefix' => 'img',
    'buttonLabel' => 'Upload Foto',
    'hint' => 'Format JPG, PNG, WEBP (Maks 10MB)',
    'layout' => 'inline', // 'inline', 'banner', or 'square'
    'thumbClass' => 'w-20 h-16',
    'sectionSlug' => null,
    'fieldKey' => null,
])

@php
    $idBase = str_replace(['[', ']', '.'], '_', $name);
    $resolvedValue = $value ?: $defaultUrl;
@endphp

<div class="space-y-2" data-upload-wrapper>
    @if ($label)
        <label class="block text-xs font-semibold text-inkAdmin">{{ $label }}</label>
    @endif

    @if ($layout === 'banner')
        <div data-upload-trigger="{{ $idBase }}" class="h-44 rounded-2xl overflow-hidden bg-cardMuted border border-borderSoft relative group cursor-pointer">
            <img id="preview_{{ $idBase }}" src="{{ $resolvedValue }}" data-fallback="{{ $defaultUrl }}" alt="{{ $label }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
            <div class="absolute inset-0 bg-black/35 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                <span class="px-3.5 py-1.5 rounded-full bg-white/95 text-[11px] font-semibold text-inkAdmin shadow flex items-center space-x-1.5">
                    <i data-lucide="upload" class="w-3.5 h-3.5 text-moss"></i>
                    <span>Klik untuk Ganti Foto</span>
                </span>
            </div>
        </div>
        <input type="hidden" id="input_{{ $idBase }}" name="{{ $name }}" value="{{ $resolvedValue }}">
        <input type="file" id="file_{{ $idBase }}" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden"
               data-upload-input
               data-target-input="input_{{ $idBase }}"
               data-target-preview="preview_{{ $idBase }}"
               data-target-status="status_{{ $idBase }}"
               data-upload-prefix="{{ $prefix }}">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center space-x-2">
                <button type="button" data-upload-trigger="{{ $idBase }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-cardMuted hover:bg-[#E5ECE0] border border-borderSoft text-xs font-semibold text-moss transition">
                    <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                    <span>{{ $buttonLabel }}</span>
                </button>
                @if ($defaultUrl)
                    <button type="button"
                            data-reset-photo
                            data-target-input="input_{{ $idBase }}"
                            data-target-preview="preview_{{ $idBase }}"
                            data-target-status="status_{{ $idBase }}"
                            data-default-url="{{ $defaultUrl }}"
                            @if ($sectionSlug && $fieldKey) data-reset-endpoint="{{ route('admin.konten.reset', $sectionSlug) }}" data-reset-field="{{ $fieldKey }}" @endif
                            class="inline-flex items-center space-x-1.5 px-3 py-2 rounded-xl bg-white hover:bg-rose-50 border border-borderSoft text-[11px] font-medium text-inkMuted hover:text-rose-600 transition" title="Kembalikan Foto Default">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset</span>
                    </button>
                @endif
            </div>
            <span id="status_{{ $idBase }}" class="text-[10px] text-inkMuted block">{{ $hint }}</span>
        </div>
    @elseif ($layout === 'square')
        <div class="bg-cardMuted/60 rounded-2xl p-4 border border-borderSoft">
            <div class="w-36 h-36 mx-auto rounded-xl overflow-hidden border border-borderSoft bg-white mb-3 p-2 flex items-center justify-center cursor-pointer" data-upload-trigger="{{ $idBase }}">
                <img id="preview_{{ $idBase }}" src="{{ $resolvedValue }}" data-fallback="{{ $defaultUrl }}" alt="{{ $label }}" class="w-full h-full object-contain">
            </div>
            <input type="hidden" id="input_{{ $idBase }}" name="{{ $name }}" value="{{ $resolvedValue }}">
            <input type="file" id="file_{{ $idBase }}" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden"
                   data-upload-input
                   data-target-input="input_{{ $idBase }}"
                   data-target-preview="preview_{{ $idBase }}"
                   data-target-status="status_{{ $idBase }}"
                   data-upload-prefix="{{ $prefix }}">
            <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                <button type="button" data-upload-trigger="{{ $idBase }}" class="inline-flex items-center space-x-1.5 bg-moss text-white px-3 py-1.5 rounded-xl text-[11px] font-medium hover:bg-adminMossLight transition">
                    <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                    <span>{{ $buttonLabel }}</span>
                </button>
                @if ($defaultUrl)
                    <button type="button"
                            data-reset-photo
                            data-target-input="input_{{ $idBase }}"
                            data-target-preview="preview_{{ $idBase }}"
                            data-target-status="status_{{ $idBase }}"
                            data-default-url="{{ $defaultUrl }}"
                            @if ($sectionSlug && $fieldKey) data-reset-endpoint="{{ route('admin.konten.reset', $sectionSlug) }}" data-reset-field="{{ $fieldKey }}" @endif
                            class="inline-flex items-center space-x-1 bg-white border border-borderSoft text-inkMuted hover:text-rose-700 hover:border-rose-200 px-2.5 py-1.5 rounded-xl text-[11px] font-medium transition">
                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                        <span>Reset Default</span>
                    </button>
                @endif
            </div>
            <span id="status_{{ $idBase }}" class="block text-center text-[11px] text-inkMuted">{{ $hint }}</span>
        </div>
    @else
        <div class="flex items-center space-x-3.5 p-3 rounded-2xl bg-cardMuted border border-borderSoft">
            <img id="preview_{{ $idBase }}" src="{{ $resolvedValue }}" data-fallback="{{ $defaultUrl }}" alt="{{ $label }}" data-upload-trigger="{{ $idBase }}" class="{{ $thumbClass }} object-cover rounded-xl border border-borderSoft shadow-sm cursor-pointer hover:opacity-90 transition shrink-0">
            <div class="flex-1 min-w-0">
                <input type="hidden" id="input_{{ $idBase }}" name="{{ $name }}" value="{{ $resolvedValue }}">
                <input type="file" id="file_{{ $idBase }}" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden"
                       data-upload-input
                       data-target-input="input_{{ $idBase }}"
                       data-target-preview="preview_{{ $idBase }}"
                       data-target-status="status_{{ $idBase }}"
                       data-upload-prefix="{{ $prefix }}">
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" data-upload-trigger="{{ $idBase }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-white hover:bg-[#E5ECE0] border border-borderSoft text-xs font-semibold text-moss transition shadow-sm">
                        <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                        <span>{{ $buttonLabel }}</span>
                    </button>
                    @if ($defaultUrl)
                        <button type="button"
                                data-reset-photo
                                data-target-input="input_{{ $idBase }}"
                                data-target-preview="preview_{{ $idBase }}"
                                data-target-status="status_{{ $idBase }}"
                                data-default-url="{{ $defaultUrl }}"
                                @if ($sectionSlug && $fieldKey) data-reset-endpoint="{{ route('admin.konten.reset', $sectionSlug) }}" data-reset-field="{{ $fieldKey }}" @endif
                                class="inline-flex items-center space-x-1 px-2.5 py-2 rounded-xl bg-white hover:bg-rose-50 border border-borderSoft text-[11px] font-medium text-inkMuted hover:text-rose-600 transition">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                            <span>Reset</span>
                        </button>
                    @endif
                </div>
                <span id="status_{{ $idBase }}" class="text-[10px] text-inkMuted block mt-1">{{ $hint }}</span>
            </div>
        </div>
    @endif
</div>
