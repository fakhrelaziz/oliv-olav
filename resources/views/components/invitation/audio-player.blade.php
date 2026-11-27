<aside class="audio-player" aria-label="Kontrol musik undangan">
    <audio id="wedding-audio" autoplay loop preload="auto">
        <source src="{{ asset('aset/sound_undangan_terbuangdalamwaktu.mp3.mpeg') }}" type="audio/mpeg" />
    </audio>
    <button class="audio-toggle" id="audio-toggle" type="button" aria-label="Putar musik" aria-pressed="false" title="Putar / Jeda Musik Latar">
        <img src="{{ asset('aset/vinyl_record_icon.webp') }}" alt="" class="audio-vinyl-disc" />
        <svg class="audio-icon audio-state-hidden" width="1" height="1" data-audio-play viewBox="0 0 24 24" aria-hidden="true"></svg>
        <svg class="audio-icon audio-state-hidden" width="1" height="1" data-audio-pause viewBox="0 0 24 24" aria-hidden="true" hidden></svg>
    </button>
    <span class="audio-status" id="audio-status" aria-live="polite"></span>
</aside>
