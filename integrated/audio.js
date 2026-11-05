const audio = document.querySelector('#wedding-audio');
const toggle = document.querySelector('#audio-toggle');
const playIcon = document.querySelector('[data-audio-play]');
const pauseIcon = document.querySelector('[data-audio-pause]');
const status = document.querySelector('#audio-status');

if (audio instanceof HTMLAudioElement
  && toggle instanceof HTMLButtonElement
  && playIcon instanceof SVGElement
  && pauseIcon instanceof SVGElement
  && status instanceof HTMLElement) {
  const updatePlaybackState = () => {
    const isPlaying = !audio.paused;
    toggle.setAttribute('aria-pressed', String(isPlaying));
    toggle.setAttribute('aria-label', isPlaying ? 'Jeda musik' : 'Putar musik');
    playIcon.hidden = isPlaying;
    pauseIcon.hidden = !isPlaying;
  };

  const playAudio = async () => {
    try {
      await audio.play();
      status.textContent = 'Musik sedang diputar.';
    } catch (error) {
      if (error instanceof DOMException && error.name === 'NotAllowedError') {
        status.textContent = 'Autoplay diblokir. Tekan tombol putar untuk memulai musik.';
        return;
      }

      status.textContent = 'Musik tidak dapat diputar. Periksa file audio atau koneksi Anda.';
      console.error('Gagal memutar musik undangan.', error);
    }

    updatePlaybackState();
  };

  audio.volume = 1;
  audio.addEventListener('play', updatePlaybackState);
  audio.addEventListener('pause', updatePlaybackState);
  audio.addEventListener('ended', updatePlaybackState);
  audio.addEventListener('error', () => {
    status.textContent = 'File musik tidak dapat dimuat.';
    console.error('Gagal memuat file musik undangan.', audio.error);
  });

  toggle.addEventListener('click', () => {
    if (audio.paused) {
      void playAudio();
      return;
    }

    audio.pause();
    status.textContent = 'Musik dijeda.';
    updatePlaybackState();
  });

  document.querySelectorAll('a[href="#about"]').forEach((link) => {
    link.addEventListener('click', () => {
      if (audio.paused) {
        void playAudio();
      }
    });
  });

  void playAudio();
}
