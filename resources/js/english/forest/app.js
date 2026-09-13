/**
 * 英語の森 – エントリーポイント
 *
 * 3D の島（ForestWorld）を起動し、画面上の HUD と接続する。
 * このページはログイン必須のルート（english.forest）からのみ描画される。
 */
import { ForestWorld } from './world.js';

/** WebGL が使えるかどうか */
function webglSupported() {
    try {
        const canvas = document.createElement('canvas');
        return !!(window.WebGLRenderingContext && (canvas.getContext('webgl2') || canvas.getContext('webgl')));
    } catch {
        return false;
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    const canvas = document.getElementById('forest-canvas');
    const config = window.__FOREST_CONFIG__;
    if (!canvas || !config) return;

    const el = (id) => document.getElementById(id);
    const loading = el('forest-loading');
    const unsupported = el('forest-unsupported');
    const prompt = el('forest-prompt');
    const promptName = el('forest-prompt-name');
    const promptDesc = el('forest-prompt-desc');
    const promptBtn = el('forest-prompt-btn');
    const fade = el('forest-fade');
    const fadeText = el('forest-fade-text');
    const stick = el('forest-stick');
    const help = el('forest-help');

    if (!webglSupported()) {
        loading?.classList.add('is-hidden');
        unsupported?.classList.remove('is-hidden');
        return;
    }

    let navigating = false;
    const enterSpot = (spot) => {
        if (navigating) return;
        navigating = true;
        if (fadeText) fadeText.textContent = `${spot.name}へ移動中…`;
        fade?.classList.add('is-visible');
        window.setTimeout(() => { window.location.href = spot.url; }, 480);
    };

    const world = new ForestWorld(canvas, { ...config, stick }, {
        onReady: () => {
            loading?.classList.add('is-hidden');
        },
        onNearSpotChange: (spot) => {
            if (!prompt) return;
            if (spot) {
                promptName.textContent = spot.name;
                promptDesc.textContent = spot.desc;
                prompt.style.setProperty('--spot-color', spot.color);
                prompt.classList.add('is-visible');
            } else {
                prompt.classList.remove('is-visible');
            }
        },
        onEnter: enterSpot,
    });

    // デバッグ・動作確認用（DevTools から島の状態を覗けるようにする）
    window.__FOREST_WORLD__ = world;

    try {
        await world.init();
        world.start();
    } catch (error) {
        console.error('[forest] 島の読み込みに失敗しました', error);
        loading?.classList.add('is-hidden');
        unsupported?.classList.remove('is-hidden');
        return;
    }

    // 「はいる」ボタン（タッチ操作用。キーボードは E / Enter / Space）
    promptBtn?.addEventListener('click', () => world.input.triggerInteract());

    // 操作説明パネルの開閉
    el('forest-help-toggle')?.addEventListener('click', () => help?.classList.toggle('is-open'));
    el('forest-help-close')?.addEventListener('click', () => help?.classList.remove('is-open'));

    // タブが隠れている間は描画を止める（電池とファンにやさしく）
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) world.stop();
        else if (!navigating) world.start();
    });

    window.addEventListener('pagehide', () => world.dispose(), { once: true });
});
