/**
 * Axis English — "Biscuit Zoo" motion layer
 *
 * - .reveal            → pops into view on scroll (IntersectionObserver)
 * - [data-parallax]    → drifts vertically as the page scrolls
 * - .pop-burst         → clicking spawns a little burst of motif confetti
 * - .hover-hop / etc.  → mostly CSS; JS just retriggers the animation on tap
 *
 * All effects are disabled when the visitor prefers reduced motion.
 */

const REDUCE = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// original sprite motifs (defined in layouts/app.blade.php) — no emoji
const BURST_MOTIFS = ['m-sparkle', 'm-leaf', 'm-acorn', 'm-drop'];
const BURST_COLORS = ['#c98f3c', '#4a6f43', '#7a4a37', '#7b9b74', '#b9764f', '#5f7c93'];

function initReveal() {
    const els = Array.from(document.querySelectorAll('.reveal'));
    if (!els.length) return;

    const revealAll = () => els.forEach((el) => el.classList.add('is-visible'));

    if (REDUCE || !('IntersectionObserver' in window)) {
        revealAll();
        return;
    }

    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                io.unobserve(entry.target);
            });
        },
        // fire well before the element is fully on screen
        { threshold: 0.05, rootMargin: '0px 0px 15% 0px' }
    );

    els.forEach((el, i) => {
        if (!el.style.getPropertyValue('--reveal-delay')) {
            // stagger siblings so grids cascade in
            el.style.setProperty('--reveal-delay', `${(i % 6) * 60}ms`);
        }
        io.observe(el);
    });

    // Safety net: if the observer is throttled (e.g. background tab) or never
    // fires, never leave content hidden — reveal everything after a short beat,
    // and again whenever the tab becomes visible.
    setTimeout(revealAll, 1400);
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') setTimeout(revealAll, 200);
    });
}

function initParallax() {
    const layers = Array.from(document.querySelectorAll('[data-parallax]'));
    if (REDUCE || !layers.length) return;

    let ticking = false;
    const update = () => {
        const y = window.scrollY;
        layers.forEach((layer) => {
            const speed = parseFloat(layer.dataset.parallax) || 0.1;
            layer.style.transform = `translate3d(0, ${(-y * speed).toFixed(1)}px, 0)`;
        });
        ticking = false;
    };
    const onScroll = () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(update);
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    update();
}

function spawnBurst(x, y) {
    const count = 7;
    for (let i = 0; i < count; i += 1) {
        const bit = document.createElement('span');
        bit.className = 'fx-burst';
        const size = 10 + Math.random() * 8;
        const motif = BURST_MOTIFS[i % BURST_MOTIFS.length];
        bit.innerHTML =
            `<svg width="${size}" height="${size}" style="display:block">` +
            `<use href="#${motif}"/></svg>`;
        bit.style.left = `${x}px`;
        bit.style.top = `${y}px`;
        bit.style.color = BURST_COLORS[i % BURST_COLORS.length];

        const angle = (Math.PI * 2 * i) / count + Math.random() * 0.5;
        const dist = 34 + Math.random() * 48;
        bit.style.setProperty('--bx', `${Math.cos(angle) * dist}px`);
        bit.style.setProperty('--by', `${Math.sin(angle) * dist - 22}px`);
        bit.style.setProperty('--br', `${(Math.random() * 2 - 1) * 180}deg`);

        document.body.appendChild(bit);
        bit.addEventListener('animationend', () => bit.remove(), { once: true });
    }
}

function initBurst() {
    if (REDUCE) return;
    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('.pop-burst');
        if (!trigger) return;
        spawnBurst(event.clientX, event.clientY);
    });
}

function initTapAnimations() {
    if (REDUCE) return;
    // Touch devices have no :hover — retrigger the wobble/hop one-shot on tap.
    document.addEventListener('pointerdown', (event) => {
        const el = event.target.closest('.hover-wobble, .hover-hop, .hover-jelly');
        if (!el) return;
        el.style.animation = 'none';
        // force reflow so the animation can restart
        void el.offsetWidth;
        el.style.animation = '';
    });
}

export function initMotion() {
    initReveal();
    initParallax();
    initBurst();
    initTapAnimations();
}

// Expose for pages that want to fire a celebration burst manually
// (e.g. quiz / lesson completion): window.biscuitBurst(x, y)
window.biscuitBurst = (x, y) => {
    if (REDUCE) return;
    spawnBurst(
        x ?? window.innerWidth / 2,
        y ?? window.innerHeight / 2
    );
};
