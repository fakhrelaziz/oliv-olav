@props([
    'photo',
    'wrapperClasses' => 'h-48 sm:h-full',
    'extraStyle' => '',
    'imgPosition' => 'object-center',
])

@php
    $src = \App\Services\MediaStorageService::normalizeAssetUrl($photo->foto_url, '/aset/section5_galerifoto(5).webp');
    $caption = $photo->keterangan ?: ($photo->judul ?: 'Momen Bahagia');
    $title = $photo->judul ?: 'Galeri Pernikahan';
@endphp

<div class="gallery-item cursor-pointer overflow-hidden shadow-md group relative rounded-xl transition-all duration-300 {{ $wrapperClasses }}"
     @if($extraStyle) style="{{ $extraStyle }}" @endif
     data-category="{{ $photo->kategori ?: 'prewedding' }}"
     data-lightbox-src="{{ $src }}"
     data-caption="{{ $caption }}">
    <img src="{{ $src }}"
         alt="{{ $title }}"
         data-fallback="/aset/section5_galerifoto(5).webp"
         loading="lazy"
         decoding="async"
         class="w-full h-full object-cover {{ $imgPosition }} transition duration-500 group-hover:scale-105 dynamic-gallery-img" />
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3.5">
        <span class="text-[9px] uppercase tracking-[0.2em] text-warmwhite/80">{{ $photo->category_label }}</span>
        <p class="font-serif text-xs text-warmwhite font-medium">{{ $title }}</p>
    </div>
</div>
