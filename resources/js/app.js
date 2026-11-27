import { initOpeningCover } from './invitation/cover';
import { initAudioPlayer } from './invitation/audio';
import { initCountdown } from './invitation/countdown';
import { initNavigation } from './invitation/navigation';
import { initGallery } from './invitation/gallery';
import { initRsvpForm } from './invitation/rsvp';
import { initQrisAndGift } from './invitation/qris';
import { initImageFallbacks } from './invitation/image-fallback';
import { trackInvitationVisit } from './invitation/tracker';

document.addEventListener('DOMContentLoaded', () => {
    initImageFallbacks();
    initOpeningCover();
    initAudioPlayer();
    initCountdown();
    initNavigation();
    initGallery();
    initRsvpForm();
    initQrisAndGift();
    trackInvitationVisit();
});
