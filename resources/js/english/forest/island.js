/**
 * 英語の森 – 島の地形
 *
 * 平らな板を積んだ「ブロック」ではなく、ノイズで起伏をつけた一枚の地形メッシュ。
 * 芝生・土・砂浜・浅瀬の色は頂点カラーで混ぜているので、境目がなめらかにつながる。
 * 施設の下だけは自動で平らにならし、広場から各施設へは土の小道が伸びる。
 */
import * as THREE from 'three';

export const ISLAND = {
    grassRadius: 40,    // 芝生の基準半径
    sandWidth:   7.5,   // 浜辺の幅
    grassTop:    1.6,   // 芝生の基準高さ
    sandTop:     0.12,  // 波打ち際の高さ
    slope:       4.2,   // 芝生から浜辺へ降りる斜面の幅
    waterY:     -0.28,  // 海面
    hillAmp:     1.35,  // 起伏の大きさ
    seaFloor:   -6.0,   // 海底
    terrainMax:  66,    // 地形メッシュの外周（海中まで）
};

// ── ノイズ（起伏と色ムラのもと） ──────────────────────────────────────
function hash2(x, z) {
    const s = Math.sin(x * 127.1 + z * 311.7) * 43758.5453123;
    return s - Math.floor(s);
}

function smootherstep(t) {
    return t * t * t * (t * (t * 6 - 15) + 10);
}

/** 格子状の値ノイズ（0〜1） */
export function valueNoise(x, z) {
    const xi = Math.floor(x);
    const zi = Math.floor(z);
    const u = smootherstep(x - xi);
    const v = smootherstep(z - zi);

    const a = hash2(xi, zi);
    const b = hash2(xi + 1, zi);
    const c = hash2(xi, zi + 1);
    const d = hash2(xi + 1, zi + 1);

    return (a * (1 - u) + b * u) * (1 - v) + (c * (1 - u) + d * u) * v;
}

/**
 * 複数の周波数を重ねたノイズ。地形らしいゆらぎになる。
 * 振幅の合計で割って 0〜1 に正規化してあるので、0.5 を中心に上下する。
 */
export function fbm(x, z, octaves = 3) {
    let sum = 0;
    let amp = 0.5;
    let norm = 0;
    let freq = 1;
    for (let i = 0; i < octaves; i++) {
        sum += valueNoise(x * freq, z * freq) * amp;
        norm += amp;
        freq *= 2.07;
        amp *= 0.5;
    }
    return sum / norm;
}

const clamp01 = (t) => (t < 0 ? 0 : t > 1 ? 1 : t);
const lerp = (a, b, t) => a + (b - a) * t;

/** 島（芝生）の輪郭半径。theta はワールド上の角度 atan2(z, x)。 */
export function grassRadiusAt(theta) {
    return ISLAND.grassRadius * (
        1
        + 0.075 * Math.sin(3 * theta + 0.6)
        + 0.045 * Math.sin(5 * theta + 2.1)
        + 0.028 * Math.sin(7 * theta + 4.2)
    );
}

/** 砂浜まで含めた島の外周半径。 */
export function sandRadiusAt(theta) {
    return grassRadiusAt(theta) + ISLAND.sandWidth * (1 + 0.22 * Math.sin(4 * theta + 1.1));
}

/** 点と線分の距離（小道の描画に使う） */
function distanceToSegment(px, pz, ax, az, bx, bz) {
    const vx = bx - ax;
    const vz = bz - az;
    const wx = px - ax;
    const wz = pz - az;
    const len2 = vx * vx + vz * vz;
    const t = len2 > 0 ? clamp01((wx * vx + wz * vz) / len2) : 0;
    return Math.hypot(px - (ax + vx * t), pz - (az + vz * t));
}

export class Island {
    /**
     * @param {THREE.Scene} scene
     * @param {object} options
     *   flattenZones: [{ x, z, r, fade }] 施設の下などを平らにする範囲
     *   paths:        [{ ax, az, bx, bz }] 土の小道（広場 → 各施設）
     *   quality:      'high' | 'low'  メッシュの細かさ
     */
    constructor(scene, { flattenZones = [], paths = [], quality = 'high' } = {}) {
        this.scene = scene;
        this.flattenZones = flattenZones;
        this.paths = paths;
        this.quality = quality;
        this.group = new THREE.Group();
        scene.add(this.group);

        // 島の東側の小さな池（地形をくぼませて水面を張る）
        this.pond = { x: 14, z: -6, r: 4.6 };
        this.pondRimY = this._plateauHeightAt(this.pond.x, this.pond.z);
        this.pondWaterY = this.pondRimY - 0.34;

        this._buildTerrain();
        this._buildOcean();
        this._buildShoreline();
        this._buildPond();
    }

    // ── 高さの計算 ───────────────────────────────────────────────────

    /** なだらかな丘だけを乗せた高さ（平坦化も池も含まない素の地形） */
    _hillHeightAt(x, z) {
        const hills =
            (fbm(x * 0.035 + 11.3, z * 0.035 - 4.7, 3) - 0.42) * 2.4 +
            (fbm(x * 0.011 - 2.1, z * 0.011 + 8.9, 2) - 0.42) * 3.0;

        return ISLAND.grassTop + hills * ISLAND.hillAmp;
    }

    /** 起伏を乗せた台地の高さ（池のくぼみ・浜辺への落ち込みは含まない） */
    _plateauHeightAt(x, z) {
        let h = this._hillHeightAt(x, z);

        // 施設や広場の下は平らにならす。基準はその場所そのものの高さなので、
        // 建物が台座に乗ったようにならず、まわりの地形になじむ。
        for (const zone of this.flattenZones) {
            const d = Math.hypot(x - zone.x, z - zone.z);
            const fade = zone.fade ?? 4;
            if (d >= zone.r + fade) continue;

            const w = 1 - smootherstep(clamp01((d - zone.r) / fade));
            const target = zone.y ?? this._hillHeightAt(zone.x, zone.z);
            h = lerp(h, target, w);
        }

        return h;
    }

    /** 実際の地面の高さ。台地 → 浜辺 → 海中までなめらかにつながる。 */
    groundHeightAt(x, z) {
        const theta = Math.atan2(z, x);
        const r = Math.hypot(x, z);
        const rGrass = grassRadiusAt(theta);
        const rSand = sandRadiusAt(theta);

        let h = this._plateauHeightAt(x, z);

        // 池のくぼみ
        const pd = Math.hypot(x - this.pond.x, z - this.pond.z);
        if (pd < this.pond.r + 1.6) {
            const w = 1 - smootherstep(clamp01((pd - this.pond.r * 0.55) / (this.pond.r * 0.45 + 1.6)));
            h = lerp(h, this.pondRimY - 1.15, w);
        }

        // 芝生のふちから浜辺へ降りる
        if (r > rGrass - ISLAND.slope) {
            const t = smootherstep(clamp01((r - (rGrass - ISLAND.slope)) / ISLAND.slope));
            // 波打ち際の砂はわずかに起伏を残す
            const beachY = ISLAND.sandTop + (fbm(x * 0.12, z * 0.12, 2) - 0.5) * 0.35;
            h = lerp(h, beachY, t);
        }

        // 浜辺の外は海中へ落ちていく
        if (r > rSand) {
            const t = smootherstep(clamp01((r - rSand) / 12));
            h = lerp(ISLAND.sandTop, ISLAND.seaFloor, t);
        }

        return h;
    }

    /** 海に落ちないよう、歩ける範囲（砂浜の内側）へ座標を丸める。 */
    clampToWalkable(x, z, margin = 1.2) {
        const theta = Math.atan2(z, x);
        const limit = sandRadiusAt(theta) - margin;
        const r = Math.hypot(x, z);
        if (r <= limit) return null;
        const k = limit / r;
        return { x: x * k, z: z * k };
    }

    // ── 地形メッシュ ─────────────────────────────────────────────────

    /**
     * その地点が小道にかかっている度合い（0〜1）。
     * 地面の色付けと、草を生やさない判定の両方で使う。
     * 広場の中心付近では細らせて、7 本の道が合流して広い土面にならないようにする。
     */
    pathWeightAt(x, z) {
        let weight = 0;
        const fromCenter = Math.hypot(x, z);

        for (const p of this.paths) {
            const d = distanceToSegment(x, z, p.ax, p.az, p.bx, p.bz);
            let w = 1 - clamp01((d - 0.85) / 0.9);
            if (fromCenter < 6.5) w *= clamp01((fromCenter - 2.2) / 4.3);
            if (w > weight) weight = w;
        }

        return weight;
    }

    /** 地面の傾き（0=平ら, 1=絶壁）。崖の色付けに使う。 */
    _steepnessAt(x, z) {
        const d = 0.9;
        const hx = this.groundHeightAt(x + d, z) - this.groundHeightAt(x - d, z);
        const hz = this.groundHeightAt(x, z + d) - this.groundHeightAt(x, z - d);
        return clamp01(Math.hypot(hx, hz) / (2 * d) * 0.85);
    }

    /** 地面の色。芝生・土・砂・小道を距離と傾きから混ぜる。 */
    _terrainColorAt(x, z, r, theta, target) {
        const rGrass = grassRadiusAt(theta);
        const rSand = sandRadiusAt(theta);

        // 芝生：ノイズで明暗をつけ、のっぺりさせない（振れ幅は抑えめに）
        const n = fbm(x * 0.09 + 3.2, z * 0.09 - 1.7, 3);
        const n2 = valueNoise(x * 0.33 - 5.5, z * 0.33 + 2.2);
        target.setRGB(0.24, 0.40, 0.13);
        target.lerp(GRASS_LIGHT, clamp01(n * 0.7 + n2 * 0.2));

        // 急斜面は土が出る
        const steep = this._steepnessAt(x, z);
        if (steep > 0.18) {
            target.lerp(DIRT, clamp01((steep - 0.18) / 0.5) * 0.85);
        }

        // 広場の焚き火まわりだけ踏み固められた土にする
        const fromCenter = Math.hypot(x, z);
        const plaza = 1 - clamp01((fromCenter - 2.4) / 1.5);

        // 広場から施設へ伸びる細い小道
        const pathW = Math.max(plaza, this.pathWeightAt(x, z));
        if (pathW > 0) {
            const edge = 0.78 + valueNoise(x * 0.8, z * 0.8) * 0.44; // ふちをギザつかせる
            target.lerp(PATH, clamp01(pathW * edge) * 0.88);
        }

        // 浜辺の砂へ
        const beachT = smootherstep(clamp01((r - (rGrass - ISLAND.slope * 0.75)) / (ISLAND.slope * 0.9)));
        if (beachT > 0) {
            const sandShade = SAND.clone().lerp(SAND_DARK, valueNoise(x * 0.2, z * 0.2));
            target.lerp(sandShade, beachT);
        }

        // 波打ち際から先は濡れた砂 → 海底
        if (r > rSand - 1.2) {
            const wetT = smootherstep(clamp01((r - (rSand - 1.2)) / 2.5));
            target.lerp(SAND_WET, wetT);
            if (r > rSand + 1) {
                target.lerp(SEA_FLOOR, smootherstep(clamp01((r - rSand - 1) / 8)));
            }
        }

        return target;
    }

    _buildTerrain() {
        const rings = this.quality === 'low' ? 54 : 78;
        const segs = this.quality === 'low' ? 120 : 172;
        const maxR = ISLAND.terrainMax;

        const vertexCount = (rings + 1) * (segs + 1);
        const positions = new Float32Array(vertexCount * 3);
        const colors = new Float32Array(vertexCount * 3);
        const indices = [];
        const color = new THREE.Color();

        for (let i = 0; i <= rings; i++) {
            // 外周（浜辺・波打ち際）ほど密になるよう半径を配分する
            const t = i / rings;
            const r = Math.pow(t, 1.25) * maxR;

            for (let j = 0; j <= segs; j++) {
                const theta = (j / segs) * Math.PI * 2;
                const x = Math.cos(theta) * r;
                const z = Math.sin(theta) * r;
                const y = this.groundHeightAt(x, z);

                const idx = (i * (segs + 1) + j) * 3;
                positions[idx] = x;
                positions[idx + 1] = y;
                positions[idx + 2] = z;

                this._terrainColorAt(x, z, r, theta, color);
                colors[idx] = color.r;
                colors[idx + 1] = color.g;
                colors[idx + 2] = color.b;
            }
        }

        // 面の向き（法線）が上を向くよう、反時計回りに並べる
        for (let i = 0; i < rings; i++) {
            for (let j = 0; j < segs; j++) {
                const a = i * (segs + 1) + j;
                const b = a + segs + 1;
                indices.push(a, a + 1, b);
                indices.push(b, a + 1, b + 1);
            }
        }

        const geo = new THREE.BufferGeometry();
        geo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        geo.setAttribute('color', new THREE.BufferAttribute(colors, 3));
        geo.setIndex(indices);
        geo.computeVertexNormals();

        const mesh = new THREE.Mesh(geo, new THREE.MeshStandardMaterial({
            vertexColors: true,
            roughness: 0.96,
            metalness: 0,
        }));
        mesh.receiveShadow = true;
        mesh.castShadow = false;
        this.terrain = mesh;
        this.group.add(mesh);
    }

    // ── 海 ───────────────────────────────────────────────────────────

    _buildOcean() {
        const geo = new THREE.PlaneGeometry(900, 900, 56, 56);
        geo.rotateX(-Math.PI / 2);
        this.waterGeo = geo;
        this.waterBase = Float32Array.from(geo.attributes.position.array);

        // 頂点ごとの「波の立ちやすさ」。岸辺では 0、沖に向かって 1 になる。
        // 実際の海と同じで、浅瀬に近づくほどうねりが収まり、
        // 波の山が砂浜を越えて浸水することがなくなる。
        const count = geo.attributes.position.count;
        this.waveDamp = new Float32Array(count);
        for (let i = 0; i < count; i++) {
            const x = this.waterBase[i * 3];
            const z = this.waterBase[i * 3 + 2];
            const r = Math.hypot(x, z);
            const shore = sandRadiusAt(Math.atan2(z, x));
            this.waveDamp[i] = smootherstep(clamp01((r - (shore + 1.5)) / 18));
        }

        // 金属質を少し混ぜると太陽のきらめきが乗り、水らしい艶が出る
        const mat = new THREE.MeshStandardMaterial({
            color: 0x2b7fa8,
            roughness: 0.16,
            metalness: 0.35,
            transparent: true,
            opacity: 0.9,
        });

        this.water = new THREE.Mesh(geo, mat);
        this.water.position.y = ISLAND.waterY;
        this.water.receiveShadow = false;
        this.group.add(this.water);
    }

    /** 波打ち際の白い泡（2重にして寄せては返す動きを出す） */
    _buildShoreline() {
        const segments = 190;

        // 地形が海面と交わる半径＝実際の汀線。そこに泡を重ねる。
        const waterlineOffset = (theta) => {
            const rSand = sandRadiusAt(theta);
            for (let d = 0; d < 12; d += 0.25) {
                if (this.groundHeightAt(Math.cos(theta) * (rSand + d), Math.sin(theta) * (rSand + d)) <= ISLAND.waterY) {
                    return d;
                }
            }
            return 2.2;
        };

        this.foamRings = [0, 1].map((k) => {
            const positions = [];
            const inner = -0.7 + k * 0.4;   // 汀線からの内外の幅（メートル）
            const outer = 1.6 + k * 1.1;

            for (let i = 0; i < segments; i++) {
                const t0 = (i / segments) * Math.PI * 2;
                const t1 = ((i + 1) / segments) * Math.PI * 2;
                const r0 = sandRadiusAt(t0) + waterlineOffset(t0);
                const r1 = sandRadiusAt(t1) + waterlineOffset(t1);

                const ax = Math.cos(t0) * (r0 + inner), az = Math.sin(t0) * (r0 + inner);
                const bx = Math.cos(t1) * (r1 + inner), bz = Math.sin(t1) * (r1 + inner);
                const cx = Math.cos(t0) * (r0 + outer), cz = Math.sin(t0) * (r0 + outer);
                const dx = Math.cos(t1) * (r1 + outer), dz = Math.sin(t1) * (r1 + outer);

                positions.push(ax, 0, az, cx, 0, cz, bx, 0, bz);
                positions.push(bx, 0, bz, cx, 0, cz, dx, 0, dz);
            }

            const geo = new THREE.BufferGeometry();
            geo.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
            geo.computeVertexNormals();

            const mesh = new THREE.Mesh(geo, new THREE.MeshBasicMaterial({
                color: 0xffffff,
                transparent: true,
                opacity: 0.5,
                depthWrite: false,
            }));
            mesh.position.y = ISLAND.waterY + 0.06 + k * 0.03;
            mesh.renderOrder = 2;
            this.group.add(mesh);
            return mesh;
        });
    }

    _buildPond() {
        const surface = new THREE.Mesh(
            new THREE.CircleGeometry(this.pond.r + 0.35, 40),
            new THREE.MeshStandardMaterial({
                color: 0x3f9fbe,
                roughness: 0.12,
                metalness: 0.32,
                transparent: true,
                opacity: 0.88,
            }),
        );
        surface.geometry.rotateX(-Math.PI / 2);
        surface.position.set(this.pond.x, this.pondWaterY, this.pond.z);
        this.pondSurface = surface;
        this.group.add(surface);

        // 水辺の石
        const stoneMat = new THREE.MeshStandardMaterial({ color: 0x8d8b83, roughness: 0.9, metalness: 0 });
        for (let i = 0; i < 9; i++) {
            const a = (i / 9) * Math.PI * 2 + 0.4;
            const rr = this.pond.r + 0.55 + valueNoise(i * 3.1, 0.5) * 0.5;
            const x = this.pond.x + Math.cos(a) * rr;
            const z = this.pond.z + Math.sin(a) * rr;

            const geo = new THREE.IcosahedronGeometry(0.34 + valueNoise(i, 2.2) * 0.28, 1);
            deformGeometry(geo, 0.28, i * 7.7);
            const stone = new THREE.Mesh(geo, stoneMat);
            stone.position.set(x, this.groundHeightAt(x, z) + 0.05, z);
            stone.rotation.set(i * 0.7, a, i * 0.4);
            stone.castShadow = true;
            stone.receiveShadow = true;
            this.group.add(stone);
        }
    }

    update(time) {
        // 海面のうねり（負荷を抑えるため 2 フレームに 1 回）
        this._frame = (this._frame || 0) + 1;
        if (this._frame % 2 === 0) this._updateWaves(time);

        // 寄せては返す波打ち際
        for (let k = 0; k < this.foamRings.length; k++) {
            const ring = this.foamRings[k];
            const phase = time * 0.55 + k * 1.9;
            const s = 1 + Math.sin(phase) * 0.012;
            ring.scale.set(s, 1, s);
            ring.material.opacity = 0.28 + (Math.sin(phase) * 0.5 + 0.5) * 0.45;
        }

        this.pondSurface.position.y = this.pondWaterY + Math.sin(time * 1.3) * 0.025;
    }

    _updateWaves(time) {
        const pos = this.waterGeo.attributes.position;
        const base = this.waterBase;
        const damp = this.waveDamp;

        // 波の山が砂浜の高さを越えないための上限（メッシュ内のローカル座標）。
        // ワールド高さ = ISLAND.waterY + この値 なので、砂浜より必ず低く収まる。
        const ceiling = ISLAND.sandTop - 0.06 - ISLAND.waterY;

        for (let i = 0; i < pos.count; i++) {
            const x = base[i * 3];
            const z = base[i * 3 + 2];

            const wave =
                Math.sin(x * 0.05 + time * 0.85) * 0.34 +
                Math.sin(z * 0.075 - time * 0.63) * 0.27 +
                Math.sin((x + z) * 0.13 + time * 1.4) * 0.1;

            pos.array[i * 3 + 1] = Math.min(wave * damp[i], ceiling);
        }

        pos.needsUpdate = true;
        this.waterGeo.computeVertexNormals();
    }
}

// 地形の色パレット（頂点カラーで混ぜる）
const GRASS_LIGHT = new THREE.Color(0.36, 0.53, 0.20);
const DIRT        = new THREE.Color(0.47, 0.36, 0.23);
const PATH        = new THREE.Color(0.50, 0.40, 0.26);
const SAND        = new THREE.Color(0.88, 0.80, 0.59);
const SAND_DARK   = new THREE.Color(0.78, 0.69, 0.48);
const SAND_WET    = new THREE.Color(0.60, 0.56, 0.42);
const SEA_FLOOR   = new THREE.Color(0.20, 0.29, 0.28);

/**
 * ジオメトリの頂点をノイズで押し出して、完全な球や正多面体に見えないようにする。
 * 木の葉・岩・雲など、自然物の「作り物っぽさ」を消すのに使う。
 */
export function deformGeometry(geometry, amount = 0.25, seed = 0) {
    const pos = geometry.attributes.position;
    const v = new THREE.Vector3();

    for (let i = 0; i < pos.count; i++) {
        v.fromBufferAttribute(pos, i);
        const n = fbm(v.x * 1.15 + seed, v.z * 1.15 - v.y * 0.8 + seed, 2) - 0.5;
        const scale = 1 + n * amount * 2;
        v.multiplyScalar(scale);
        pos.setXYZ(i, v.x, v.y, v.z);
    }

    pos.needsUpdate = true;
    geometry.computeVertexNormals();
    return geometry;
}
