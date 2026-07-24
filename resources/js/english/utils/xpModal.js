/**
 * Populate and show the result modal with XP / level data.
 *
 * Expected server response shape:
 *   { gained_xp, total_xp, next_level_xp, level, xp_in_level, bar_percent }
 */
export function showXpModal(data, onContinue = null) {
    const modal = document.getElementById('result-modal');
    if (!modal) return;

    const {
        gained_xp    = 0,
        level        = 1,
        xp_in_level  = 0,
        bar_percent  = 0,
    } = data;

    const setText = (id, text) => {
        const el = document.getElementById(id);
        if (el) el.textContent = text;
    };

    setText('gained-xp-text',    `+${gained_xp} XP`);
    setText('level-text',         `Level ${level}`);
    setText('xp-progress-text',   `${xp_in_level} / 500 XP`);

    // WPM / accuracy / time (optional extras)
    const wpmEl  = document.getElementById('result-wpm');
    const accEl  = document.getElementById('result-accuracy');
    const timeEl = document.getElementById('result-time');
    if (data.wpm  !== undefined && wpmEl)  wpmEl.textContent  = data.wpm;
    if (data.accuracy !== undefined && accEl) accEl.textContent = `${data.accuracy}%`;
    if (data.time_seconds !== undefined && timeEl) timeEl.textContent = `${data.time_seconds}s`;

    modal.classList.remove('hidden');

    // Animate XP bar after a short delay (lets transition apply)
    const xpBar = document.getElementById('xp-bar');
    if (xpBar) {
        xpBar.style.width = '0%';
        requestAnimationFrame(() => requestAnimationFrame(() => {
            xpBar.style.width = `${bar_percent}%`;
        }));
    }

    // Wire continue / redirect button
    if (onContinue) {
        const btn = document.getElementById('continue-btn');
        if (btn) {
            btn.addEventListener('click', onContinue, { once: true });
        }
    }
}

export function hideXpModal() {
    const modal = document.getElementById('result-modal');
    if (modal) modal.classList.add('hidden');
}
