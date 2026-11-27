const MONTH_MAP = {
    januari: '01', january: '01', jan: '01',
    februari: '02', february: '02', feb: '02',
    maret: '03', march: '03', mar: '03',
    april: '04', apr: '04',
    mei: '05', may: '05',
    juni: '06', june: '06', jun: '06',
    juli: '07', july: '07', jul: '07',
    agustus: '08', august: '08', aug: '08', agu: '08',
    september: '09', sep: '09',
    oktober: '10', october: '10', oct: '10', okt: '10',
    november: '11', nov: '11',
    desember: '12', december: '12', dec: '12', des: '12',
};

function parseWeddingTarget(dateStr) {
    const fallback = new Date('2026-12-25T08:00:00+07:00').getTime();
    if (!dateStr) return fallback;

    const match = String(dateStr).trim().match(/(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/);
    if (!match) return fallback;

    const day = match[1].padStart(2, '0');
    const mon = MONTH_MAP[match[2].toLowerCase()];
    const year = match[3];
    if (!mon) return fallback;

    const parsed = new Date(`${year}-${mon}-${day}T08:00:00+07:00`).getTime();
    return Number.isNaN(parsed) ? fallback : parsed;
}

export function initCountdown() {
    const elDays = document.getElementById('cd-days');
    const elHours = document.getElementById('cd-hours');
    const elMinutes = document.getElementById('cd-minutes');
    const elSeconds = document.getElementById('cd-seconds');
    if (!elDays || !elHours || !elMinutes || !elSeconds) return;

    const dateLabelEl = document.getElementById('countdown-wedding-date');
    const targetTimestamp = parseWeddingTarget(dateLabelEl?.textContent);

    const tick = () => {
        const now = Date.now();
        let target = targetTimestamp;
        if (target <= now) {
            const nextDate = new Date(target);
            while (nextDate.getTime() <= now) {
                nextDate.setFullYear(nextDate.getFullYear() + 1);
            }
            target = nextDate.getTime();
        }

        const totalSec = Math.floor(Math.max(0, target - now) / 1000);
        const days = Math.floor(totalSec / 86400);
        const hours = Math.floor((totalSec % 86400) / 3600);
        const minutes = Math.floor((totalSec % 3600) / 60);
        const seconds = totalSec % 60;

        elDays.textContent = String(days).padStart(2, '0');
        elHours.textContent = String(hours).padStart(2, '0');
        elMinutes.textContent = String(minutes).padStart(2, '0');
        elSeconds.textContent = String(seconds).padStart(2, '0');
    };

    tick();
    window.setInterval(tick, 1000);
}
