/**
 * 英語の森 – 3D 空間内の文字ラベル
 *
 * Canvas に描いた文字をテクスチャにして Sprite で表示する。
 * Sprite は常にカメラを向くので、どの角度からでも読める。
 */
import * as THREE from 'three';

const FONT = '"Zen Maru Gothic", "M PLUS Rounded 1c", "Hiragino Maru Gothic ProN", system-ui, sans-serif';

/** 角丸の矩形パス */
function roundRect(ctx, x, y, w, h, r) {
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.arcTo(x + w, y, x + w, y + h, r);
    ctx.arcTo(x + w, y + h, x, y + h, r);
    ctx.arcTo(x, y + h, x, y, r);
    ctx.arcTo(x, y, x + w, y, r);
    ctx.closePath();
}

/**
 * 文字ラベルの Sprite を作る。
 * @param {string} text  メインの文字列
 * @param {object} opts  sub: 小さい副題 / bg: 背景色 / fg: 文字色 / height: ワールド上の高さ
 */
export function createLabel(text, opts = {}) {
    const {
        sub = '',
        bg = 'rgba(255,255,255,0.94)',
        fg = '#2e3a4f',
        subFg = '#5c6675',
        accent = '#3d5a80',
        height = 1.1,
    } = opts;

    const dpr = 2;
    const padX = 34, padY = 22;
    const mainSize = 46;
    const subSize = 26;

    const measure = document.createElement('canvas').getContext('2d');
    measure.font = `700 ${mainSize}px ${FONT}`;
    const mainW = measure.measureText(text).width;
    measure.font = `500 ${subSize}px ${FONT}`;
    const subW = sub ? measure.measureText(sub).width : 0;

    const w = Math.ceil(Math.max(mainW, subW) + padX * 2);
    const h = Math.ceil(mainSize + (sub ? subSize + 10 : 0) + padY * 2);

    const canvas = document.createElement('canvas');
    canvas.width = w * dpr;
    canvas.height = h * dpr;
    const ctx = canvas.getContext('2d');
    ctx.scale(dpr, dpr);

    // 背景（角丸カード）＋下側のアクセントライン
    ctx.fillStyle = bg;
    roundRect(ctx, 0, 0, w, h, 20);
    ctx.fill();
    ctx.fillStyle = accent;
    roundRect(ctx, 0, h - 7, w, 7, 3);
    ctx.fill();

    ctx.textAlign = 'center';
    ctx.textBaseline = 'top';
    ctx.fillStyle = fg;
    ctx.font = `700 ${mainSize}px ${FONT}`;
    ctx.fillText(text, w / 2, padY - 4);

    if (sub) {
        ctx.fillStyle = subFg;
        ctx.font = `500 ${subSize}px ${FONT}`;
        ctx.fillText(sub, w / 2, padY + mainSize + 4);
    }

    const texture = new THREE.CanvasTexture(canvas);
    texture.colorSpace = THREE.SRGBColorSpace;
    texture.minFilter = THREE.LinearFilter;

    const sprite = new THREE.Sprite(new THREE.SpriteMaterial({
        map: texture,
        transparent: true,
        depthTest: true,
        depthWrite: false,
    }));
    sprite.scale.set((w / h) * height, height, 1);
    sprite.renderOrder = 10;
    return sprite;
}
