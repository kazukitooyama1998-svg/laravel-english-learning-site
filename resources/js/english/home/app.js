/**
 * じぶんの家 – エントリーポイント
 *
 * 室内の 3D 空間（InteriorWorld）を起動し、模様替え用の HUD と接続する。
 * 家具の配置・床・壁はサーバーへ保存し、次に入ったときに再現される。
 */
import { InteriorWorld } from './world.js';

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
    const canvas = document.getElementById('home-canvas');
    const config = window.__HOME_CONFIG__;
    if (!canvas || !config) return;

    const el = (id) => document.getElementById(id);
    const loading = el('home-loading');
    const unsupported = el('home-unsupported');
    const editPanel = el('home-edit-panel');
    const editToggle = el('home-edit-toggle');
    const saveBtn = el('home-save');
    const saveState = el('home-save-state');
    const selectedName = el('home-selected-name');
    const selectionBox = el('home-selection');
    const stick = el('home-stick');

    if (!webglSupported()) {
        loading?.classList.add('is-hidden');
        unsupported?.classList.remove('is-hidden');
        return;
    }

    let dirty = false;
    const markDirty = () => {
        dirty = true;
        if (saveState) {
            saveState.textContent = '未保存の変更があります';
            saveState.classList.add('is-dirty');
        }
    };

    const world = new InteriorWorld(canvas, { ...config, stick }, {
        onReady: () => loading?.classList.add('is-hidden'),
        onDirty: markDirty,
        onSelect: (item) => {
            if (!selectionBox) return;
            selectionBox.classList.toggle('is-visible', !!item);
            if (item && selectedName) selectedName.textContent = item.name;
        },
    });

    window.__HOME_WORLD__ = world;   // デバッグ・動作確認用

    try {
        await world.init();
        world.start();
    } catch (error) {
        console.error('[home] 部屋の読み込みに失敗しました', error);
        loading?.classList.add('is-hidden');
        unsupported?.classList.remove('is-hidden');
        return;
    }

    // ── 模様替えモードの切り替え ──────────────────────────────────
    const setEditing = (on) => {
        world.setEditMode(on);
        document.body.classList.toggle('is-editing', on);
        editPanel?.classList.toggle('is-open', on);
        if (editToggle) {
            editToggle.textContent = on ? '✓ 模様替えを終える' : '🛋 模様替え';
            editToggle.classList.toggle('btn--primary', !on);
        }
    };

    editToggle?.addEventListener('click', () => setEditing(!world.editing));

    // ── 家具カタログ ─────────────────────────────────────────────
    document.querySelectorAll('[data-add-furniture]').forEach((btn) => {
        btn.addEventListener('click', () => world.addItem(btn.dataset.addFurniture));
    });

    el('home-rotate')?.addEventListener('click', () => world.rotateSelected());
    el('home-remove')?.addEventListener('click', () => world.removeSelected());

    // ── 床・壁の選択 ─────────────────────────────────────────────
    const markActive = (group, value) => {
        document.querySelectorAll(`[data-${group}]`).forEach((btn) => {
            btn.classList.toggle('is-active', btn.dataset[group] === value);
        });
    };

    document.querySelectorAll('[data-floor]').forEach((btn) => {
        btn.addEventListener('click', () => {
            world.setFloor(btn.dataset.floor);
            markActive('floor', btn.dataset.floor);
        });
    });
    document.querySelectorAll('[data-wall]').forEach((btn) => {
        btn.addEventListener('click', () => {
            world.setWall(btn.dataset.wall);
            markActive('wall', btn.dataset.wall);
        });
    });
    markActive('floor', config.layout.floor);
    markActive('wall', config.layout.wall);

    // ── 保存 ─────────────────────────────────────────────────────
    saveBtn?.addEventListener('click', async () => {
        saveBtn.disabled = true;
        saveBtn.textContent = '保存中…';

        try {
            const response = await fetch(config.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(world.getLayout()),
            });
            if (!response.ok) throw new Error(`保存に失敗しました (${response.status})`);

            dirty = false;
            if (saveState) {
                saveState.textContent = '保存しました';
                saveState.classList.remove('is-dirty');
            }
        } catch (error) {
            console.error('[home] 保存に失敗', error);
            if (saveState) {
                saveState.textContent = '保存に失敗しました';
                saveState.classList.add('is-dirty');
            }
        } finally {
            saveBtn.disabled = false;
            saveBtn.textContent = '模様替えを保存';
        }
    });

    // 未保存のまま離れようとしたら確認する
    window.addEventListener('beforeunload', (event) => {
        if (!dirty) return;
        event.preventDefault();
        event.returnValue = '';
    });

    // キーボードショートカット（模様替え中）
    window.addEventListener('keydown', (event) => {
        if (!world.editing) return;
        if (event.code === 'KeyR') world.rotateSelected();
        if (event.code === 'Delete' || event.code === 'Backspace') {
            event.preventDefault();
            world.removeSelected();
        }
        if (event.code === 'Escape') setEditing(false);
    });

    // タブが隠れている間は描画を止める
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) world.stop();
        else world.start();
    });

    window.addEventListener('pagehide', () => world.dispose(), { once: true });
});
