/**
 * 英語の森 – 入力（キーボード / マウス / タッチ）
 *
 * ・移動：WASD・矢印キー、またはタッチの左側に出るバーチャルスティック
 * ・視点：画面のドラッグ、ホイール（ピンチ）でズーム
 * ・決定：E / Enter / Space
 */
const MOVE_KEYS = {
    KeyW: [0, 1], ArrowUp: [0, 1],
    KeyS: [0, -1], ArrowDown: [0, -1],
    KeyA: [-1, 0], ArrowLeft: [-1, 0],
    KeyD: [1, 0], ArrowRight: [1, 0],
};

export class Input {
    /**
     * @param {HTMLElement} element  ドラッグを受け付ける要素（canvas）
     * @param {HTMLElement} stickEl  バーチャルスティックの DOM（タッチ時のみ表示）
     */
    constructor(element, stickEl) {
        this.element = element;
        this.stickEl = stickEl;
        this.keys = new Set();
        this.move = { x: 0, y: 0 };   // -1〜1（y は前後）
        this.look = { dx: 0, dy: 0 }; // 1 フレーム分の視点移動量
        this.zoom = 0;
        this.running = false;
        this.interactPressed = false;
        this._pointers = new Map();
        this._stick = null;           // { id, baseX, baseY, knob }
        this._dragId = null;
        this._pinchDist = 0;

        this._bind();
    }

    _bind() {
        this._onKeyDown = (e) => {
            if (e.repeat) return;
            if (e.code in MOVE_KEYS) { this.keys.add(e.code); e.preventDefault(); }
            if (e.code === 'ShiftLeft' || e.code === 'ShiftRight') this.running = true;
            if (e.code === 'KeyE' || e.code === 'Enter' || e.code === 'Space') {
                this.interactPressed = true;
                e.preventDefault();
            }
        };
        this._onKeyUp = (e) => {
            this.keys.delete(e.code);
            if (e.code === 'ShiftLeft' || e.code === 'ShiftRight') this.running = false;
        };
        this._onBlur = () => { this.keys.clear(); this.running = false; };

        window.addEventListener('keydown', this._onKeyDown);
        window.addEventListener('keyup', this._onKeyUp);
        window.addEventListener('blur', this._onBlur);

        this.element.addEventListener('pointerdown', this._onPointerDown = (e) => {
            this.element.setPointerCapture?.(e.pointerId);
            this._pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });

            const isTouch = e.pointerType === 'touch';
            const leftHalf = e.clientX < window.innerWidth * 0.5;
            if (isTouch && leftHalf && !this._stick) {
                this._stick = { id: e.pointerId, baseX: e.clientX, baseY: e.clientY };
                this._showStick(e.clientX, e.clientY, 0, 0);
            } else if (this._dragId === null) {
                this._dragId = e.pointerId;
            }
        });

        this.element.addEventListener('pointermove', this._onPointerMove = (e) => {
            const prev = this._pointers.get(e.pointerId);
            if (!prev) return;
            const dx = e.clientX - prev.x;
            const dy = e.clientY - prev.y;
            this._pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });

            if (this._stick && this._stick.id === e.pointerId) {
                const maxR = 55;
                let ox = e.clientX - this._stick.baseX;
                let oy = e.clientY - this._stick.baseY;
                const d = Math.hypot(ox, oy);
                if (d > maxR) { ox *= maxR / d; oy *= maxR / d; }
                this.move.x = ox / maxR;
                this.move.y = -oy / maxR;
                this.running = d > maxR * 0.85;
                this._showStick(this._stick.baseX, this._stick.baseY, ox, oy);
                return;
            }

            if (this._dragId === e.pointerId) {
                this.look.dx += dx;
                this.look.dy += dy;
            }

            // 2 本指ピンチでズーム
            if (this._pointers.size === 2) {
                const [a, b] = [...this._pointers.values()];
                const dist = Math.hypot(a.x - b.x, a.y - b.y);
                if (this._pinchDist) this.zoom += (this._pinchDist - dist) * 0.02;
                this._pinchDist = dist;
            }
        });

        const endPointer = (e) => {
            this._pointers.delete(e.pointerId);
            if (this._stick && this._stick.id === e.pointerId) {
                this._stick = null;
                this.move.x = 0;
                this.move.y = 0;
                this.running = false;
                this._hideStick();
            }
            if (this._dragId === e.pointerId) this._dragId = null;
            if (this._pointers.size < 2) this._pinchDist = 0;
        };
        this.element.addEventListener('pointerup', this._onPointerUp = endPointer);
        this.element.addEventListener('pointercancel', this._onPointerCancel = endPointer);

        this.element.addEventListener('wheel', this._onWheel = (e) => {
            this.zoom += e.deltaY * 0.01;
            e.preventDefault();
        }, { passive: false });

        this.element.addEventListener('contextmenu', this._onContextMenu = (e) => e.preventDefault());
    }

    _showStick(baseX, baseY, ox, oy) {
        if (!this.stickEl) return;
        this.stickEl.style.display = 'block';
        this.stickEl.style.left = `${baseX}px`;
        this.stickEl.style.top = `${baseY}px`;
        const knob = this.stickEl.firstElementChild;
        if (knob) knob.style.transform = `translate(calc(-50% + ${ox}px), calc(-50% + ${oy}px))`;
    }

    _hideStick() {
        if (this.stickEl) this.stickEl.style.display = 'none';
    }

    /** キーボードの入力を move に反映して、正規化した移動量を返す。 */
    readMove() {
        if (this._stick) return { x: this.move.x, y: this.move.y };

        let x = 0, y = 0;
        for (const code of this.keys) {
            const [kx, ky] = MOVE_KEYS[code];
            x += kx; y += ky;
        }
        const len = Math.hypot(x, y);
        if (len > 1) { x /= len; y /= len; }
        return { x, y };
    }

    /** 視点の移動量を取り出す（取り出すとリセットされる）。 */
    consumeLook() {
        const look = { ...this.look };
        this.look.dx = 0;
        this.look.dy = 0;
        return look;
    }

    consumeZoom() {
        const z = this.zoom;
        this.zoom = 0;
        return z;
    }

    consumeInteract() {
        const pressed = this.interactPressed;
        this.interactPressed = false;
        return pressed;
    }

    /** HUD のボタンなどから決定を発火させる。 */
    triggerInteract() { this.interactPressed = true; }

    dispose() {
        window.removeEventListener('keydown', this._onKeyDown);
        window.removeEventListener('keyup', this._onKeyUp);
        window.removeEventListener('blur', this._onBlur);
        this.element.removeEventListener('pointerdown', this._onPointerDown);
        this.element.removeEventListener('pointermove', this._onPointerMove);
        this.element.removeEventListener('pointerup', this._onPointerUp);
        this.element.removeEventListener('pointercancel', this._onPointerCancel);
        this.element.removeEventListener('wheel', this._onWheel);
        this.element.removeEventListener('contextmenu', this._onContextMenu);
    }
}
