/**
 * 英語の森 – 学習機能への入口となる施設
 *
 * config/english.php の forest_spots に対応する 3D モデルをここで組み立てる。
 * プレイヤーが足元の光の輪に入ると、その機能のページへ移動できる。
 */
import * as THREE from 'three';
import { createLabel } from './labels.js';

export const INTERACT_RADIUS = 4.2;

const mat = (color, flat = true) => new THREE.MeshLambertMaterial({ color, flatShading: flat });

/** 単語の木：本の看板をさげた大きな木 */
function buildBigTree(color) {
    const g = new THREE.Group();

    const trunk = new THREE.Mesh(new THREE.CylinderGeometry(0.75, 1.25, 4.2, 9), mat(0x9a7350, false));
    trunk.position.y = 2.1;
    trunk.castShadow = true;
    g.add(trunk);

    const leafMat = mat(color);
    [[0, 5.6, 0, 3.2], [-2.2, 5.0, 1.0, 2.2], [2.3, 5.2, -0.8, 2.0], [0.4, 7.3, 0.6, 1.8]].forEach(([x, y, z, r]) => {
        const leaf = new THREE.Mesh(new THREE.IcosahedronGeometry(r, 1), leafMat);
        leaf.position.set(x, y, z);
        leaf.castShadow = true;
        g.add(leaf);
    });

    // 幹にたてかけた本
    const book = new THREE.Mesh(new THREE.BoxGeometry(1.5, 1.0, 0.28), mat(0xf4efe2, false));
    book.position.set(1.3, 0.6, 1.2);
    book.rotation.set(-0.35, 0.4, 0.15);
    g.add(book);

    return { object: g, radius: 1.5, labelY: 9.4 };
}

/** TOEIC キャンプ：三角テントとランタン */
function buildTent(color) {
    const g = new THREE.Group();

    const body = new THREE.Mesh(new THREE.CylinderGeometry(0.01, 2.6, 3.4, 4, 1, true), mat(color, false));
    body.rotation.y = Math.PI / 4;
    body.position.y = 1.7;
    body.material.side = THREE.DoubleSide;
    body.castShadow = true;
    g.add(body);

    const door = new THREE.Mesh(new THREE.ConeGeometry(0.9, 2.0, 3), mat(0x4a4234, false));
    door.position.set(0, 1.0, 1.45);
    door.rotation.y = Math.PI;
    g.add(door);

    // ペグと張り綱代わりの杭
    const pegMat = mat(0x7f5c3e, false);
    [-2.4, 2.4].forEach((x) => {
        const peg = new THREE.Mesh(new THREE.CylinderGeometry(0.08, 0.08, 0.9, 5), pegMat);
        peg.position.set(x, 0.45, -1.8);
        g.add(peg);
    });

    // ランタン
    const post = new THREE.Mesh(new THREE.CylinderGeometry(0.07, 0.09, 1.6, 6), mat(0x5c6675, false));
    post.position.set(2.6, 0.8, 1.4);
    g.add(post);
    const lamp = new THREE.Mesh(new THREE.SphereGeometry(0.28, 8, 7), new THREE.MeshBasicMaterial({ color: 0xf7dc7a }));
    lamp.position.set(2.6, 1.7, 1.4);
    g.add(lamp);
    const lampLight = new THREE.PointLight(0xffd98a, 8, 9, 2);
    lampLight.position.copy(lamp.position);
    g.add(lampLight);

    return { object: g, radius: 2.2, labelY: 5.0 };
}

/** IELTS 灯台：紅白のタワー */
function buildLighthouse(color) {
    const g = new THREE.Group();

    const base = new THREE.Mesh(new THREE.CylinderGeometry(2.0, 2.4, 0.8, 12), mat(0xb8b2a4));
    base.position.y = 0.4;
    base.receiveShadow = true;
    g.add(base);

    const tower = new THREE.Mesh(new THREE.CylinderGeometry(1.0, 1.6, 7.0, 12), mat(0xfdfbf5, false));
    tower.position.y = 4.2;
    tower.castShadow = true;
    g.add(tower);

    // 赤いストライプ
    const stripeMat = mat(color, false);
    [2.0, 4.2, 6.4].forEach((y, i) => {
        const r = 1.55 - i * 0.2;
        const stripe = new THREE.Mesh(new THREE.CylinderGeometry(r - 0.09, r, 0.9, 12), stripeMat);
        stripe.position.y = y;
        g.add(stripe);
    });

    const deck = new THREE.Mesh(new THREE.CylinderGeometry(1.45, 1.45, 0.3, 12), mat(0x5c6675));
    deck.position.y = 7.8;
    g.add(deck);

    const lantern = new THREE.Mesh(new THREE.CylinderGeometry(0.85, 0.85, 1.3, 10), new THREE.MeshBasicMaterial({ color: 0xf9e6a0 }));
    lantern.position.y = 8.6;
    g.add(lantern);

    const roof = new THREE.Mesh(new THREE.ConeGeometry(1.3, 1.2, 10), mat(color, false));
    roof.position.y = 9.8;
    g.add(roof);

    const beam = new THREE.PointLight(0xfff0b8, 20, 22, 2);
    beam.position.y = 8.6;
    g.add(beam);

    g.userData.animate = (t) => { beam.intensity = 16 + Math.sin(t * 2) * 8; };

    return { object: g, radius: 2.3, labelY: 11.6 };
}

/** タイピング小屋：三角屋根のログハウス */
function buildCabin(color) {
    const g = new THREE.Group();

    const walls = new THREE.Mesh(new THREE.BoxGeometry(4.6, 3.0, 4.0), mat(0xe8d9bb, false));
    walls.position.y = 1.5;
    walls.castShadow = true;
    walls.receiveShadow = true;
    g.add(walls);

    const roof = new THREE.Mesh(new THREE.ConeGeometry(3.9, 2.0, 4), mat(color, false));
    roof.position.y = 4.0;
    roof.rotation.y = Math.PI / 4;
    roof.castShadow = true;
    g.add(roof);

    const door = new THREE.Mesh(new THREE.BoxGeometry(1.2, 2.0, 0.16), mat(0x8a5f3c, false));
    door.position.set(0, 1.0, 2.05);
    g.add(door);

    const knob = new THREE.Mesh(new THREE.SphereGeometry(0.09, 6, 6), mat(0xc98f3c, false));
    knob.position.set(0.42, 1.05, 2.16);
    g.add(knob);

    const windowMat = mat(0x9fd0e2, false);
    [[-1.6, 2.05], [1.6, 2.05]].forEach(([x, z]) => {
        const win = new THREE.Mesh(new THREE.BoxGeometry(1.0, 1.0, 0.12), windowMat);
        win.position.set(x, 1.9, z);
        g.add(win);
    });

    // 煙突
    const chimney = new THREE.Mesh(new THREE.BoxGeometry(0.6, 1.4, 0.6), mat(0xb1795a, false));
    chimney.position.set(1.3, 4.4, -0.9);
    g.add(chimney);

    return { object: g, radius: 3.0, labelY: 6.0 };
}

/** クイズ広場：円形ステージと「？」看板 */
function buildStage(color) {
    const g = new THREE.Group();

    const stage = new THREE.Mesh(new THREE.CylinderGeometry(3.2, 3.4, 0.55, 16), mat(0xe0d2b4));
    stage.position.y = 0.28;
    stage.receiveShadow = true;
    g.add(stage);

    const inner = new THREE.Mesh(new THREE.CylinderGeometry(2.2, 2.2, 0.6, 16), mat(color, false));
    inner.position.y = 0.34;
    g.add(inner);

    // ステージを囲む柱
    const postMat = mat(0xfdfbf5, false);
    for (let i = 0; i < 4; i++) {
        const a = (i / 4) * Math.PI * 2 + Math.PI / 4;
        const post = new THREE.Mesh(new THREE.CylinderGeometry(0.16, 0.16, 3.4, 8), postMat);
        post.position.set(Math.cos(a) * 2.9, 1.7, Math.sin(a) * 2.9);
        post.castShadow = true;
        g.add(post);
    }

    const canopy = new THREE.Mesh(new THREE.ConeGeometry(3.5, 1.6, 8), mat(color, false));
    canopy.position.y = 4.5;
    canopy.castShadow = true;
    g.add(canopy);

    const ball = new THREE.Mesh(new THREE.SphereGeometry(0.35, 10, 8), mat(0xf2b44c, false));
    ball.position.y = 5.5;
    g.add(ball);
    g.userData.animate = (t) => { ball.position.y = 5.5 + Math.sin(t * 2) * 0.15; ball.rotation.y = t; };

    return { object: g, radius: 3.2, labelY: 6.9 };
}

/** ランキング展望台：らせん階段のある塔 */
function buildTower(color) {
    const g = new THREE.Group();

    const base = new THREE.Mesh(new THREE.BoxGeometry(3.4, 0.6, 3.4), mat(0xd9c79a));
    base.position.y = 0.3;
    base.receiveShadow = true;
    g.add(base);

    const legMat = mat(0xa1815f, false);
    const legs = [[-1.2, -1.2], [1.2, -1.2], [-1.2, 1.2], [1.2, 1.2]];
    legs.forEach(([x, z]) => {
        const leg = new THREE.Mesh(new THREE.CylinderGeometry(0.2, 0.24, 5.0, 6), legMat);
        leg.position.set(x, 2.8, z);
        leg.castShadow = true;
        g.add(leg);
    });

    const deck = new THREE.Mesh(new THREE.BoxGeometry(4.2, 0.4, 4.2), mat(0xc9a877, false));
    deck.position.y = 5.4;
    deck.castShadow = true;
    g.add(deck);

    const railMat = mat(color, false);
    [[0, 2.0], [0, -2.0], [2.0, 0], [-2.0, 0]].forEach(([x, z]) => {
        const rail = new THREE.Mesh(new THREE.BoxGeometry(x === 0 ? 4.2 : 0.2, 0.9, x === 0 ? 0.2 : 4.2), railMat);
        rail.position.set(x, 6.0, z);
        g.add(rail);
    });

    const roof = new THREE.Mesh(new THREE.ConeGeometry(3.2, 1.6, 4), mat(color, false));
    roof.position.y = 7.6;
    roof.rotation.y = Math.PI / 4;
    roof.castShadow = true;
    g.add(roof);

    // はしご
    const rungMat = mat(0x8a5f3c, false);
    for (let i = 0; i < 8; i++) {
        const rung = new THREE.Mesh(new THREE.BoxGeometry(1.2, 0.12, 0.12), rungMat);
        rung.position.set(0, 0.8 + i * 0.6, 2.0);
        g.add(rung);
    }

    return { object: g, radius: 2.6, labelY: 9.2 };
}

/** 学習の掲示板 */
function buildBoard(color) {
    const g = new THREE.Group();

    const postMat = mat(0x8a5f3c, false);
    [-1.3, 1.3].forEach((x) => {
        const post = new THREE.Mesh(new THREE.CylinderGeometry(0.16, 0.18, 2.6, 6), postMat);
        post.position.set(x, 1.3, 0);
        post.castShadow = true;
        g.add(post);
    });

    const panel = new THREE.Mesh(new THREE.BoxGeometry(3.4, 2.0, 0.22), mat(0xf4efe2, false));
    panel.position.y = 2.2;
    panel.castShadow = true;
    g.add(panel);

    const frame = new THREE.Mesh(new THREE.BoxGeometry(3.7, 2.3, 0.14), mat(color, false));
    frame.position.set(0, 2.2, -0.08);
    g.add(frame);

    // 貼り紙
    const noteMat = mat(0xf2b44c, false);
    [[-0.9, 2.5], [0.3, 2.2], [1.0, 2.7]].forEach(([x, y]) => {
        const note = new THREE.Mesh(new THREE.BoxGeometry(0.7, 0.5, 0.04), noteMat);
        note.position.set(x, y, 0.14);
        note.rotation.z = (x % 0.4) - 0.1;
        g.add(note);
    });

    const roof = new THREE.Mesh(new THREE.BoxGeometry(4.0, 0.2, 0.9), mat(color, false));
    roof.position.set(0, 3.4, 0);
    roof.rotation.x = -0.25;
    g.add(roof);

    return { object: g, radius: 1.9, labelY: 4.6 };
}

const BUILDERS = {
    bigtree: buildBigTree,
    tent: buildTent,
    lighthouse: buildLighthouse,
    cabin: buildCabin,
    stage: buildStage,
    tower: buildTower,
    board: buildBoard,
};

/** 足元の光の輪（入口の目印） */
function createRing(color) {
    const ring = new THREE.Mesh(
        new THREE.RingGeometry(INTERACT_RADIUS - 0.45, INTERACT_RADIUS, 48),
        new THREE.MeshBasicMaterial({ color, transparent: true, opacity: 0.45, side: THREE.DoubleSide, depthWrite: false }),
    );
    ring.rotation.x = -Math.PI / 2;
    return ring;
}

/**
 * 施設をまとめて配置する。
 * @returns {{spots: Array, obstacles: Array}}
 */
export function buildSpots(scene, spotConfigs, groundHeightAt) {
    const spots = [];
    const obstacles = [];

    for (const cfg of spotConfigs) {
        const builder = BUILDERS[cfg.kind] || buildBoard;
        const built = builder(new THREE.Color(cfg.color));

        const y = groundHeightAt(cfg.x, cfg.z);
        built.object.position.set(cfg.x, y, cfg.z);
        built.object.rotation.y = cfg.rot || 0;
        scene.add(built.object);

        const ring = createRing(cfg.color);
        ring.position.set(cfg.x, y + 0.08, cfg.z);
        scene.add(ring);

        const label = createLabel(cfg.name, { sub: cfg.desc, accent: cfg.color, height: 1.25 });
        label.position.set(cfg.x, y + built.labelY, cfg.z);
        scene.add(label);

        obstacles.push({ x: cfg.x, z: cfg.z, r: built.radius });
        spots.push({ ...cfg, object: built.object, ring, label, baseLabelY: y + built.labelY, near: false });
    }

    return { spots, obstacles };
}

/**
 * 毎フレームの更新。プレイヤーに一番近い（範囲内の）施設を返す。
 */
export function updateSpots(spots, playerPos, time, cameraPos = null) {
    let active = null;
    let activeDist = Infinity;

    for (const spot of spots) {
        const d = Math.hypot(playerPos.x - spot.x, playerPos.z - spot.z);
        const near = d < INTERACT_RADIUS;
        if (near && d < activeDist) { active = spot; activeDist = d; }

        // 近づくと輪が明るく脈打ち、看板が少し浮き上がる
        const pulse = 0.5 + Math.sin(time * 3) * 0.5;
        spot.ring.material.opacity = near ? 0.55 + pulse * 0.35 : 0.28 + pulse * 0.08;
        const scale = near ? 1.04 + pulse * 0.04 : 1;
        spot.ring.scale.set(scale, scale, scale);

        const lift = near ? 0.5 : 0;
        spot.label.position.y += ((spot.baseLabelY + lift + Math.sin(time * 1.5 + spot.x) * 0.12) - spot.label.position.y) * 0.1;

        // カメラのすぐ手前に来た看板は視界の邪魔になるので薄くする
        if (cameraPos) {
            const camDist = cameraPos.distanceTo(spot.label.position);
            spot.label.material.opacity = Math.min(1, Math.max(0, (camDist - 3.5) / 5));
        }

        if (spot.object.userData.animate) spot.object.userData.animate(time);
        spot.near = near;
    }

    return active;
}
