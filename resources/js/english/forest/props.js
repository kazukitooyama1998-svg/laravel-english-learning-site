/**
 * 英語の森 – 島に置く植物や小物
 *
 * 完全な球や円錐のままだと「積み木」に見えてしまうため、すべての自然物は
 * ノイズで頂点を押し出して形を崩し、下を暗く・上を明るくした頂点カラーで
 * 立体感（擬似的な陰影）を足している。葉と草は風でゆっくり揺れる。
 */
import * as THREE from 'three';
import { mergeGeometries } from 'three/examples/jsm/utils/BufferGeometryUtils.js';
import { grassRadiusAt, fbm, valueNoise, deformGeometry } from './island.js';

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

// ── 風（葉と草を揺らす） ─────────────────────────────────────────────
const windShaders = [];

/** 毎フレーム呼んで、風のアニメーションを進める。 */
export function updateWind(time) {
    for (const shader of windShaders) shader.uniforms.uTime.value = time;
}

/**
 * 風で揺れるマテリアルを作る。
 * @param {object} params MeshStandardMaterial のパラメータ
 * @param {object} wind   { strength, height, instanced }
 */
function windMaterial(params, { strength = 0.12, height = 3.0, instanced = false } = {}) {
    const material = new THREE.MeshStandardMaterial(params);

    material.onBeforeCompile = (shader) => {
        shader.uniforms.uTime = { value: 0 };

        // インスタンス（草）は instanceMatrix、単体（木）は modelMatrix から
        // ワールド座標を取り出し、株ごとに揺れの位相をずらす
        const origin = instanced
            ? 'vec2(instanceMatrix[3][0], instanceMatrix[3][2])'
            : 'vec2(modelMatrix[3][0], modelMatrix[3][2])';

        shader.vertexShader = shader.vertexShader
            .replace('#include <common>', `#include <common>\n uniform float uTime;`)
            .replace('#include <begin_vertex>', `
                #include <begin_vertex>
                vec2 windOrigin = ${origin};
                float windPhase = windOrigin.x * 0.24 + windOrigin.y * 0.31;
                float sway = sin(uTime * 1.1 + windPhase) * 0.6
                           + sin(uTime * 0.57 + windPhase * 1.9) * 0.4;
                float windInfl = smoothstep(0.0, ${height.toFixed(2)}, transformed.y + 0.6);
                transformed.x += sway * ${strength.toFixed(3)} * windInfl;
                transformed.z += sway * ${(strength * 0.65).toFixed(3)} * windInfl;
            `);

        windShaders.push(shader);
    };

    // onBeforeCompile を差し替えたマテリアルはキャッシュキーを変えておく
    material.customProgramCacheKey = () => `wind-${strength}-${height}-${instanced}`;
    return material;
}

// ── 色づけのヘルパー ─────────────────────────────────────────────────

/**
 * 高さに応じた明暗を頂点カラーで焼き込む。
 * 下ほど暗くすることで、光が当たらない内側の陰（擬似 AO）を表現する。
 */
function paintVertical(geometry, topColor, bottomColor) {
    geometry.computeBoundingBox();
    const { min, max } = geometry.boundingBox;
    const span = Math.max(0.0001, max.y - min.y);

    const pos = geometry.attributes.position;
    const colors = new Float32Array(pos.count * 3);
    const c = new THREE.Color();

    for (let i = 0; i < pos.count; i++) {
        const t = (pos.getY(i) - min.y) / span;
        // 下端でも上の色を 3 割残す。逆光でシルエットが真っ黒にならない。
        c.copy(bottomColor).lerp(topColor, 0.3 + 0.7 * (t * t * 0.6 + t * 0.4));
        colors[i * 3] = c.r;
        colors[i * 3 + 1] = c.g;
        colors[i * 3 + 2] = c.b;
    }

    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
    return geometry;
}

/**
 * グループ内のメッシュを「同じマテリアルごと」に 1 つへ結合する。
 * 木 1 本が 6 メッシュ → 2 メッシュになり、描画コール数が大きく減る。
 * （葉の風揺れはオブジェクト単位の位相なので、結合しても木ごとに違う揺れが残る）
 */
function mergeByMaterial(group) {
    group.updateMatrixWorld(true);
    const inverse = new THREE.Matrix4().copy(group.matrixWorld).invert();

    const buckets = new Map();
    const others = [];

    group.traverse((child) => {
        if (!child.isMesh) {
            if (child !== group) others.push(child);
            return;
        }
        const geo = child.geometry.clone();
        geo.applyMatrix4(new THREE.Matrix4().multiplyMatrices(inverse, child.matrixWorld));

        const list = buckets.get(child.material) ?? [];
        list.push({ geo, castShadow: child.castShadow, receiveShadow: child.receiveShadow });
        buckets.set(child.material, list);
    });

    // 元のメッシュを外し、結合済みメッシュに差し替える
    group.clear();
    for (const child of others) {
        if (child.isMesh) continue;
        group.add(child);
    }

    for (const [material, list] of buckets) {
        const geometries = list.map((entry) => entry.geo);
        const merged = geometries.length === 1 ? geometries[0] : mergeGeometries(geometries, false);
        if (!merged) {   // 属性の構成が違って結合できない場合は個別のまま戻す
            for (const entry of list) {
                const mesh = new THREE.Mesh(entry.geo, material);
                mesh.castShadow = entry.castShadow;
                mesh.receiveShadow = entry.receiveShadow;
                group.add(mesh);
            }
            continue;
        }

        const mesh = new THREE.Mesh(merged, material);
        mesh.castShadow = list.some((entry) => entry.castShadow);
        mesh.receiveShadow = list.some((entry) => entry.receiveShadow);
        group.add(mesh);
    }

    return group;
}

// ── マテリアル（使い回してドローコールを節約する） ────────────────────
const MATERIALS = {
    // 頂点カラーを使うマテリアルは color を白にしておく。
    // ここに色を入れると頂点カラーと掛け算になり、岩や幹が黒ずんでしまう。
    bark: new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.92, metalness: 0, vertexColors: true }),
    barkPalm: new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.88, metalness: 0, vertexColors: true }),
    rock: new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.95, metalness: 0, flatShading: true, vertexColors: true }),
    stem: new THREE.MeshStandardMaterial({ color: 0x4f7a3a, roughness: 0.9, metalness: 0 }),
    flower: new THREE.MeshStandardMaterial({ roughness: 0.62, metalness: 0, vertexColors: true }),
    cloud: new THREE.MeshStandardMaterial({ color: 0xfdfdff, roughness: 1, metalness: 0, transparent: true, opacity: 0.94, vertexColors: true }),
    // 葉は風で揺れる
    leaf: windMaterial({ roughness: 0.85, metalness: 0, vertexColors: true }, { strength: 0.14, height: 3.4 }),
    leafPine: windMaterial({ roughness: 0.88, metalness: 0, vertexColors: true }, { strength: 0.07, height: 4.2 }),
    frond: windMaterial({ roughness: 0.8, metalness: 0, vertexColors: true, side: THREE.DoubleSide }, { strength: 0.2, height: 2.0 }),
};

const LEAF_TOPS = [0x83b24c, 0x79a745, 0x8dbd57, 0x6f9e3e];
const LEAF_BOTTOMS = [0x4a7434, 0x456d31, 0x507c38, 0x3f652c];
const PINE_TOPS = [0x568a4a, 0x5c9150, 0x4f8044];
const PINE_BOTTOMS = [0x335428, 0x375b2b, 0x2e4d24];

const pick = (arr, rng) => arr[Math.floor(rng() * arr.length)];

/** 葉のかたまり（変形した多面体＋上下グラデーション） */
function leafBlob(radius, topColor, bottomColor, seed, detail = 2) {
    const geo = new THREE.IcosahedronGeometry(radius, detail);
    deformGeometry(geo, 0.22, seed);
    paintVertical(geo, topColor, bottomColor);
    return geo;
}

// ── 木 ───────────────────────────────────────────────────────────────

/** 広葉樹：幹が根元で広がり、葉が数個のかたまりで茂る */
export function createBroadleafTree(rng, scale = 1) {
    const g = new THREE.Group();
    const seed = rng() * 100;

    // 幹（根張りを出すため下を太く）
    const trunkGeo = new THREE.CylinderGeometry(0.2, 0.46, 2.6, 10, 4);
    deformGeometry(trunkGeo, 0.06, seed + 5);
    paintVertical(trunkGeo, new THREE.Color(0xa17852), new THREE.Color(0x6b4f37));
    const trunk = new THREE.Mesh(trunkGeo, MATERIALS.bark);
    trunk.position.y = 1.3;
    trunk.castShadow = true;
    trunk.receiveShadow = true;
    g.add(trunk);

    const topColor = new THREE.Color(pick(LEAF_TOPS, rng));
    const bottomColor = new THREE.Color(pick(LEAF_BOTTOMS, rng));

    const blobs = [
        { r: 1.55, y: 3.2, x: 0, z: 0 },
        { r: 1.15, y: 2.85, x: -1.15, z: 0.5 },
        { r: 1.05, y: 2.95, x: 1.1, z: -0.45 },
        { r: 0.95, y: 3.95, x: 0.3, z: 0.4 },
        { r: 0.8, y: 3.5, x: -0.55, z: -0.8 },
    ];

    for (let i = 0; i < blobs.length; i++) {
        const b = blobs[i];
        const leaf = new THREE.Mesh(leafBlob(b.r, topColor, bottomColor, seed + i * 13), MATERIALS.leaf);
        leaf.position.set(b.x, b.y, b.z);
        leaf.rotation.set(rng(), rng() * Math.PI, rng() * 0.3);
        leaf.castShadow = true;
        g.add(leaf);
    }

    mergeByMaterial(g);
    g.scale.setScalar(scale);
    return { object: g, radius: 1.0 * scale };
}

/** 針葉樹：先細りの層が重なった樹形 */
export function createPineTree(rng, scale = 1) {
    const g = new THREE.Group();
    const seed = rng() * 100;

    const trunkGeo = new THREE.CylinderGeometry(0.16, 0.34, 1.6, 8, 2);
    paintVertical(trunkGeo, new THREE.Color(0x8a6440), new THREE.Color(0x5e462f));
    const trunk = new THREE.Mesh(trunkGeo, MATERIALS.bark);
    trunk.position.y = 0.8;
    trunk.castShadow = true;
    g.add(trunk);

    const topColor = new THREE.Color(pick(PINE_TOPS, rng));
    const bottomColor = new THREE.Color(pick(PINE_BOTTOMS, rng));

    const tiers = [
        { r: 1.6, h: 2.1, y: 1.7 },
        { r: 1.3, h: 1.9, y: 2.75 },
        { r: 0.98, h: 1.7, y: 3.7 },
        { r: 0.6, h: 1.4, y: 4.5 },
    ];

    for (let i = 0; i < tiers.length; i++) {
        const t = tiers[i];
        const geo = new THREE.ConeGeometry(t.r, t.h, 12, 3);
        deformGeometry(geo, 0.1, seed + i * 9);
        paintVertical(geo, topColor, bottomColor);
        const cone = new THREE.Mesh(geo, MATERIALS.leafPine);
        cone.position.y = t.y;
        cone.rotation.y = rng() * Math.PI;
        cone.castShadow = true;
        g.add(cone);
    }

    mergeByMaterial(g);
    g.scale.setScalar(scale);
    return { object: g, radius: 0.9 * scale };
}

/** ヤシの木：反った幹と垂れ下がる葉 */
export function createPalmTree(rng, scale = 1) {
    const g = new THREE.Group();
    const seed = rng() * 100;

    // 幹：少しずつ傾けた輪切りを積んで曲線にする
    let x = 0, y = 0, lean = 0;
    const trunkTop = new THREE.Color(0xb08a5e);
    const trunkBottom = new THREE.Color(0x8a6844);
    for (let i = 0; i < 8; i++) {
        const geo = new THREE.CylinderGeometry(0.17 - i * 0.009, 0.23 - i * 0.009, 0.72, 9, 1);
        paintVertical(geo, trunkTop, trunkBottom);
        const seg = new THREE.Mesh(geo, MATERIALS.barkPalm);
        lean += 0.062;
        seg.position.set(x, y + 0.36, 0);
        seg.rotation.z = -lean;
        seg.castShadow = true;
        g.add(seg);
        x += Math.sin(lean) * 0.72;
        y += Math.cos(lean) * 0.72;
    }

    // 葉：中央が高く、先が垂れ下がる曲面
    const frondTop = new THREE.Color(0x8cc65e);
    const frondBottom = new THREE.Color(0x3f7a35);
    for (let i = 0; i < 8; i++) {
        const geo = new THREE.PlaneGeometry(2.7, 0.85, 10, 3);
        const pos = geo.attributes.position;
        for (let v = 0; v < pos.count; v++) {
            const px = pos.getX(v);
            const py = pos.getY(v);
            const t = (px + 1.35) / 2.7;             // 0（根元）〜1（先端）
            pos.setZ(v, -Math.pow(t, 2) * 1.5);       // 先端ほど垂れる
            pos.setY(v, py * (1 - t * 0.75));         // 先細り
            pos.setX(v, px + 1.35);                   // 根元を原点へ
        }
        geo.rotateX(-Math.PI / 2);
        deformGeometry(geo, 0.04, seed + i * 5);
        paintVertical(geo, frondTop, frondBottom);

        const frond = new THREE.Mesh(geo, MATERIALS.frond);
        const a = (i / 8) * Math.PI * 2 + rng() * 0.3;
        frond.position.set(x, y + 0.1, 0);
        frond.rotation.y = a;
        frond.rotation.z = 0.15 + rng() * 0.12;
        frond.castShadow = true;
        g.add(frond);
    }

    // ココナッツ
    const nutMat = new THREE.MeshStandardMaterial({ color: 0x6b4b2f, roughness: 0.85, metalness: 0 });
    for (let i = 0; i < 3; i++) {
        const nut = new THREE.Mesh(new THREE.SphereGeometry(0.19, 10, 8), nutMat);
        const a = (i / 3) * Math.PI * 2;
        nut.position.set(x + Math.cos(a) * 0.22, y - 0.18, Math.sin(a) * 0.22);
        nut.castShadow = true;
        g.add(nut);
    }

    mergeByMaterial(g);
    g.scale.setScalar(scale);
    return { object: g, radius: 0.8 * scale };
}

// ── 岩・茂み・花 ─────────────────────────────────────────────────────

export function createRock(rng, scale = 1) {
    const g = new THREE.Group();
    const geo = new THREE.IcosahedronGeometry(0.85, 1);
    deformGeometry(geo, 0.34, rng() * 100);
    paintVertical(geo, new THREE.Color(0xb9b5ab), new THREE.Color(0x7e7a72));

    const rock = new THREE.Mesh(geo, MATERIALS.rock);
    rock.scale.set(1 + rng() * 0.5, 0.72 + rng() * 0.45, 1 + rng() * 0.5);
    rock.rotation.set(rng(), rng() * Math.PI, rng());
    rock.position.y = 0.38 * scale;
    rock.castShadow = true;
    rock.receiveShadow = true;
    g.add(rock);

    // 足元に小石を添えて地面となじませる
    if (rng() > 0.45) {
        const pebbleGeo = new THREE.IcosahedronGeometry(0.26, 0);
        deformGeometry(pebbleGeo, 0.3, rng() * 50);
        paintVertical(pebbleGeo, new THREE.Color(0xaeaaa1), new THREE.Color(0x76736c));
        const pebble = new THREE.Mesh(pebbleGeo, MATERIALS.rock);
        pebble.position.set((rng() - 0.5) * 1.5, 0.1, (rng() - 0.5) * 1.5);
        pebble.castShadow = true;
        g.add(pebble);
    }

    mergeByMaterial(g);
    g.scale.setScalar(scale);
    return { object: g, radius: 1.0 * scale };
}

export function createBush(rng, scale = 1) {
    const g = new THREE.Group();
    const seed = rng() * 100;
    const topColor = new THREE.Color(pick(LEAF_TOPS, rng));
    const bottomColor = new THREE.Color(pick(LEAF_BOTTOMS, rng));

    for (let i = 0; i < 3; i++) {
        const leaf = new THREE.Mesh(
            leafBlob(0.5 + rng() * 0.3, topColor, bottomColor, seed + i * 11, 2),
            MATERIALS.leaf,
        );
        leaf.position.set((rng() - 0.5) * 0.85, 0.38 + rng() * 0.22, (rng() - 0.5) * 0.85);
        leaf.castShadow = true;
        g.add(leaf);
    }

    // 実をつけた茂みも混ぜる
    if (rng() > 0.65) {
        const berryMat = new THREE.MeshStandardMaterial({ color: 0xd2514f, roughness: 0.5, metalness: 0 });
        for (let i = 0; i < 5; i++) {
            const berry = new THREE.Mesh(new THREE.SphereGeometry(0.09, 8, 6), berryMat);
            berry.position.set((rng() - 0.5) * 1, 0.45 + rng() * 0.35, (rng() - 0.5) * 1);
            g.add(berry);
        }
    }

    mergeByMaterial(g);
    g.scale.setScalar(scale);
    return { object: g, radius: 0.7 * scale };
}

const FLOWER_COLORS = [0xe8756b, 0xf2b44c, 0xf4f0e6, 0xd48fc0, 0xf0d264, 0xb98ad4];

/** ジオメトリ全体を 1 色の頂点カラーで塗る（結合しても色が保てる） */
function paintUniform(geometry, color) {
    const count = geometry.attributes.position.count;
    const colors = new Float32Array(count * 3);
    for (let i = 0; i < count; i++) {
        colors[i * 3] = color.r;
        colors[i * 3 + 1] = color.g;
        colors[i * 3 + 2] = color.b;
    }
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
    return geometry;
}

/**
 * 花。色を頂点カラーに焼き込んで 1 メッシュに結合するので、
 * 何本生やしても描画コストがほとんど増えない。
 */
export function createFlower(rng) {
    const petalColor = new THREE.Color(pick(FLOWER_COLORS, rng));
    const stemColor = new THREE.Color(0x4f7a3a);
    const parts = [];

    const stem = new THREE.CylinderGeometry(0.025, 0.04, 0.5, 5);
    stem.translate(0, 0.25, 0);
    parts.push(paintUniform(stem, stemColor));

    // 葉を 1 枚添える
    const leaf = new THREE.SphereGeometry(0.1, 6, 5);
    leaf.scale(1.6, 0.25, 0.8);
    leaf.rotateZ(-0.5);
    leaf.translate(0.09, 0.2, 0);
    parts.push(paintUniform(leaf, stemColor));

    for (let i = 0; i < 6; i++) {
        const a = (i / 6) * Math.PI * 2;
        const petal = new THREE.SphereGeometry(0.1, 7, 6);
        petal.scale(1, 0.45, 1);
        petal.translate(Math.cos(a) * 0.11, 0.52, Math.sin(a) * 0.11);
        parts.push(paintUniform(petal, petalColor));
    }

    const core = new THREE.SphereGeometry(0.07, 7, 6);
    core.translate(0, 0.55, 0);
    parts.push(paintUniform(core, new THREE.Color(0xf7d774)));

    const merged = mergeGeometries(parts, false);
    const flower = new THREE.Mesh(merged ?? parts[0], MATERIALS.flower);
    flower.castShadow = true;

    const g = new THREE.Group();
    g.add(flower);
    return { object: g, radius: 0 };
}

// ── 草原（インスタンシングで一面に生やす） ─────────────────────────────

/** 1 本の草の葉（根元が太く、先が細い曲面）。キャラクターの足首ほどの高さ。 */
const BLADE_HEIGHT = 0.34;

function bladeGeometry() {
    const geo = new THREE.PlaneGeometry(0.075, BLADE_HEIGHT, 1, 3);
    const pos = geo.attributes.position;
    for (let i = 0; i < pos.count; i++) {
        const y = pos.getY(i) + BLADE_HEIGHT / 2;   // 0（根元）〜BLADE_HEIGHT（先端）
        const t = y / BLADE_HEIGHT;
        pos.setX(i, pos.getX(i) * (1 - t * 0.8));   // 先細り
        pos.setZ(i, Math.pow(t, 2) * 0.1);           // わずかに反る
        pos.setY(i, y);
    }
    pos.needsUpdate = true;
    geo.computeVertexNormals();
    return geo;
}

/**
 * 草を数本まとめた「株」。1 インスタンスで数本生えるので、
 * 同じ描画コストのまま見た目の密度を上げられる。
 */
function tuftGeometry(bladesPerTuft = 4) {
    const parts = [];
    for (let i = 0; i < bladesPerTuft; i++) {
        const blade = bladeGeometry();
        const a = (i / bladesPerTuft) * Math.PI * 2 + i * 0.7;
        const lean = 0.12 + (i % 3) * 0.12;
        blade.rotateZ(Math.sin(a) * lean);
        blade.rotateX(Math.cos(a) * lean);
        blade.rotateY(a);
        blade.translate(Math.cos(a) * 0.055, 0, Math.sin(a) * 0.055);
        blade.scale(1, 0.75 + (i % 3) * 0.22, 1);
        parts.push(blade);
    }

    // まとめて 1 つのジオメトリに結合する
    const merged = parts[0];
    const positions = [];
    for (const part of parts) positions.push(...part.attributes.position.array);
    const indices = [];
    const perPart = merged.attributes.position.count;
    for (let p = 0; p < parts.length; p++) {
        const offset = p * perPart;
        for (const idx of parts[p].index.array) indices.push(idx + offset);
    }

    const geo = new THREE.BufferGeometry();
    geo.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    geo.setIndex(indices);
    geo.computeVertexNormals();

    // 草の法線を上向きに寄せる。細い葉は横を向いていると太陽光をほとんど
    // 受けず、芝生の上に暗いトゲが立っているように見えてしまうため、
    // 地面と同じ向きの光を受けさせて一体の芝に見せる（草描画の定石）。
    const normal = geo.attributes.normal;
    for (let i = 0; i < normal.count; i++) {
        const nx = normal.getX(i) * 0.15;
        const ny = normal.getY(i) * 0.15 + 0.85;
        const nz = normal.getZ(i) * 0.15;
        const len = Math.hypot(nx, ny, nz) || 1;
        normal.setXYZ(i, nx / len, ny / len, nz / len);
    }
    normal.needsUpdate = true;

    return geo;
}

/**
 * 芝生の上に草を大量に生やす。InstancedMesh なので描画は 1 回で済む。
 */
export function createGrassField(scene, { rng, island, count = 5200, avoid = [] }) {
    const geo = tuftGeometry(4);
    const material = windMaterial({
        roughness: 0.9,
        metalness: 0,
        vertexColors: true,
        side: THREE.DoubleSide,
    }, { strength: 0.16, height: 0.7, instanced: true });

    // 根元を暗く、穂先を明るくして立体感を出す
    paintVertical(geo, new THREE.Color(0x9cc767), new THREE.Color(0x6a9440));

    const mesh = new THREE.InstancedMesh(geo, material, count);
    mesh.castShadow = false;
    mesh.receiveShadow = false;
    mesh.instanceMatrix.setUsage(THREE.DynamicDrawUsage);

    const dummy = new THREE.Object3D();
    const tint = new THREE.Color();
    let placed = 0;

    for (let attempt = 0; attempt < count * 8 && placed < count; attempt++) {
        const theta = rng() * Math.PI * 2;
        const rg = grassRadiusAt(theta);
        const r = Math.sqrt(rng()) * (rg - 2.2);
        const x = Math.cos(theta) * r;
        const z = Math.sin(theta) * r;

        // 施設まわりは草を薄くする
        if (avoid.some((a) => Math.hypot(x - a.x, z - a.z) < a.r * 0.75)) continue;
        // 踏み固められた小道と広場には生やさない（道がはっきり見えるようになる）
        if (island.pathWeightAt(x, z) > 0.28) continue;
        if (Math.hypot(x, z) < 3.4) continue;

        const y = island.groundHeightAt(x, z);
        dummy.position.set(x, y - 0.02, z);
        dummy.rotation.set(0, rng() * Math.PI * 2, (rng() - 0.5) * 0.3);
        const s = 0.7 + rng() * 0.6;
        dummy.scale.set(s, s * (0.75 + rng() * 0.7), s);
        dummy.updateMatrix();
        mesh.setMatrixAt(placed, dummy.matrix);

        // 生え際の色を少しずつ変えて単調さをなくす
        const n = fbm(x * 0.08, z * 0.08, 2);
        tint.setRGB(0.92 + n * 0.16, 0.97 + n * 0.1, 0.88 + n * 0.14);
        mesh.setColorAt(placed, tint);

        placed++;
    }

    mesh.count = placed;
    mesh.instanceMatrix.needsUpdate = true;
    if (mesh.instanceColor) mesh.instanceColor.needsUpdate = true;
    scene.add(mesh);
    return mesh;
}

// ── 雲・焚き火・鳥 ───────────────────────────────────────────────────

export function createCloud(rng) {
    const g = new THREE.Group();
    const n = 5 + Math.floor(rng() * 4);
    const top = new THREE.Color(0xffffff);
    const bottom = new THREE.Color(0xc7d4e4);   // 雲の底は空の色を拾って青みがかる

    for (let i = 0; i < n; i++) {
        const geo = new THREE.IcosahedronGeometry(2.1 + rng() * 1.8, 2);
        deformGeometry(geo, 0.2, rng() * 100);
        paintVertical(geo, top, bottom);
        const puff = new THREE.Mesh(geo, MATERIALS.cloud);
        puff.position.set(i * 2.3 - n, rng() * 1.1, (rng() - 0.5) * 2.6);
        puff.scale.y = 0.75;
        g.add(puff);
    }

    mergeByMaterial(g);
    g.scale.setScalar(0.9 + rng() * 0.9);
    return { object: g, radius: 0 };
}

/** 焚き火（島の中央の広場） */
export function createCampfire() {
    const g = new THREE.Group();

    const stoneMat = new THREE.MeshStandardMaterial({ color: 0x8d8b83, roughness: 0.95, metalness: 0, flatShading: true });
    for (let i = 0; i < 9; i++) {
        const a = (i / 9) * Math.PI * 2;
        const geo = new THREE.IcosahedronGeometry(0.26 + valueNoise(i * 2.7, 1.1) * 0.12, 0);
        deformGeometry(geo, 0.3, i * 3.3);
        const stone = new THREE.Mesh(geo, stoneMat);
        stone.position.set(Math.cos(a) * 1.05, 0.14, Math.sin(a) * 1.05);
        stone.rotation.set(i, a, i * 0.5);
        stone.castShadow = true;
        stone.receiveShadow = true;
        g.add(stone);
    }

    const logMat = new THREE.MeshStandardMaterial({ color: 0x5b4128, roughness: 0.92, metalness: 0 });
    const emberMat = new THREE.MeshStandardMaterial({
        color: 0x3a2a1c, roughness: 0.9, emissive: 0xd2521a, emissiveIntensity: 0.8,
    });
    for (let i = 0; i < 5; i++) {
        const log = new THREE.Mesh(new THREE.CylinderGeometry(0.1, 0.12, 1.4, 7), i === 0 ? emberMat : logMat);
        log.rotation.set(Math.PI / 2.3, (i / 5) * Math.PI * 2, 0);
        log.position.y = 0.3;
        log.castShadow = true;
        g.add(log);
    }

    // 炎：内側ほど明るい 2 枚重ね
    const flame = new THREE.Mesh(
        new THREE.ConeGeometry(0.44, 1.25, 9),
        new THREE.MeshStandardMaterial({
            color: 0xf07c25, emissive: 0xf2711c, emissiveIntensity: 2.4,
            roughness: 1, transparent: true, opacity: 0.92,
        }),
    );
    flame.position.y = 1.0;
    g.add(flame);

    const core = new THREE.Mesh(
        new THREE.ConeGeometry(0.22, 0.72, 8),
        new THREE.MeshStandardMaterial({
            color: 0xffd98a, emissive: 0xffca6a, emissiveIntensity: 3.2, roughness: 1,
        }),
    );
    core.position.y = 0.88;
    g.add(core);

    const light = new THREE.PointLight(0xffa94d, 22, 16, 2);
    light.position.y = 1.3;
    g.add(light);

    g.userData.animate = (t) => {
        const s = 1 + Math.sin(t * 7) * 0.13;
        flame.scale.set(s, 1 + Math.sin(t * 9.3) * 0.2, s);
        flame.rotation.y = t * 0.9;
        core.scale.setScalar(1 + Math.sin(t * 11) * 0.16);
        light.intensity = 18 + Math.sin(t * 8) * 6;
    };

    return { object: g, radius: 1.6 };
}

/** 島の上空を旋回する鳥 */
export function createBird(rng) {
    const g = new THREE.Group();
    const birdMat = new THREE.MeshStandardMaterial({ color: 0x4a4234, roughness: 0.9, metalness: 0 });

    const wings = [-1, 1].map((side) => {
        const wing = new THREE.Mesh(new THREE.ConeGeometry(0.22, 1.1, 4), birdMat);
        wing.rotation.z = side * Math.PI / 2;
        wing.position.x = side * 0.55;
        g.add(wing);
        return wing;
    });
    const bodyMesh = new THREE.Mesh(new THREE.SphereGeometry(0.22, 8, 6), birdMat);
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

// ── 配置 ─────────────────────────────────────────────────────────────

/**
 * 島じゅうに木・岩・花などを散らす。
 * avoid には施設など「近くに物を置きたくない」円を渡す。
 */
export function scatterProps(scene, { rng, groundHeightAt, avoid = [], quality = 'high' }) {
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

    const dense = quality !== 'low';
    // 島の外周付近に木を寄せ、中央部は歩きやすく開けておく
    place(createBroadleafTree, { minR: 12, maxR: 37, count: dense ? 26 : 18, pad: 4.5 });
    place(createPineTree,      { minR: 15, maxR: 37, count: dense ? 18 : 12, pad: 4.5 });
    place(createPalmTree,      { minR: 0,  maxR: 0,  count: dense ? 16 : 10, pad: 4.0, onBeach: true });
    place(createRock,          { minR: 10, maxR: 37, count: dense ? 8  : 5,  pad: 5.0 });
    place(createBush,          { minR: 10, maxR: 37, count: dense ? 12 : 8,  pad: 4.5 });
    place((r) => createFlower(r), { minR: 5, maxR: 38, count: dense ? 85 : 45, pad: 1.2 });

    // 中央広場の焚き火
    const fire = createCampfire();
    fire.object.position.set(0, groundHeightAt(0, 0), 0);
    scene.add(fire.object);
    obstacles.push({ x: 0, z: 0, r: fire.radius });
    animated.push(fire.object);

    // 空に浮かぶ雲
    const clouds = [];
    for (let i = 0; i < (dense ? 14 : 8); i++) {
        const cloud = createCloud(rng);
        const a = rng() * Math.PI * 2;
        const d = 30 + rng() * 80;
        cloud.object.position.set(Math.cos(a) * d, 28 + rng() * 16, Math.sin(a) * d);
        scene.add(cloud.object);
        clouds.push(cloud.object);
    }

    return { obstacles, animated, clouds, blockers };
}
