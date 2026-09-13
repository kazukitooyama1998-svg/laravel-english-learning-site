/**
 * 英語の森 – ワールド本体
 *
 * シーンの構築（空・光・島・小物・施設・プレイヤー）と、
 * 毎フレームの更新（移動・カメラ追従・施設の判定）をまとめて受け持つ。
 */
import * as THREE from 'three';
import { Island, ISLAND } from './island.js';
import { makeRng, scatterProps, createBird } from './props.js';
import { buildSpots, updateSpots } from './spots.js';
import { Player } from './player.js';
import { Input } from './input.js';

const SKY_VERT = `
    varying vec3 vWorldPosition;
    void main() {
        vec4 worldPosition = modelMatrix * vec4(position, 1.0);
        vWorldPosition = worldPosition.xyz;
        gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
    }
`;

const SKY_FRAG = `
    uniform vec3 topColor;
    uniform vec3 bottomColor;
    uniform float offset;
    uniform float exponent;
    varying vec3 vWorldPosition;
    void main() {
        float h = normalize(vWorldPosition + vec3(0.0, offset, 0.0)).y;
        gl_FragColor = vec4(mix(bottomColor, topColor, max(pow(max(h, 0.0), exponent), 0.0)), 1.0);
    }
`;

export class ForestWorld {
    /**
     * @param {HTMLCanvasElement} canvas
     * @param {object} config  { spots, player, stick }
     * @param {object} hooks   { onReady, onNearSpotChange, onEnter }
     */
    constructor(canvas, config, hooks = {}) {
        this.canvas = canvas;
        this.config = config;
        this.hooks = hooks;
        this.time = 0;
        this.running = false;
        this.nearSpot = null;

        // カメラの追従パラメータ
        this.camYaw = 0;
        this.camPitch = 0.56;   // 少し高い位置から見下ろす（島全体が見やすい角度）
        this.camDistance = 13;
    }

    async init() {
        // 文字ラベルをきれいに焼き込むため、Web フォントの読み込みを待つ
        try { await document.fonts?.ready; } catch { /* フォント API 非対応でも続行 */ }

        this._initRenderer();
        this._initScene();
        this._initLights();

        this.island = new Island(this.scene);

        const groundHeightAt = (x, z) => this.island.groundHeightAt(x, z);
        const { spots, obstacles: spotObstacles } = buildSpots(this.scene, this.config.spots, groundHeightAt);
        this.spots = spots;

        const rng = makeRng(20260913);
        const avoid = [
            ...spotObstacles.map((o) => ({ x: o.x, z: o.z, r: o.r + 4.5 })),
            { x: 0, z: 0, r: 6 },                                                     // 中央広場
            { x: 0, z: 14, r: 5 },                                                    // スポーン地点
            { x: this.island.pond.x, z: this.island.pond.z, r: this.island.pond.r + 1.5 }, // 池
        ];
        const scattered = scatterProps(this.scene, { rng, groundHeightAt, avoid });

        this.obstacles = [
            ...spotObstacles,
            ...scattered.obstacles,
            { x: this.island.pond.x, z: this.island.pond.z, r: this.island.pond.r + 0.4 },
        ];
        this.animated = scattered.animated;
        this.clouds = scattered.clouds;
        // カメラが木や建物越しにならないよう、視線判定に使う対象
        this.blockers = [...spots.map((s) => s.object), ...scattered.blockers];
        this.raycaster = new THREE.Raycaster();

        // 空を旋回する鳥
        this.birds = [];
        for (let i = 0; i < 4; i++) {
            const bird = createBird(rng);
            this.scene.add(bird.object);
            this.birds.push(bird.object);
        }

        // プレイヤー（ログイン中のユーザー）
        this.player = new Player(this.scene, this.config.player);
        const spawn = { x: 0, z: 14 };
        this.player.position.set(spawn.x, groundHeightAt(spawn.x, spawn.z), spawn.z);
        this.player.facing = Math.PI;

        this.input = new Input(this.canvas, this.config.stick);

        this._resize();
        this._onResize = () => this._resize();
        window.addEventListener('resize', this._onResize);

        // 初期カメラ位置（プレイヤーの背後）
        this._updateCamera(1, true);
        this.hooks.onReady?.();
    }

    /** スマートフォンなどの小さい画面か（描画品質の調整に使う） */
    _isSmallScreen() {
        return window.innerWidth < 820;
    }

    _initRenderer() {
        this.renderer = new THREE.WebGLRenderer({ canvas: this.canvas, antialias: true, powerPreference: 'high-performance' });
        // 端末の解像度が高すぎると描画負荷が跳ね上がるため上限を設ける
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, this._isSmallScreen() ? 1.5 : 2));
        this.renderer.shadowMap.enabled = true;
        this.renderer.shadowMap.type = THREE.PCFShadowMap;
    }

    _initScene() {
        this.scene = new THREE.Scene();
        this.scene.fog = new THREE.Fog(0xcfeaf5, 130, 340);

        this.camera = new THREE.PerspectiveCamera(52, 1, 0.1, 1000);
        this.camera.position.set(0, 12, 30);

        const sky = new THREE.Mesh(
            new THREE.SphereGeometry(420, 24, 16),
            new THREE.ShaderMaterial({
                uniforms: {
                    topColor: { value: new THREE.Color(0x3f9ede) },
                    bottomColor: { value: new THREE.Color(0xdff2fb) },
                    offset: { value: 40 },
                    exponent: { value: 0.7 },
                },
                vertexShader: SKY_VERT,
                fragmentShader: SKY_FRAG,
                side: THREE.BackSide,
                depthWrite: false,
                fog: false,
            }),
        );
        this.scene.add(sky);
    }

    _initLights() {
        this.scene.add(new THREE.AmbientLight(0xffffff, 0.55));
        this.scene.add(new THREE.HemisphereLight(0xbfe3ff, 0x7fa65c, 1.0));

        const sun = new THREE.DirectionalLight(0xfff6e2, 2.1);
        sun.position.set(46, 62, 24);
        sun.castShadow = true;
        const shadowSize = this._isSmallScreen() ? 1024 : 2048;
        sun.shadow.mapSize.set(shadowSize, shadowSize);
        sun.shadow.camera.left = -48;
        sun.shadow.camera.right = 48;
        sun.shadow.camera.top = 48;
        sun.shadow.camera.bottom = -48;
        sun.shadow.camera.near = 10;
        sun.shadow.camera.far = 180;
        sun.shadow.bias = -0.0006;
        sun.shadow.normalBias = 0.03;
        this.scene.add(sun);
        this.scene.add(sun.target);
    }

    _resize() {
        const w = this.canvas.clientWidth || window.innerWidth;
        const h = this.canvas.clientHeight || window.innerHeight;
        this.renderer.setSize(w, h, false);
        this.camera.aspect = w / h;
        this.camera.updateProjectionMatrix();
    }

    /** 障害物（木・岩・施設・池）を押しのけて、島の外へ出ないよう座標を補正する。 */
    resolveCollisions(x, z) {
        const playerR = 0.55;

        for (const o of this.obstacles) {
            const dx = x - o.x;
            const dz = z - o.z;
            const min = o.r + playerR;
            const d = Math.hypot(dx, dz);
            if (d < min && d > 0.0001) {
                x = o.x + (dx / d) * min;
                z = o.z + (dz / d) * min;
            }
        }

        const clamped = this.island.clampToWalkable(x, z, 1.4);
        return clamped || { x, z };
    }

    groundHeightAt(x, z) { return this.island.groundHeightAt(x, z); }

    _updateCamera(dt, immediate = false) {
        const look = this.input ? this.input.consumeLook() : { dx: 0, dy: 0 };
        this.camYaw -= look.dx * 0.005;
        this.camPitch = THREE.MathUtils.clamp(this.camPitch + look.dy * 0.004, 0.12, 1.15);

        if (this.input) {
            this.camDistance = THREE.MathUtils.clamp(this.camDistance + this.input.consumeZoom(), 6, 22);
        }

        const target = new THREE.Vector3(
            this.player.position.x,
            this.player.position.y + 1.5,
            this.player.position.z,
        );
        const horizontal = Math.cos(this.camPitch) * this.camDistance;
        const desired = new THREE.Vector3(
            target.x + Math.sin(this.camYaw) * horizontal,
            target.y + Math.sin(this.camPitch) * this.camDistance,
            target.z + Math.cos(this.camYaw) * horizontal,
        );

        // プレイヤーとの間に木や建物があるときはカメラを手前に寄せる
        const toCamera = desired.clone().sub(target);
        const wanted = toCamera.length();
        toCamera.normalize();
        this.raycaster.set(target, toCamera);
        this.raycaster.far = wanted;
        const blocked = this.raycaster.intersectObjects(this.blockers, true);
        if (blocked.length > 0) {
            desired.copy(target).addScaledVector(toCamera, Math.max(3.2, blocked[0].distance - 0.8));
        }

        // 地面や海面にめり込まないよう下限を設ける
        const minY = Math.max(this.groundHeightAt(desired.x, desired.z), ISLAND.waterY) + 1.6;
        desired.y = Math.max(desired.y, minY);

        const k = immediate ? 1 : 1 - Math.exp(-dt * 9);
        this.camera.position.lerp(desired, k);
        this.camera.lookAt(target);
    }

    /** カメラの向きを基準に、入力を進行方向ベクトルへ変換する。 */
    _moveDirection() {
        const input = this.input.readMove();
        const dir = new THREE.Vector3();
        if (input.x === 0 && input.y === 0) return dir;

        // カメラが向いている水平方向
        const forward = new THREE.Vector3(-Math.sin(this.camYaw), 0, -Math.cos(this.camYaw));
        const right = new THREE.Vector3(-forward.z, 0, forward.x);

        dir.addScaledVector(forward, input.y).addScaledVector(right, input.x);
        if (dir.lengthSq() > 1) dir.normalize();
        return dir;
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
        this.island.update(this.time);

        for (const obj of this.animated) obj.userData.animate?.(this.time);
        for (const bird of this.birds) bird.userData.animate?.(this.time);

        // 雲はゆっくり流れ、遠ざかったら反対側へ戻す
        for (const cloud of this.clouds) {
            cloud.position.x += dt * 0.9;
            if (cloud.position.x > 110) cloud.position.x = -110;
        }

        const active = updateSpots(this.spots, this.player.position, this.time, this.camera.position);
        if (active !== this.nearSpot) {
            this.nearSpot = active;
            this.hooks.onNearSpotChange?.(active);
        }

        if (this.input.consumeInteract() && this.nearSpot) {
            this.hooks.onEnter?.(this.nearSpot);
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
        this.input?.dispose();
        this.renderer?.dispose();
    }
}
