/**
 * 英語の森 – ワールド本体
 *
 * シーンの構築（空・光・島・小物・施設・プレイヤー）と、
 * 毎フレームの更新（移動・カメラ追従・施設の判定）をまとめて受け持つ。
 */
import * as THREE from 'three';
import { Island, ISLAND } from './island.js';
import { makeRng, scatterProps, createBird, createGrassField, updateWind } from './props.js';
import { buildSpots, updateSpots } from './spots.js';
import { Player } from './player.js';
import { Input } from './input.js';

// 太陽の方向（光・空・海の輝きで共有する）
const SUN_DIRECTION = new THREE.Vector3(0.42, 0.86, 0.32).normalize();

const SKY_VERT = `
    varying vec3 vWorldPosition;
    void main() {
        vec4 worldPosition = modelMatrix * vec4(position, 1.0);
        vWorldPosition = worldPosition.xyz;
        gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
    }
`;

// 天頂 → 空色 → 地平のかすみ、に太陽まわりのにじみを足した空
const SKY_FRAG = `
    uniform vec3 zenithColor;
    uniform vec3 skyColor;
    uniform vec3 horizonColor;
    uniform vec3 sunDirection;
    uniform vec3 sunColor;
    varying vec3 vWorldPosition;

    void main() {
        vec3 dir = normalize(vWorldPosition);
        float h = clamp(dir.y, -1.0, 1.0);

        // 地平線付近をかすませ、上に行くほど濃い青にする
        vec3 color = mix(horizonColor, skyColor, smoothstep(-0.02, 0.28, h));
        color = mix(color, zenithColor, smoothstep(0.25, 0.85, h));

        // 太陽まわりのにじみと、小さな太陽本体
        float sun = max(dot(dir, normalize(sunDirection)), 0.0);
        color += sunColor * pow(sun, 8.0) * 0.35;
        color += sunColor * pow(sun, 220.0) * 1.4;

        gl_FragColor = vec4(color, 1.0);
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

        const quality = this._isSmallScreen() ? 'low' : 'high';
        const spotConfigs = this.config.spots ?? [];

        // 施設と広場の下は地面を平らにならし、広場から各施設へは土の小道を通す
        this.island = new Island(this.scene, {
            quality,
            flattenZones: [
                { x: 0, z: 0, r: 5.5, fade: 5 },                    // 中央広場
                { x: 0, z: 30, r: 3.5, fade: 5 },                   // スポーン地点（家の前）
                ...spotConfigs.map((s) => ({ x: s.x, z: s.z, r: 4.2, fade: 6 })),
            ],
            paths: spotConfigs.map((s) => ({ ax: 0, az: 0, bx: s.x, bz: s.z })),
        });

        const groundHeightAt = (x, z) => this.island.groundHeightAt(x, z);
        const { spots, obstacles: spotObstacles } = buildSpots(this.scene, spotConfigs, groundHeightAt);
        this.spots = spots;

        const rng = makeRng(20260913);
        const avoid = [
            ...spotObstacles.map((o) => ({ x: o.x, z: o.z, r: o.r + 4.5 })),
            { x: 0, z: 0, r: 6 },                                                     // 中央広場
            { x: 0, z: 30, r: 5 },                                                    // スポーン地点
            { x: this.island.pond.x, z: this.island.pond.z, r: this.island.pond.r + 1.5 }, // 池
        ];
        const scattered = scatterProps(this.scene, { rng, groundHeightAt, avoid, quality });

        // 芝生一面に草を生やす（インスタンシングなので描画は 1 回）
        createGrassField(this.scene, {
            rng,
            island: this.island,
            count: quality === 'low' ? 3200 : 8200,
            avoid: [
                { x: this.island.pond.x, z: this.island.pond.z, r: this.island.pond.r + 1.5 },
                ...spotObstacles.map((o) => ({ x: o.x, z: o.z, r: o.r + 1.5 })),
            ],
        });

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
        const spawn = { x: 0, z: 30 };
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

        // 映画的なトーンマッピング。ハイライトが白飛びせず、
        // 陰から日なたまでの階調が出るので立体感が大きく変わる。
        this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
        this.renderer.toneMappingExposure = 0.96;
    }

    _initScene() {
        this.scene = new THREE.Scene();
        // 遠景をかすませて空気感（奥行き）を出す
        this.scene.fog = new THREE.Fog(0xbfdcec, 110, 400);

        this.camera = new THREE.PerspectiveCamera(50, 1, 0.1, 1200);
        this.camera.position.set(0, 12, 30);

        const sky = new THREE.Mesh(
            new THREE.SphereGeometry(500, 32, 20),
            new THREE.ShaderMaterial({
                uniforms: {
                    zenithColor:  { value: new THREE.Color(0x2f7fd4) },
                    skyColor:     { value: new THREE.Color(0x63b3e8) },
                    horizonColor: { value: new THREE.Color(0xdceef6) },
                    sunDirection: { value: SUN_DIRECTION.clone() },
                    sunColor:     { value: new THREE.Color(0xfff1cf) },
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
        // 空と地面からの照り返し（環境光）。影の中を真っ黒にしないための土台。
        this.scene.add(new THREE.HemisphereLight(0xaed2f0, 0x74924f, 1.15));
        this.scene.add(new THREE.AmbientLight(0xffffff, 0.22));

        // 主光源＝太陽。強めに当てて、影とのコントラストで立体感を作る。
        const sun = new THREE.DirectionalLight(0xfff0cd, 2.2);
        sun.position.copy(SUN_DIRECTION).multiplyScalar(90);
        sun.castShadow = true;
        const shadowSize = this._isSmallScreen() ? 1024 : 2048;
        sun.shadow.mapSize.set(shadowSize, shadowSize);
        sun.shadow.radius = 2.5;          // 影のふちをやわらかく
        sun.shadow.camera.left = -46;
        sun.shadow.camera.right = 46;
        sun.shadow.camera.top = 46;
        sun.shadow.camera.bottom = -46;
        sun.shadow.camera.near = 10;
        sun.shadow.camera.far = 200;
        sun.shadow.bias = -0.0006;
        sun.shadow.normalBias = 0.03;
        this.scene.add(sun);
        this.scene.add(sun.target);

        // 反対側からの弱い補助光。影側が潰れず、物の丸みが読み取れるようになる。
        const fill = new THREE.DirectionalLight(0xb6d2f5, 0.55);
        fill.position.set(-SUN_DIRECTION.x * 70, 34, -SUN_DIRECTION.z * 70);
        this.scene.add(fill);
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
        updateWind(this.time);   // 葉と草を風で揺らす

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
