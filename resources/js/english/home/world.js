/**
 * じぶんの家 – 室内の 3D 空間
 *
 * 島と同じ操作でキャラクターを歩かせられる部屋。
 * 「模様替えモード」に切り替えると、家具をドラッグで動かし、回転・削除・追加ができる。
 * 島側（forest）のプレイヤー・入力・ラベルをそのまま再利用している。
 */
import * as THREE from 'three';
import { Player } from '../forest/player.js';
import { Input } from '../forest/input.js';
import { buildFurniture, setHighlight } from './furniture.js';

const WALL_THICK = 0.3;

export class InteriorWorld {
    /**
     * @param {HTMLCanvasElement} canvas
     * @param {object} config { player, catalog, layout, stick }
     * @param {object} hooks  { onReady, onSelect, onDirty }
     */
    constructor(canvas, config, hooks = {}) {
        this.canvas = canvas;
        this.config = config;
        this.hooks = hooks;
        this.room = config.catalog.room;
        this.time = 0;
        this.running = false;
        this.editing = false;
        this.selected = null;
        this.items = [];          // { key, object, def }
        this._initializing = true;   // 読み込み中は「未保存の変更」にしない

        this.camYaw = 0;
        this.camPitch = 0.42;
        this.camDistance = 8.5;

        this.raycaster = new THREE.Raycaster();
        this.pointer = new THREE.Vector2();
        this.floorPlane = new THREE.Plane(new THREE.Vector3(0, 1, 0), 0);
    }

    async init() {
        try { await document.fonts?.ready; } catch { /* フォント API 非対応でも続行 */ }

        this._initRenderer();
        this._initScene();
        this._initLights();
        this._buildRoom();

        // 保存済みのレイアウトを並べる
        for (const item of this.config.layout.items ?? []) {
            this._spawnItem(item.key, item.x, item.z, item.rot);
        }
        this.setFloor(this.config.layout.floor ?? 'oak');
        this.setWall(this.config.layout.wall ?? 'cream');

        this.player = new Player(this.scene, this.config.player);
        this.player.position.set(0, 0, this.room.depth / 2 - 1.6);
        this.player.facing = Math.PI;

        this.input = new Input(this.canvas, this.config.stick);
        this._bindEditPointer();

        this._resize();
        this._onResize = () => this._resize();
        window.addEventListener('resize', this._onResize);

        this._updateCamera(1, true);
        this._initializing = false;
        this.hooks.onReady?.();
    }

    _initRenderer() {
        this.renderer = new THREE.WebGLRenderer({ canvas: this.canvas, antialias: true, powerPreference: 'high-performance' });
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, window.innerWidth < 820 ? 1.5 : 2));
        this.renderer.shadowMap.enabled = true;
        this.renderer.shadowMap.type = THREE.PCFShadowMap;
        this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
        this.renderer.toneMappingExposure = 1.0;
    }

    _initScene() {
        this.scene = new THREE.Scene();
        this.scene.background = new THREE.Color(0xdfe7ee);
        this.camera = new THREE.PerspectiveCamera(50, 1, 0.1, 200);
        this.camera.position.set(0, 6, 10);
    }

    _initLights() {
        // 室内は窓からの光を主役に、天井の反射光で全体を持ち上げる
        this.scene.add(new THREE.HemisphereLight(0xd8e8f5, 0xb09a7c, 1.5));
        this.scene.add(new THREE.AmbientLight(0xffffff, 0.35));

        const windowLight = new THREE.DirectionalLight(0xfff1d8, 2.0);
        windowLight.position.set(-6, 7, 8);
        windowLight.castShadow = true;
        windowLight.shadow.mapSize.set(2048, 2048);
        const span = Math.max(this.room.width, this.room.depth);
        windowLight.shadow.camera.left = -span;
        windowLight.shadow.camera.right = span;
        windowLight.shadow.camera.top = span;
        windowLight.shadow.camera.bottom = -span;
        windowLight.shadow.camera.near = 1;
        windowLight.shadow.camera.far = 40;
        windowLight.shadow.bias = -0.0005;
        windowLight.shadow.normalBias = 0.02;
        this.scene.add(windowLight);

        const bounce = new THREE.DirectionalLight(0xffe9cf, 0.4);
        bounce.position.set(5, 4, -6);
        this.scene.add(bounce);
    }

    // ── 部屋 ─────────────────────────────────────────────────────────

    _buildRoom() {
        const { width: W, depth: D, height: H } = this.room;

        this.floorMat = new THREE.MeshStandardMaterial({ color: 0xc89a63, roughness: 0.85, metalness: 0 });
        this.wallMat = new THREE.MeshStandardMaterial({ color: 0xf2e8d5, roughness: 0.95, metalness: 0 });
        const trimMat = new THREE.MeshStandardMaterial({ color: 0xfdfaf2, roughness: 0.8, metalness: 0 });

        const floor = new THREE.Mesh(new THREE.BoxGeometry(W, 0.3, D), this.floorMat);
        floor.position.y = -0.15;
        floor.receiveShadow = true;
        this.scene.add(floor);

        // 床板の目地（板張りに見せる）
        const seamMat = new THREE.MeshStandardMaterial({ color: 0x000000, transparent: true, opacity: 0.07, roughness: 1 });
        for (let x = -W / 2 + 1; x < W / 2; x += 1) {
            const seam = new THREE.Mesh(new THREE.BoxGeometry(0.03, 0.01, D), seamMat);
            seam.position.set(x, 0.005, 0);
            this.scene.add(seam);
        }

        const ceiling = new THREE.Mesh(new THREE.BoxGeometry(W, 0.2, D), trimMat);
        ceiling.position.y = H + 0.1;
        this.scene.add(ceiling);
        this.ceiling = ceiling;

        // 壁（手前の壁は視界を塞ぐので作らない＝ドールハウス方式）
        this.walls = [];
        const wallDefs = [
            { w: W + WALL_THICK * 2, d: WALL_THICK, x: 0, z: -D / 2 - WALL_THICK / 2 },   // 奥
            { w: WALL_THICK, d: D, x: -W / 2 - WALL_THICK / 2, z: 0 },                    // 左
            { w: WALL_THICK, d: D, x: W / 2 + WALL_THICK / 2, z: 0 },                     // 右
        ];
        for (const def of wallDefs) {
            const wall = new THREE.Mesh(new THREE.BoxGeometry(def.w, H, def.d), this.wallMat);
            wall.position.set(def.x, H / 2, def.z);
            wall.receiveShadow = true;
            this.scene.add(wall);
            this.walls.push(wall);
        }

        // 幅木（壁と床の境目）
        for (const def of wallDefs) {
            const skirt = new THREE.Mesh(new THREE.BoxGeometry(def.w + 0.08, 0.18, def.d + 0.08), trimMat);
            skirt.position.set(def.x, 0.09, def.z);
            this.scene.add(skirt);
        }

        this._buildWindow(-W / 2 - WALL_THICK / 2, H);
        this._buildDoor(0, -D / 2 - WALL_THICK / 2, H);
        this._buildRug();
    }

    /** 左の壁の出窓（外の空が見える） */
    _buildWindow(x, H) {
        const frameMat = new THREE.MeshStandardMaterial({ color: 0xfdfaf2, roughness: 0.7 });
        const skyMat = new THREE.MeshStandardMaterial({
            color: 0x9fd0e8, emissive: 0x9fd0e8, emissiveIntensity: 0.85, roughness: 1,
        });

        const frame = new THREE.Mesh(new THREE.BoxGeometry(0.2, 1.9, 2.9), frameMat);
        frame.position.set(x + 0.06, 1.9, 0.6);
        this.scene.add(frame);

        const glass = new THREE.Mesh(new THREE.BoxGeometry(0.1, 1.6, 2.6), skyMat);
        glass.position.set(x + 0.16, 1.9, 0.6);
        this.scene.add(glass);

        const barV = new THREE.Mesh(new THREE.BoxGeometry(0.12, 1.6, 0.08), frameMat);
        barV.position.set(x + 0.2, 1.9, 0.6);
        this.scene.add(barV);

        const sill = new THREE.Mesh(new THREE.BoxGeometry(0.45, 0.12, 3.1), frameMat);
        sill.position.set(x + 0.2, 0.98, 0.6);
        this.scene.add(sill);
    }

    /** 奥の壁の扉（外に出る場所の目印） */
    _buildDoor(x, z, H) {
        const frameMat = new THREE.MeshStandardMaterial({ color: 0xfdfaf2, roughness: 0.7 });
        const doorMat = new THREE.MeshStandardMaterial({ color: 0x9a6f47, roughness: 0.8 });

        const frame = new THREE.Mesh(new THREE.BoxGeometry(1.8, 2.6, 0.24), frameMat);
        frame.position.set(x, 1.3, z + 0.06);
        this.scene.add(frame);

        const door = new THREE.Mesh(new THREE.BoxGeometry(1.5, 2.35, 0.14), doorMat);
        door.position.set(x, 1.18, z + 0.16);
        this.scene.add(door);

        const knob = new THREE.Mesh(
            new THREE.SphereGeometry(0.08, 10, 8),
            new THREE.MeshStandardMaterial({ color: 0xd8c48a, metalness: 0.4, roughness: 0.35 }),
        );
        knob.position.set(x + 0.55, 1.2, z + 0.24);
        this.scene.add(knob);
    }

    /** 玄関マット（扉の前） */
    _buildRug() {
        const mat = new THREE.MeshStandardMaterial({ color: 0xb5a184, roughness: 1 });
        const mat2 = new THREE.Mesh(new THREE.BoxGeometry(1.6, 0.03, 0.9), mat);
        mat2.position.set(0, 0.015, -this.room.depth / 2 + 0.7);
        mat2.receiveShadow = true;
        this.scene.add(mat2);
    }

    // ── 家具 ─────────────────────────────────────────────────────────

    /** 模様替えの変更を通知する（初期化中は無視） */
    _markDirty() {
        if (!this._initializing) this.hooks.onDirty?.();
    }

    _spawnItem(key, x, z, rot) {
        const def = this.config.catalog.furniture[key];
        if (!def) return null;

        const object = buildFurniture(key, def);
        object.position.set(x, 0, z);
        object.rotation.y = rot ?? 0;
        this.scene.add(object);

        const item = { key, def, object };
        this.items.push(item);
        return item;
    }

    /** カタログから家具を足す（部屋の中央付近に置く） */
    addItem(key) {
        const spot = this._freeSpot();
        const item = this._spawnItem(key, spot.x, spot.z, 0);
        if (item) {
            this.select(item);
            this._markDirty();
        }
        return item;
    }

    /** 家具が重ならない place を探す */
    _freeSpot() {
        const { width: W, depth: D } = this.room;
        for (let attempt = 0; attempt < 60; attempt++) {
            const x = (Math.random() - 0.5) * (W - 3);
            const z = (Math.random() - 0.5) * (D - 3);
            const clear = this.items.every((it) => Math.hypot(it.object.position.x - x, it.object.position.z - z) > 1.8);
            if (clear) return { x, z };
        }
        return { x: 0, z: 0 };
    }

    select(item) {
        if (this.selected === item) return;
        if (this.selected) setHighlight(this.selected.object, false);
        this.selected = item;
        if (item) setHighlight(item.object, true);
        this.hooks.onSelect?.(item ? { key: item.key, name: item.def.name } : null);
    }

    rotateSelected(step = Math.PI / 8) {
        if (!this.selected) return;
        this.selected.object.rotation.y += step;
        this._markDirty();
    }

    removeSelected() {
        if (!this.selected) return;
        this.scene.remove(this.selected.object);
        this.items = this.items.filter((it) => it !== this.selected);
        this.selected = null;
        this.hooks.onSelect?.(null);
        this._markDirty();
    }

    setFloor(key) {
        const def = this.config.catalog.floors[key];
        if (!def) return;
        this.floorKey = key;
        this.floorMat.color.set(def.color);
        this._markDirty();
    }

    setWall(key) {
        const def = this.config.catalog.walls[key];
        if (!def) return;
        this.wallKey = key;
        this.wallMat.color.set(def.color);
        this._markDirty();
    }

    /** 保存用のレイアウトを書き出す */
    getLayout() {
        return {
            floor: this.floorKey,
            wall: this.wallKey,
            items: this.items.map((it) => ({
                key: it.key,
                x: +it.object.position.x.toFixed(2),
                z: +it.object.position.z.toFixed(2),
                rot: +it.object.rotation.y.toFixed(3),
            })),
        };
    }

    // ── 模様替えモード ────────────────────────────────────────────────

    setEditMode(on) {
        this.editing = on;
        this.input?.setEnabled(!on);
        if (!on) this.select(null);

        // 模様替え中はキャラクターを隠して、家具を見やすくする
        if (this.player) this.player.group.visible = !on;

        // 模様替え中は部屋全体を見下ろす
        this.camPitch = on ? 0.88 : 0.42;
        this.camDistance = on ? 16 : 8.5;
        this.camYaw = on ? 0 : this.camYaw;
    }

    _bindEditPointer() {
        const toPointer = (event) => {
            const rect = this.canvas.getBoundingClientRect();
            this.pointer.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
            this.pointer.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
        };

        this._onPointerDown = (event) => {
            if (!this.editing) return;
            toPointer(event);
            this.raycaster.setFromCamera(this.pointer, this.camera);

            const hits = this.raycaster.intersectObjects(this.items.map((it) => it.object), true);
            if (hits.length === 0) {
                this.select(null);
                return;
            }

            // 当たったメッシュから、どの家具かをたどる
            let node = hits[0].object;
            while (node && node.userData.furnitureKey === undefined) node = node.parent;
            const item = this.items.find((it) => it.object === node);
            if (!item) return;

            this.select(item);
            this.dragging = item;
            this.canvas.setPointerCapture?.(event.pointerId);
        };

        this._onPointerMove = (event) => {
            if (!this.editing || !this.dragging) return;
            toPointer(event);
            this.raycaster.setFromCamera(this.pointer, this.camera);

            const point = new THREE.Vector3();
            if (!this.raycaster.ray.intersectPlane(this.floorPlane, point)) return;

            // 部屋からはみ出さないよう内側に収める
            const half = this.dragging.def;
            const limitX = this.room.width / 2 - Math.max(half.w, half.d) / 2 - 0.1;
            const limitZ = this.room.depth / 2 - Math.max(half.w, half.d) / 2 - 0.1;
            this.dragging.object.position.x = THREE.MathUtils.clamp(point.x, -limitX, limitX);
            this.dragging.object.position.z = THREE.MathUtils.clamp(point.z, -limitZ, limitZ);
        };

        this._onPointerUp = () => {
            if (this.dragging) {
                this.dragging = null;
                this._markDirty();
            }
        };

        this.canvas.addEventListener('pointerdown', this._onPointerDown);
        this.canvas.addEventListener('pointermove', this._onPointerMove);
        this.canvas.addEventListener('pointerup', this._onPointerUp);
        this.canvas.addEventListener('pointercancel', this._onPointerUp);
    }

    // ── 歩行・カメラ ─────────────────────────────────────────────────

    /** 壁の内側に収め、家具にぶつからないようにする */
    resolveCollisions(x, z) {
        const playerR = 0.5;
        const limitX = this.room.width / 2 - playerR - 0.2;
        const limitZ = this.room.depth / 2 - playerR - 0.2;

        for (const item of this.items) {
            if (item.def.kind === 'rug') continue;   // ラグの上は歩ける

            const dx = x - item.object.position.x;
            const dz = z - item.object.position.z;
            const min = Math.max(item.def.w, item.def.d) * 0.42 + playerR;
            const d = Math.hypot(dx, dz);
            if (d < min && d > 0.0001) {
                x = item.object.position.x + (dx / d) * min;
                z = item.object.position.z + (dz / d) * min;
            }
        }

        return {
            x: THREE.MathUtils.clamp(x, -limitX, limitX),
            z: THREE.MathUtils.clamp(z, -limitZ, limitZ),
        };
    }

    groundHeightAt() { return 0; }   // 室内の床は平ら

    _updateCamera(dt, immediate = false) {
        const look = (!this.editing && this.input) ? this.input.consumeLook() : { dx: 0, dy: 0 };
        this.camYaw -= look.dx * 0.005;
        this.camPitch = THREE.MathUtils.clamp(this.camPitch + look.dy * 0.004, 0.15, 1.2);

        if (!this.editing && this.input) {
            this.camDistance = THREE.MathUtils.clamp(this.camDistance + this.input.consumeZoom(), 4, 16);
        }

        // 模様替え中は部屋の中心を、歩行中はプレイヤーを見る
        const target = this.editing
            ? new THREE.Vector3(0, 0.8, 0)
            : new THREE.Vector3(this.player.position.x, this.player.position.y + 1.4, this.player.position.z);

        const horizontal = Math.cos(this.camPitch) * this.camDistance;
        const desired = new THREE.Vector3(
            target.x + Math.sin(this.camYaw) * horizontal,
            target.y + Math.sin(this.camPitch) * this.camDistance,
            target.z + Math.cos(this.camYaw) * horizontal,
        );
        desired.y = Math.max(desired.y, 1.2);

        const k = immediate ? 1 : 1 - Math.exp(-dt * 9);
        this.camera.position.lerp(desired, k);
        this.camera.lookAt(target);

        // カメラが天井より高い位置にあるときは天井を消す。
        // 目線の高さまで下げると天井が現れて、室内らしい囲まれ感が出る。
        if (this.ceiling) {
            this.ceiling.visible = !this.editing && this.camera.position.y < this.room.height + 0.2;
        }
    }

    _moveDirection() {
        if (this.editing) return new THREE.Vector3();

        const input = this.input.readMove();
        const dir = new THREE.Vector3();
        if (input.x === 0 && input.y === 0) return dir;

        const forward = new THREE.Vector3(-Math.sin(this.camYaw), 0, -Math.cos(this.camYaw));
        const right = new THREE.Vector3(-forward.z, 0, forward.x);
        dir.addScaledVector(forward, input.y).addScaledVector(right, input.x);
        if (dir.lengthSq() > 1) dir.normalize();
        return dir;
    }

    _resize() {
        const w = this.canvas.clientWidth || window.innerWidth;
        const h = this.canvas.clientHeight || window.innerHeight;
        this.renderer.setSize(w, h, false);
        this.camera.aspect = w / h;
        this.camera.updateProjectionMatrix();
    }

    start() {
        this.running = true;
        this.timer = new THREE.Timer();
        const loop = () => {
            if (!this.running) return;
            this.frame = requestAnimationFrame(loop);
            this.update();
        };
        this.frame = requestAnimationFrame(loop);
    }

    update() {
        this.timer.update();
        const dt = Math.min(this.timer.getDelta(), 0.05);
        this.time += dt;

        this.player.update(dt, this._moveDirection(), this.input.running, this);
        this._updateCamera(dt);

        // 選択中の家具をふわりと上下させて、選択が分かるようにする
        if (this.selected) {
            this.selected.object.position.y = Math.abs(Math.sin(this.time * 3)) * 0.08;
        }

        this.renderer.render(this.scene, this.camera);
    }

    stop() {
        this.running = false;
        if (this.frame) cancelAnimationFrame(this.frame);
    }

    dispose() {
        this.stop();
        window.removeEventListener('resize', this._onResize);
        this.canvas.removeEventListener('pointerdown', this._onPointerDown);
        this.canvas.removeEventListener('pointermove', this._onPointerMove);
        this.canvas.removeEventListener('pointerup', this._onPointerUp);
        this.canvas.removeEventListener('pointercancel', this._onPointerUp);
        this.input?.dispose();
        this.renderer?.dispose();
    }
}
