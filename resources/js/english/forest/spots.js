/**
 * 英語の森 – 学習機能への入口となる施設
 *
 * config/english.php の forest_spots に対応する 3D モデルをここで組み立てる。
 * プレイヤーが足元の光の輪に入ると、その機能のページへ移動できる。
 */
import * as THREE from 'three';
import { createLabel } from './labels.js';

export const INTERACT_RADIUS = 4.2;

// 施設の素材。PBR（金属度・粗さ）で光を受けるので、面ごとの陰影がはっきり出る。
const mat = (color, flat = true) => new THREE.MeshStandardMaterial({
    color, flatShading: flat, roughness: 0.88, metalness: 0,
});

/** ランプや灯台の光源部分（自発光させて夕暮れでも光って見える） */
const glowMat = (color, intensity = 1.6) => new THREE.MeshStandardMaterial({
    color, emissive: color, emissiveIntensity: intensity, roughness: 0.4, metalness: 0,
});

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
    const lamp = new THREE.Mesh(new THREE.SphereGeometry(0.28, 12, 10), glowMat(0xffd98a, 2.2));
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

    const lantern = new THREE.Mesh(new THREE.CylinderGeometry(0.85, 0.85, 1.3, 16), glowMat(0xfff0bb, 2.6));
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

/** タイピング小屋：丸太を積んだ壁と、軒を張り出した三角屋根 */
function buildCabin(color) {
    const g = new THREE.Group();

    // 石積みの土台（地面との境目をぼかし、建物が「置いてある」感を消す）
    const foundation = new THREE.Mesh(new THREE.BoxGeometry(5.0, 0.45, 4.4), mat(0x8d8b83));
    foundation.position.y = 0.22;
    g.add(foundation);

    const walls = new THREE.Mesh(new THREE.BoxGeometry(4.6, 3.0, 4.0), mat(0xe8d9bb, false));
    walls.position.y = 1.9;
    g.add(walls);

    // 横に積んだ丸太。壁に凹凸の陰影が生まれる。
    const logMat = mat(0xd9c6a2, false);
    for (let i = 0; i < 6; i++) {
        const log = new THREE.Mesh(new THREE.CylinderGeometry(0.26, 0.26, 4.75, 10), logMat);
        log.rotation.z = Math.PI / 2;
        log.position.set(0, 0.65 + i * 0.5, 2.02);
        g.add(log);
        const back = log.clone();
        back.position.z = -2.02;
        g.add(back);
    }

    // 屋根：軒を大きく張り出して影を落とす
    const roof = new THREE.Mesh(new THREE.ConeGeometry(4.35, 2.2, 4), mat(color, false));
    roof.position.y = 4.35;
    roof.rotation.y = Math.PI / 4;
    g.add(roof);

    const eaves = new THREE.Mesh(new THREE.ConeGeometry(4.55, 0.35, 4), mat(0x6b5642, false));
    eaves.position.y = 3.35;
    eaves.rotation.y = Math.PI / 4;
    g.add(eaves);

    const ridge = new THREE.Mesh(new THREE.BoxGeometry(0.3, 0.3, 6.2), mat(0x6b5642, false));
    ridge.position.y = 5.3;
    g.add(ridge);

    // 入口（枠を付けて奥行きを出す）
    const doorFrame = new THREE.Mesh(new THREE.BoxGeometry(1.5, 2.3, 0.2), mat(0x6b4a2f, false));
    doorFrame.position.set(0, 1.35, 2.06);
    g.add(doorFrame);

    const door = new THREE.Mesh(new THREE.BoxGeometry(1.2, 2.0, 0.16), mat(0x8a5f3c, false));
    door.position.set(0, 1.2, 2.14);
    g.add(door);

    const knob = new THREE.Mesh(new THREE.SphereGeometry(0.09, 10, 8), mat(0xc98f3c, false));
    knob.position.set(0.42, 1.25, 2.24);
    g.add(knob);

    // 窓：枠 → ガラス → 十字桟の 3 層
    const frameMat = mat(0x6b4a2f, false);
    const glassMat = new THREE.MeshStandardMaterial({
        color: 0x9fd0e2, roughness: 0.15, metalness: 0.2,
        emissive: 0x88b4c8, emissiveIntensity: 0.25,
    });
    [-1.55, 1.55].forEach((x) => {
        const frame = new THREE.Mesh(new THREE.BoxGeometry(1.25, 1.25, 0.14), frameMat);
        frame.position.set(x, 2.25, 2.06);
        g.add(frame);

        const glass = new THREE.Mesh(new THREE.BoxGeometry(1.0, 1.0, 0.1), glassMat);
        glass.position.set(x, 2.25, 2.12);
        g.add(glass);

        [[1.05, 0.08], [0.08, 1.05]].forEach(([w, h]) => {
            const bar = new THREE.Mesh(new THREE.BoxGeometry(w, h, 0.06), frameMat);
            bar.position.set(x, 2.25, 2.18);
            g.add(bar);
        });
    });

    // 煙突
    const chimney = new THREE.Mesh(new THREE.BoxGeometry(0.6, 1.4, 0.6), mat(0xb1795a, false));
    chimney.position.set(1.3, 4.4, -0.9);
    g.add(chimney);

    return { object: g, radius: 3.0, labelY: 6.0 };
}

/**
 * クイズ広場：八角形の東屋（ガゼボ）
 *
 * 柱・手すり・梁・軒がすべて上下でつながるよう寸法を通してあるので、
 * どこにも「宙に浮いた隙間」ができない。正面の一面だけ手すりを抜いて入口にする。
 */
function buildStage(color) {
    const g = new THREE.Group();

    const SIDES = 8;
    const FLOOR_R = 3.1;     // 床の半径
    const POST_R = 2.78;     // 柱を立てる半径
    const FLOOR_TOP = 0.76;  // 床の上面
    const POST_TOP = 3.5;    // 柱の頭＝梁の下端
    const BEAM_TOP = 3.78;   // 梁の上面＝軒の下端
    const ENTRANCE = 0;      // 手すりを抜く面（入口）

    const stoneMat = mat(0xd9cdb4);
    const woodMat = mat(0xb08a5e, false);
    const postMat = mat(0xf5efe2, false);
    const roofMat = mat(color, false);

    // 石段（2段）→ 床の順に積み、地面から床まで段差なくつなぐ
    const step1 = new THREE.Mesh(new THREE.CylinderGeometry(FLOOR_R + 0.8, FLOOR_R + 1.0, 0.24, SIDES), stoneMat);
    step1.position.y = 0.12;
    g.add(step1);

    const step2 = new THREE.Mesh(new THREE.CylinderGeometry(FLOOR_R + 0.35, FLOOR_R + 0.55, 0.24, SIDES), stoneMat);
    step2.position.y = 0.36;
    g.add(step2);

    const floor = new THREE.Mesh(new THREE.CylinderGeometry(FLOOR_R, FLOOR_R + 0.12, 0.28, SIDES), woodMat);
    floor.position.y = 0.62;
    g.add(floor);

    // 床の中央に色違いの飾り（舞台の目印）
    const inlay = new THREE.Mesh(new THREE.CylinderGeometry(FLOOR_R * 0.6, FLOOR_R * 0.6, 0.06, SIDES), roofMat);
    inlay.position.y = FLOOR_TOP + 0.01;
    g.add(inlay);

    const postH = POST_TOP - FLOOR_TOP;
    const half = Math.PI / SIDES;            // 隣り合う柱の中間角
    const beamLen = 2 * POST_R * Math.sin(half) + 0.26;   // 柱の間をぴったり埋める長さ

    for (let i = 0; i < SIDES; i++) {
        const a = (i / SIDES) * Math.PI * 2;
        const x = Math.cos(a) * POST_R;
        const z = Math.sin(a) * POST_R;

        // 柱：床の上面から梁の下端まで
        const post = new THREE.Mesh(new THREE.CylinderGeometry(0.15, 0.18, postH, 10), postMat);
        post.position.set(x, FLOOR_TOP + postH / 2, z);
        g.add(post);

        // 柱頭の飾り
        const cap = new THREE.Mesh(new THREE.CylinderGeometry(0.22, 0.22, 0.12, 10), postMat);
        cap.position.set(x, POST_TOP - 0.06, z);
        g.add(cap);

        // 柱と柱の間をつなぐ梁（ここが空いていると屋根が浮いて見える）
        const mid = a + half;
        const bx = Math.cos(mid) * POST_R;
        const bz = Math.sin(mid) * POST_R;

        const beam = new THREE.Mesh(new THREE.BoxGeometry(beamLen, 0.28, 0.2), woodMat);
        beam.position.set(bx, POST_TOP + 0.14, bz);
        beam.rotation.y = -mid + Math.PI / 2;
        g.add(beam);

        // 手すり（入口の面だけ省く）：上下の横木＋縦の桟で隙間を埋める
        if (i === ENTRANCE) continue;

        const railTop = new THREE.Mesh(new THREE.BoxGeometry(beamLen, 0.14, 0.16), postMat);
        railTop.position.set(bx, FLOOR_TOP + 1.05, bz);
        railTop.rotation.y = -mid + Math.PI / 2;
        g.add(railTop);

        const railBottom = railTop.clone();
        railBottom.position.y = FLOOR_TOP + 0.22;
        g.add(railBottom);

        for (let k = -1; k <= 1; k++) {
            const t = k * 0.3;
            const sx = bx - Math.sin(mid) * beamLen * t;
            const sz = bz + Math.cos(mid) * beamLen * t;
            const baluster = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.85, 0.1), postMat);
            baluster.position.set(sx, FLOOR_TOP + 0.62, sz);
            baluster.rotation.y = -mid + Math.PI / 2;
            g.add(baluster);
        }
    }

    // 軒（厚みのある縁）→ 屋根。梁の上面から隙間なく立ち上げる。
    const eave = new THREE.Mesh(new THREE.CylinderGeometry(4.05, 4.3, 0.26, SIDES), roofMat);
    eave.position.y = BEAM_TOP + 0.13;
    g.add(eave);

    const roof = new THREE.Mesh(new THREE.ConeGeometry(4.05, 1.75, SIDES), roofMat);
    roof.position.y = BEAM_TOP + 0.26 + 0.875;
    g.add(roof);

    // てっぺんの飾り
    const spire = new THREE.Mesh(new THREE.CylinderGeometry(0.12, 0.16, 0.4, 8), postMat);
    spire.position.y = BEAM_TOP + 2.1;
    g.add(spire);

    const ball = new THREE.Mesh(new THREE.SphereGeometry(0.32, 14, 12), mat(0xf2b44c, false));
    ball.position.y = BEAM_TOP + 2.55;
    g.add(ball);
    g.userData.animate = (t) => {
        ball.position.y = BEAM_TOP + 2.55 + Math.sin(t * 2) * 0.1;
        ball.rotation.y = t;
    };

    return { object: g, radius: 3.2, labelY: 7.4 };
}

/**
 * ランキング展望台：四本脚の物見やぐら
 *
 * 「脚 → 床 → 隅柱 → 桁 → 屋根」と下から順に接して積み上げ、
 * 手すりと腰板で隙間を塞いでいるので、空が透けて見えることがない。
 */
function buildTower(color) {
    const g = new THREE.Group();

    const LEG = 1.35;        // 脚の中心からの距離
    const DECK_Y = 5.0;      // 床の上面
    const RAIL_Y = 6.35;     // 手すりの上端
    const PLATE_Y = 7.25;    // 桁（屋根を載せる梁）の下端
    const ENTRY = 2;         // はしごを掛ける面（+Z 側）

    const stoneMat = mat(0x9c968a);
    const legMat = mat(0x9a7850, false);
    const deckMat = mat(0xc2a172, false);
    const railMat = mat(0xe8dcc4, false);
    const wallMat = mat(color, false);
    const roofMat = mat(new THREE.Color(color).multiplyScalar(0.78), false);

    const corners = [[-LEG, -LEG], [LEG, -LEG], [LEG, LEG], [-LEG, LEG]];

    // 石の沓石 → 脚（床の裏まで届かせる）
    corners.forEach(([x, z]) => {
        const footing = new THREE.Mesh(new THREE.BoxGeometry(0.62, 0.42, 0.62), stoneMat);
        footing.position.set(x, 0.21, z);
        g.add(footing);

        const legH = DECK_Y - 0.3;
        const leg = new THREE.Mesh(new THREE.CylinderGeometry(0.19, 0.26, legH, 8), legMat);
        leg.position.set(x, 0.3 + legH / 2, z);
        g.add(leg);
    });

    // 脚をつなぐ水平の貫と筋交い（構造が抜けて見えるのを防ぐ）
    for (let i = 0; i < 4; i++) {
        const [ax, az] = corners[i];
        const [bx, bz] = corners[(i + 1) % 4];
        const mx = (ax + bx) / 2;
        const mz = (az + bz) / 2;
        const len = Math.hypot(bx - ax, bz - az);
        const angle = Math.atan2(bz - az, bx - ax);

        const tie = new THREE.Mesh(new THREE.BoxGeometry(len, 0.16, 0.14), legMat);
        tie.position.set(mx, 2.4, mz);
        tie.rotation.y = -angle;
        g.add(tie);

        if (i === ENTRY) continue;   // はしご側は筋交いを入れない
        for (const dir of [1, -1]) {
            const brace = new THREE.Mesh(new THREE.BoxGeometry(Math.hypot(len, 2.0), 0.12, 0.1), legMat);
            brace.position.set(mx, 3.4, mz);
            brace.rotation.y = -angle;
            brace.rotation.z = dir * Math.atan2(2.0, len);
            g.add(brace);
        }
    }

    // 床（下面の梁 → 床板の順に重ねる）
    const joist = new THREE.Mesh(new THREE.BoxGeometry(3.5, 0.22, 3.5), legMat);
    joist.position.y = DECK_Y - 0.36;
    g.add(joist);

    const deck = new THREE.Mesh(new THREE.BoxGeometry(4.0, 0.26, 4.0), deckMat);
    deck.position.y = DECK_Y - 0.13;
    g.add(deck);

    // 隅柱：床の上面から桁の下端まで通す（ここが屋根を支える）
    const postH = PLATE_Y - DECK_Y;
    const postR = 1.78;
    const postPos = [[-postR, -postR], [postR, -postR], [postR, postR], [-postR, postR]];
    postPos.forEach(([x, z]) => {
        const post = new THREE.Mesh(new THREE.BoxGeometry(0.22, postH, 0.22), legMat);
        post.position.set(x, DECK_Y + postH / 2, z);
        g.add(post);
    });

    // 腰板＋手すり。4 面のうち 3 面を塞ぎ、はしご側は手すりだけにする。
    for (let i = 0; i < 4; i++) {
        const [ax, az] = postPos[i];
        const [bx, bz] = postPos[(i + 1) % 4];
        const mx = (ax + bx) / 2;
        const mz = (az + bz) / 2;
        const len = Math.hypot(bx - ax, bz - az);
        const angle = Math.atan2(bz - az, bx - ax);

        // 腰板（床から手すりの下まで）
        if (i !== ENTRY) {
            const panel = new THREE.Mesh(new THREE.BoxGeometry(len, 0.95, 0.16), wallMat);
            panel.position.set(mx, DECK_Y + 0.48, mz);
            panel.rotation.y = -angle;
            g.add(panel);
        }

        // 手すりの上端（全周）
        const rail = new THREE.Mesh(new THREE.BoxGeometry(len, 0.16, 0.22), railMat);
        rail.position.set(mx, RAIL_Y - 0.08, mz);
        rail.rotation.y = -angle;
        g.add(rail);

        // 腰板と手すりの間の縦桟
        for (let k = -1; k <= 1; k++) {
            const t = k * 0.28;
            const sx = mx + (bx - ax) * t;
            const sz = mz + (bz - az) * t;
            const baluster = new THREE.Mesh(new THREE.BoxGeometry(0.1, i === ENTRY ? 1.2 : 0.45, 0.1), railMat);
            baluster.position.set(sx, i === ENTRY ? DECK_Y + 0.6 : DECK_Y + 1.18, sz);
            baluster.rotation.y = -angle;
            g.add(baluster);
        }
    }

    // 桁（屋根の受け）→ 軒 → 屋根。下から順に接している。
    const plate = new THREE.Mesh(new THREE.BoxGeometry(3.9, 0.24, 3.9), legMat);
    plate.position.y = PLATE_Y + 0.12;
    g.add(plate);

    const eave = new THREE.Mesh(new THREE.ConeGeometry(3.5, 0.34, 4), roofMat);
    eave.position.y = PLATE_Y + 0.41;
    eave.rotation.y = Math.PI / 4;
    g.add(eave);

    const roof = new THREE.Mesh(new THREE.ConeGeometry(3.3, 1.75, 4), roofMat);
    roof.position.y = PLATE_Y + 0.58 + 0.875;
    roof.rotation.y = Math.PI / 4;
    g.add(roof);

    const finial = new THREE.Mesh(new THREE.SphereGeometry(0.22, 12, 10), mat(0xf2b44c, false));
    finial.position.y = PLATE_Y + 2.55;
    g.add(finial);

    // はしご（2 本の親柱に横木を渡す）
    const ladderMat = mat(0x8a5f3c, false);
    const ladderZ = postR + 0.22;
    for (const side of [-0.45, 0.45]) {
        const rail = new THREE.Mesh(new THREE.BoxGeometry(0.12, DECK_Y + 0.2, 0.12), ladderMat);
        rail.position.set(side, (DECK_Y + 0.2) / 2, ladderZ);
        rail.rotation.x = -0.12;
        g.add(rail);
    }
    for (let i = 0; i < 7; i++) {
        const rung = new THREE.Mesh(new THREE.BoxGeometry(1.0, 0.1, 0.1), ladderMat);
        rung.position.set(0, 0.7 + i * 0.62, ladderZ + 0.06);
        g.add(rung);
    }

    return { object: g, radius: 2.6, labelY: 10.4 };
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

/**
 * じぶんの家：中に入って模様替えできる住まい
 *
 * 玄関前にポーチと踏み段を作って「入る場所」がひと目で分かるようにし、
 * 屋根・壁・土台は上下で必ず接するよう寸法を通してある。
 */
function buildHouse(color) {
    const g = new THREE.Group();

    const wallMat = mat(0xf4e7cd, false);
    const trimMat = mat(0xffffff, false);
    const roofMat = mat(color, false);
    const woodMat = mat(0x9a6f47, false);
    const stoneMat = mat(0xb5ad9e);

    const W = 5.6;   // 間口
    const D = 4.8;   // 奥行き
    const WALL_TOP = 3.2;

    // 石の基礎
    const base = new THREE.Mesh(new THREE.BoxGeometry(W + 0.5, 0.45, D + 0.5), stoneMat);
    base.position.y = 0.22;
    g.add(base);

    // 壁
    const walls = new THREE.Mesh(new THREE.BoxGeometry(W, WALL_TOP - 0.45, D), wallMat);
    walls.position.y = 0.45 + (WALL_TOP - 0.45) / 2;
    g.add(walls);

    // 柱（四隅）と胴差し。面がのっぺりしないよう陰影をつける。
    [[-1, -1], [1, -1], [1, 1], [-1, 1]].forEach(([sx, sz]) => {
        const pillar = new THREE.Mesh(new THREE.BoxGeometry(0.3, WALL_TOP - 0.45, 0.3), woodMat);
        pillar.position.set(sx * (W / 2 - 0.1), 0.45 + (WALL_TOP - 0.45) / 2, sz * (D / 2 - 0.1));
        g.add(pillar);
    });

    const belt = new THREE.Mesh(new THREE.BoxGeometry(W + 0.12, 0.22, D + 0.12), woodMat);
    belt.position.y = 1.75;
    g.add(belt);

    // 軒 → 屋根 → 棟。壁の上端から隙間なく立ち上げる。
    const eave = new THREE.Mesh(new THREE.BoxGeometry(W + 1.5, 0.3, D + 1.5), roofMat);
    eave.position.y = WALL_TOP + 0.15;
    g.add(eave);

    const roof = new THREE.Mesh(new THREE.ConeGeometry(W * 0.78, 2.1, 4), roofMat);
    roof.position.y = WALL_TOP + 0.3 + 1.05;
    roof.rotation.y = Math.PI / 4;
    g.add(roof);

    const ridge = new THREE.Mesh(new THREE.SphereGeometry(0.26, 12, 10), trimMat);
    ridge.position.y = WALL_TOP + 2.5;
    g.add(ridge);

    // 玄関ポーチ（踏み段＋庇）。ここが入口だと分かるようにする。
    const porch = new THREE.Mesh(new THREE.BoxGeometry(2.4, 0.22, 1.0), stoneMat);
    porch.position.set(0, 0.33, D / 2 + 0.5);
    g.add(porch);

    const step = new THREE.Mesh(new THREE.BoxGeometry(2.0, 0.2, 0.6), stoneMat);
    step.position.set(0, 0.1, D / 2 + 1.1);
    g.add(step);

    const doorFrame = new THREE.Mesh(new THREE.BoxGeometry(1.7, 2.5, 0.22), trimMat);
    doorFrame.position.set(0, 1.25 + 0.45, D / 2 + 0.02);
    g.add(doorFrame);

    const door = new THREE.Mesh(new THREE.BoxGeometry(1.35, 2.2, 0.18), woodMat);
    door.position.set(0, 1.1 + 0.45, D / 2 + 0.1);
    g.add(door);

    const knob = new THREE.Mesh(new THREE.SphereGeometry(0.1, 10, 8), mat(0xe0b04a, false));
    knob.position.set(0.48, 1.55, D / 2 + 0.2);
    g.add(knob);

    // 庇（ポーチの上）
    const canopy = new THREE.Mesh(new THREE.BoxGeometry(2.8, 0.2, 1.3), roofMat);
    canopy.position.set(0, 3.05, D / 2 + 0.55);
    g.add(canopy);

    [-1, 1].forEach((side) => {
        const prop = new THREE.Mesh(new THREE.CylinderGeometry(0.09, 0.09, 2.6, 8), trimMat);
        prop.position.set(side * 1.2, 1.7, D / 2 + 1.0);
        g.add(prop);
    });

    // 窓（枠・ガラス・桟・出窓の下板）
    const glassMat = new THREE.MeshStandardMaterial({
        color: 0xa9d6e5, roughness: 0.14, metalness: 0.2,
        emissive: 0x7fa8bd, emissiveIntensity: 0.3,
    });
    [[-2.0, D / 2], [2.0, D / 2]].forEach(([x, z]) => {
        const frame = new THREE.Mesh(new THREE.BoxGeometry(1.3, 1.3, 0.16), trimMat);
        frame.position.set(x, 2.1, z + 0.03);
        g.add(frame);

        const glass = new THREE.Mesh(new THREE.BoxGeometry(1.05, 1.05, 0.12), glassMat);
        glass.position.set(x, 2.1, z + 0.09);
        g.add(glass);

        const barV = new THREE.Mesh(new THREE.BoxGeometry(0.08, 1.05, 0.06), trimMat);
        barV.position.set(x, 2.1, z + 0.15);
        g.add(barV);

        const sill = new THREE.Mesh(new THREE.BoxGeometry(1.5, 0.12, 0.3), trimMat);
        sill.position.set(x, 1.42, z + 0.1);
        g.add(sill);
    });

    // 煙突
    const chimney = new THREE.Mesh(new THREE.BoxGeometry(0.7, 1.8, 0.7), stoneMat);
    chimney.position.set(-1.6, 4.0, -1.2);
    g.add(chimney);

    const chimneyCap = new THREE.Mesh(new THREE.BoxGeometry(0.9, 0.16, 0.9), trimMat);
    chimney.position.y = 4.0;
    chimneyCap.position.set(-1.6, 4.95, -1.2);
    g.add(chimneyCap);

    // 郵便ポスト（家のそばの目印）
    const postPole = new THREE.Mesh(new THREE.CylinderGeometry(0.09, 0.11, 1.2, 8), woodMat);
    postPole.position.set(2.2, 0.6, D / 2 + 1.4);
    g.add(postPole);

    const mailbox = new THREE.Mesh(new THREE.BoxGeometry(0.5, 0.42, 0.7), mat(0xd96f5a, false));
    mailbox.position.set(2.2, 1.4, D / 2 + 1.4);
    g.add(mailbox);

    const mailRoof = new THREE.Mesh(new THREE.CylinderGeometry(0.26, 0.26, 0.72, 10, 1, false, 0, Math.PI), mat(0xd96f5a, false));
    mailRoof.rotation.z = Math.PI / 2;
    mailRoof.rotation.y = Math.PI / 2;
    mailRoof.position.set(2.2, 1.61, 1.4 + D / 2);
    g.add(mailRoof);

    return { object: g, radius: 3.4, labelY: 6.6 };
}

const BUILDERS = {
    bigtree: buildBigTree,
    tent: buildTent,
    lighthouse: buildLighthouse,
    cabin: buildCabin,
    stage: buildStage,
    tower: buildTower,
    board: buildBoard,
    house: buildHouse,
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

        // 施設のパーツはすべて影を落とし、かつ受ける（自分の軒下に影ができる）
        built.object.traverse((child) => {
            if (!child.isMesh) return;
            child.castShadow = true;
            child.receiveShadow = true;
        });

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
