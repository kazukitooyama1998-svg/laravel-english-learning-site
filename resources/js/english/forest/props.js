/**
 * 英語の森 – 島に置く小物（木・岩・花・雲など）
 *
 * すべて three.js のプリミティブを組み合わせた自作のローポリ形状。
 * 乱数はシード付きなので、誰がいつ訪れても同じ景色になる。
 */
import * as THREE from 'three';
import { grassRadiusAt } from './island.js';

/** mulberry32 – 小さなシード付き乱数 */
export function makeRng(seed = 20260913) {
    let a = seed >>> 0;
    return function rng() {
        a |= 0; a = (a + 0x6D2B79F5) | 0;
        let t = Math.imul(a ^ (a >>> 15), 1 | a);
        t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
        return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
    };
}

const mat = (color, flat = true) => new THREE.MeshLambertMaterial({ color, flatShading: flat });

const COLORS = {
    trunk:     0x9a7350,
    trunkDark: 0x7f5c3e,
    leaf:      [0x6e9c4f, 0x5c8a45, 0x7fae5a, 0x4f7c3e],
    pine:      [0x4a7350, 0x3f6445, 0x55805a],
    rock:      [0x9aa0a6, 0x868d94, 0xa8aeb4],
    flower:    [0xe8756b, 0xf2b44c, 0xe4e2dd, 0xd48fc0, 0xf0d264],
};

const pick = (arr, rng) => arr[Math.floor(rng() * arr.length)];

/** 広葉樹（もこもこした丸い木） */
export function createBroadleafTree(rng, scale = 1) {
    const g = new THREE.Group();

    const trunk = new THREE.Mesh(
        new THREE.CylinderGeometry(0.22, 0.36, 2.2, 7),
        mat(COLORS.trunk, false),
    );
    trunk.position.y = 1.1;
    g.add(trunk);

    const leafMat = mat(pick(COLORS.leaf, rng));
    const blobs = [
        { r: 1.45, y: 2.9, x: 0,     z: 0 },
        { r: 1.05, y: 2.6, x: -1.0,  z: 0.5 },
        { r: 0.95, y: 2.7, x: 1.0,   z: -0.4 },
        { r: 0.85, y: 3.7, x: 0.25,  z: 0.35 },
    ];
    for (const b of blobs) {
        const leaf = new THREE.Mesh(new THREE.IcosahedronGeometry(b.r, 1), leafMat);
        leaf.position.set(b.x, b.y, b.z);
        leaf.castShadow = true;
        g.add(leaf);
    }

    g.scale.setScalar(scale);
    return { object: g, radius: 1.0 * scale };
}

/** 針葉樹（三角の重なった木） */
export function createPineTree(rng, scale = 1) {
    const g = new THREE.Group();

    const trunk = new THREE.Mesh(new THREE.CylinderGeometry(0.2, 0.3, 1.4, 6), mat(COLORS.trunkDark, false));
    trunk.position.y = 0.7;
    g.add(trunk);

    const pineMat = mat(pick(COLORS.pine, rng));
    const tiers = [
        { r: 1.5, h: 1.9, y: 1.8 },
        { r: 1.15, h: 1.7, y: 2.8 },
        { r: 0.8, h: 1.5, y: 3.7 },
    ];
    for (const t of tiers) {
        const cone = new THREE.Mesh(new THREE.ConeGeometry(t.r, t.h, 8), pineMat);
        cone.position.y = t.y;
        cone.castShadow = true;
        g.add(cone);
    }

    g.scale.setScalar(scale);
    return { object: g, radius: 0.9 * scale };
}

/** ヤシの木（浜辺用） */
export function createPalmTree(rng, scale = 1) {
    const g = new THREE.Group();

    // 反った幹を短い円柱の積み重ねで作る
    let x = 0, y = 0, lean = 0;
    for (let i = 0; i < 6; i++) {
        const seg = new THREE.Mesh(
            new THREE.CylinderGeometry(0.18 - i * 0.015, 0.24 - i * 0.015, 0.8, 6),
            mat(COLORS.trunk, false),
        );
        lean += 0.075;
        seg.position.set(x, y + 0.4, 0);
        seg.rotation.z = -lean;
        g.add(seg);
        x += Math.sin(lean) * 0.8;
        y += Math.cos(lean) * 0.8;
    }

    const leafMat = mat(0x62a35a);
    for (let i = 0; i < 7; i++) {
        const leaf = new THREE.Mesh(new THREE.SphereGeometry(1.25, 7, 5), leafMat);
        leaf.scale.set(1, 0.16, 0.38);
        const a = (i / 7) * Math.PI * 2 + rng();
        leaf.position.set(x + Math.cos(a) * 1.1, y + 0.15, Math.sin(a) * 1.1);
        leaf.rotation.y = -a;
        leaf.rotation.z = -0.32;
        leaf.castShadow = true;
        g.add(leaf);
    }

    const coconutMat = mat(0x6b4b2f, false);
    for (let i = 0; i < 2; i++) {
        const nut = new THREE.Mesh(new THREE.SphereGeometry(0.2, 7, 6), coconutMat);
        nut.position.set(x + (i ? 0.3 : -0.25), y - 0.2, i ? 0.2 : -0.2);
        g.add(nut);
    }

    g.scale.setScalar(scale);
    return { object: g, radius: 0.8 * scale };
}

/** 岩 */
export function createRock(rng, scale = 1) {
    const rock = new THREE.Mesh(new THREE.IcosahedronGeometry(0.9, 0), mat(pick(COLORS.rock, rng)));
    rock.scale.set(1 + rng() * 0.5, 0.7 + rng() * 0.5, 1 + rng() * 0.5);
    rock.rotation.set(rng(), rng() * Math.PI, rng());
    rock.castShadow = true;
    rock.receiveShadow = true;

    const g = new THREE.Group();
    g.add(rock);
    rock.position.y = 0.45 * scale;
    g.scale.setScalar(scale);
    return { object: g, radius: 1.0 * scale };
}

/** 茂み */
export function createBush(rng, scale = 1) {
    const g = new THREE.Group();
    const bushMat = mat(pick(COLORS.leaf, rng));
    for (let i = 0; i < 3; i++) {
        const b = new THREE.Mesh(new THREE.IcosahedronGeometry(0.55 + rng() * 0.25, 1), bushMat);
        b.position.set((rng() - 0.5) * 0.9, 0.4 + rng() * 0.2, (rng() - 0.5) * 0.9);
        b.castShadow = true;
        g.add(b);
    }
    g.scale.setScalar(scale);
    return { object: g, radius: 0.7 * scale };
}

/** 花（茎＋5枚の花びら） */
export function createFlower(rng) {
    const g = new THREE.Group();
    const petalColor = pick(COLORS.flower, rng);

    const stem = new THREE.Mesh(new THREE.CylinderGeometry(0.035, 0.045, 0.45, 4), mat(0x5f8a48, false));
    stem.position.y = 0.22;
    g.add(stem);

    const petalMat = mat(petalColor, false);
    for (let i = 0; i < 5; i++) {
        const a = (i / 5) * Math.PI * 2;
        const petal = new THREE.Mesh(new THREE.SphereGeometry(0.11, 6, 5), petalMat);
        petal.position.set(Math.cos(a) * 0.12, 0.47, Math.sin(a) * 0.12);
        petal.scale.set(1, 0.55, 1);
        g.add(petal);
    }
    const core = new THREE.Mesh(new THREE.SphereGeometry(0.07, 6, 5), mat(0xf7e08a, false));
    core.position.y = 0.5;
    g.add(core);

    return { object: g, radius: 0 };
}

/** 草むら */
export function createGrassTuft(rng) {
    const g = new THREE.Group();
    const tuftMat = mat(0x6f9a4d, false);
    for (let i = 0; i < 4; i++) {
        const blade = new THREE.Mesh(new THREE.ConeGeometry(0.07, 0.5 + rng() * 0.3, 4), tuftMat);
        blade.position.set((rng() - 0.5) * 0.4, 0.28, (rng() - 0.5) * 0.4);
        blade.rotation.z = (rng() - 0.5) * 0.5;
        g.add(blade);
    }
    return { object: g, radius: 0 };
}

/** 雲（空をゆっくり流れる） */
export function createCloud(rng) {
    const g = new THREE.Group();
    const cloudMat = new THREE.MeshLambertMaterial({ color: 0xffffff, transparent: true, opacity: 0.95 });
    const n = 4 + Math.floor(rng() * 3);
    for (let i = 0; i < n; i++) {
        const puff = new THREE.Mesh(new THREE.IcosahedronGeometry(2 + rng() * 1.6, 1), cloudMat);
        puff.position.set(i * 2.2 - n, rng() * 0.9, (rng() - 0.5) * 2.2);
        g.add(puff);
    }
    g.scale.setScalar(0.9 + rng() * 0.8);
    return { object: g, radius: 0 };
}

/** 焚き火（島の中央の広場） */
export function createCampfire() {
    const g = new THREE.Group();

    const ring = new THREE.Mesh(new THREE.TorusGeometry(1.05, 0.22, 6, 14), mat(0x9aa0a6));
    ring.rotation.x = Math.PI / 2;
    ring.position.y = 0.18;
    g.add(ring);

    const logMat = mat(COLORS.trunkDark, false);
    for (let i = 0; i < 4; i++) {
        const log = new THREE.Mesh(new THREE.CylinderGeometry(0.12, 0.12, 1.5, 5), logMat);
        log.rotation.set(Math.PI / 2.4, (i / 4) * Math.PI * 2, 0);
        log.position.y = 0.35;
        g.add(log);
    }

    const flame = new THREE.Mesh(new THREE.ConeGeometry(0.5, 1.2, 7), new THREE.MeshBasicMaterial({ color: 0xf2a03c }));
    flame.position.y = 1.0;
    g.add(flame);

    const inner = new THREE.Mesh(new THREE.ConeGeometry(0.25, 0.7, 6), new THREE.MeshBasicMaterial({ color: 0xf7dc7a }));
    inner.position.y = 0.85;
    g.add(inner);

    const light = new THREE.PointLight(0xffa94d, 18, 14, 2);
    light.position.y = 1.2;
    g.add(light);

    g.userData.animate = (t) => {
        const s = 1 + Math.sin(t * 7) * 0.12;
        flame.scale.set(s, 1 + Math.sin(t * 9) * 0.18, s);
        inner.scale.setScalar(1 + Math.sin(t * 11) * 0.15);
        light.intensity = 16 + Math.sin(t * 8) * 5;
    };

    return { object: g, radius: 1.6 };
}

/**
 * 島じゅうに木・岩・花などを散らす。
 * avoid には施設など「近くに物を置きたくない」円を渡す。
 */
export function scatterProps(scene, { rng, groundHeightAt, avoid = [] }) {
    const obstacles = [];
    const animated = [];
    const blockers = [];   // カメラの視界をさえぎる可能性のあるもの

    const tooClose = (x, z, pad) => avoid.some((a) => Math.hypot(x - a.x, z - a.z) < a.r + pad)
        || obstacles.some((o) => Math.hypot(x - o.x, z - o.z) < o.r + pad);

    const place = (factory, { minR, maxR, count, pad = 2.2, onBeach = false }) => {
        let placed = 0;
        for (let attempt = 0; attempt < count * 30 && placed < count; attempt++) {
            const theta = rng() * Math.PI * 2;
            const rg = grassRadiusAt(theta);
            const r = onBeach
                ? rg + 1.5 + rng() * 3.2
                : minR + rng() * (Math.min(maxR, rg - 2.5) - minR);
            const x = Math.cos(theta) * r;
            const z = Math.sin(theta) * r;
            if (tooClose(x, z, pad)) continue;

            const item = factory(rng, 0.85 + rng() * 0.5);
            item.object.position.set(x, groundHeightAt(x, z), z);
            item.object.rotation.y = rng() * Math.PI * 2;
            scene.add(item.object);
            if (item.radius > 0) {
                obstacles.push({ x, z, r: item.radius });
                blockers.push(item.object);
            }
            placed++;
        }
    };

    place(createBroadleafTree, { minR: 5, maxR: 27, count: 26, pad: 2.8 });
    place(createPineTree,      { minR: 8, maxR: 27, count: 20, pad: 2.8 });
    place(createPalmTree,      { minR: 0, maxR: 0,  count: 14, pad: 3.0, onBeach: true });
    place(createRock,          { minR: 4, maxR: 28, count: 16, pad: 1.8 });
    place(createBush,          { minR: 4, maxR: 27, count: 24, pad: 1.6 });
    place((r) => createFlower(r), { minR: 3, maxR: 28, count: 70, pad: 0.8 });
    place((r) => createGrassTuft(r), { minR: 3, maxR: 28, count: 60, pad: 0.7 });

    // 中央広場の焚き火
    const fire = createCampfire();
    fire.object.position.set(0, groundHeightAt(0, 0), 0);
    scene.add(fire.object);
    obstacles.push({ x: 0, z: 0, r: fire.radius });
    animated.push(fire.object);

    // 空に浮かぶ雲
    const clouds = [];
    for (let i = 0; i < 12; i++) {
        const cloud = createCloud(rng);
        const a = rng() * Math.PI * 2;
        const d = 30 + rng() * 70;
        cloud.object.position.set(Math.cos(a) * d, 26 + rng() * 14, Math.sin(a) * d);
        scene.add(cloud.object);
        clouds.push(cloud.object);
    }

    return { obstacles, animated, clouds, blockers };
}

/** 島の上空を旋回する鳥 */
export function createBird(rng) {
    const g = new THREE.Group();
    const birdMat = mat(0x4a4234, false);

    const wings = [-1, 1].map((side) => {
        const wing = new THREE.Mesh(new THREE.ConeGeometry(0.22, 1.1, 3), birdMat);
        wing.rotation.z = side * Math.PI / 2;
        wing.position.x = side * 0.55;
        g.add(wing);
        return wing;
    });
    const bodyMesh = new THREE.Mesh(new THREE.SphereGeometry(0.22, 6, 5), birdMat);
    bodyMesh.scale.set(1, 0.8, 1.6);
    g.add(bodyMesh);

    const orbit = { r: 34 + rng() * 26, y: 18 + rng() * 10, speed: 0.06 + rng() * 0.05, phase: rng() * Math.PI * 2 };
    g.userData.animate = (t) => {
        const a = orbit.phase + t * orbit.speed;
        g.position.set(Math.cos(a) * orbit.r, orbit.y + Math.sin(t * 0.6 + orbit.phase) * 1.5, Math.sin(a) * orbit.r);
        g.rotation.y = -a + Math.PI / 2;
        const flap = Math.sin(t * 6 + orbit.phase) * 0.5;
        wings[0].rotation.x = flap;
        wings[1].rotation.x = -flap;
    };
    return { object: g, radius: 0 };
}
