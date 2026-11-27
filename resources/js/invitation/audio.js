export function initAudioPlayer() {
    const audio = document.getElementById('wedding-audio');
    const toggleBtn = document.getElementById('audio-toggle');
    const statusEl = document.getElementById('audio-status');
    if (!audio || !toggleBtn) return;

    const playIcon = toggleBtn.querySelector('[data-audio-play]');
    const pauseIcon = toggleBtn.querySelector('[data-audio-pause]');
    let userPausedManually = false;

    const syncUi = (isPlaying) => {
        toggleBtn.classList.toggle('is-playing', isPlaying);
        toggleBtn.setAttribute('aria-pressed', isPlaying ? 'true' : 'false');
        toggleBtn.setAttribute('aria-label', isPlaying ? 'Jeda musik' : 'Putar musik');
        if (playIcon) playIcon.hidden = isPlaying;
        if (pauseIcon) pauseIcon.hidden = !isPlaying;
        if (statusEl) statusEl.textContent = isPlaying ? 'Musik diputar' : 'Musik dijeda';
    };

    const tryPlay = () => {
        if (userPausedManually) return;
        const promise = audio.play();
        if (promise && typeof promise.then === 'function') {
            promise.then(() => syncUi(true)).catch(() => syncUi(false));
        }
    };

    toggleBtn.addEventListener('click', () => {
        if (audio.paused) {
            userPausedManually = false;
            tryPlay();
        } else {
            userPausedManually = true;
            audio.pause();
            syncUi(false);
        }
    });

    audio.addEventListener('play', () => syncUi(true));
    audio.addEventListener('pause', () => syncUi(false));

    // Automatically start music when guest clicks "Buka Undangan"
    window.addEventListener('invitation:opened', () => {
        if (!userPausedManually && audio.paused) {
            tryPlay();
        }
    });

    // Respect browsers that allow autoplay immediately, otherwise wait for cover open
    syncUi(!audio.paused);
}
