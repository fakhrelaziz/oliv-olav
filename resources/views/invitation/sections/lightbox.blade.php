<!-- GALLERY LIGHTBOX MODAL (Full-Screen Image Viewer) -->
<div id="gallery-lightbox" class="fixed inset-0 z-50 bg-black/92 backdrop-blur-md hidden flex flex-col items-center justify-between p-4 sm:p-6 md:p-8 opacity-0 transition-opacity duration-300" role="dialog" aria-modal="true" aria-label="Galeri Foto Layar Penuh">
    <div class="w-full max-w-5xl flex items-center justify-between text-warmwhite z-20 pt-2 pb-4">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-olive-light"></span>
            <span id="lightbox-counter" class="text-xs sm:text-sm font-serif tracking-wider text-warmwhite/80">1 / {{ max(1, $totalPhotos) }}</span>
        </div>
        <button id="lightbox-close-btn" type="button" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 transition flex items-center justify-center text-warmwhite text-lg" aria-label="Tutup Galeri">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="relative w-full max-w-5xl flex-1 flex items-center justify-center min-h-0">
        <button id="lightbox-prev-btn" type="button" class="absolute left-2 sm:left-4 z-20 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-black/50 hover:bg-black/75 border border-white/20 text-warmwhite flex items-center justify-center transition hover:scale-105 active:scale-95 shadow-lg backdrop-blur-sm" aria-label="Foto Sebelumnya">
            <svg class="w-6 h-6 -ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <div class="relative max-h-full max-w-full flex items-center justify-center overflow-hidden">
            <img id="lightbox-image" src="" alt="Foto Galeri" class="max-h-[72vh] sm:max-h-[76vh] max-w-full object-contain rounded-lg shadow-2xl transition duration-300 scale-95" />
        </div>

        <button id="lightbox-next-btn" type="button" class="absolute right-2 sm:right-4 z-20 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-black/50 hover:bg-black/75 border border-white/20 text-warmwhite flex items-center justify-center transition hover:scale-105 active:scale-95 shadow-lg backdrop-blur-sm" aria-label="Foto Berikutnya">
            <svg class="w-6 h-6 -mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <div class="w-full max-w-3xl text-center z-20 pt-3 pb-2">
        <p id="lightbox-caption" class="font-serif text-sm sm:text-base text-warmwhite/90 drop-shadow-sm font-medium"></p>
    </div>
</div>
