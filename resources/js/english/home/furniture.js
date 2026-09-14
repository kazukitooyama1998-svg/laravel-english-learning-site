/**
 * じぶんの家 – 家具
 *
 * config/english.php の `furniture` に対応する 3D モデル。
 * すべて原点（床の中心）を基準に置き、上へ伸びる形で組む。
 * 模様替えで選択したときに光らせられるよう、マテリアルは家具ごとに複製する。
 */
import * as THREE from 'three';

const mat = (color, opts = {}) => new THREE.MeshStandardMaterial({
    color, roughness: 0.82, metalness: 0, ...opts,
});

const BOOK_COLORS = [0xb4483a, 0x3d5a80, 0x6f8154, 0xc98f3c, 0x75688f, 0x9c6070];

/** ベッド：フレーム・マットレス・掛け布団・まくら */
function buildBed(color) {
    const g = new THREE.Group();
    const frame = mat(0x8a5f3c);
    const sheet = mat(0xfdfaf2);
    const cover = mat(color);

    const base = new THREE.Mesh(new THREE.BoxGeometry(1.9, 0.34, 2.9), frame);
    base.position.y = 0.25;
    g.add(base);

    [[-0.85, -1.35], [0.85, -1.35], [-0.85, 1.35], [0.85, 1.35]].forEach(([x, z]) => {
        const leg = new THREE.Mesh(new THREE.BoxGeometry(0.16, 0.2, 0.16), frame);
        leg.position.set(x, 0.1, z);
        g.add(leg);
    });

    const headboard = new THREE.Mesh(new THREE.BoxGeometry(1.9, 0.9, 0.16), frame);
    headboard.position.set(0, 0.75, -1.45);
    g.add(headboard);

    const mattress = new THREE.Mesh(new THREE.BoxGeometry(1.78, 0.26, 2.76), sheet);
    mattress.position.y = 0.55;
    g.add(mattress);

    const blanket = new THREE.Mesh(new THREE.BoxGeometry(1.82, 0.16, 1.9), cover);
    blanket.position.set(0, 0.72, 0.45);
    g.add(blanket);

    const pillow = new THREE.Mesh(new THREE.BoxGeometry(1.2, 0.2, 0.5), sheet);
    pillow.position.set(0, 0.76, -1.05);
    g.add(pillow);

    return g;
}

/** つくえ：天板・脚・引き出し */
function buildDesk(color) {
    const g = new THREE.Group();
    const wood = mat(color);
    const dark = mat(new THREE.Color(color).multiplyScalar(0.8));

    const top = new THREE.Mesh(new THREE.BoxGeometry(2.1, 0.1, 1.0), wood);
    top.position.y = 0.78;
    g.add(top);

    [[-0.95, -0.42], [0.95, -0.42], [-0.95, 0.42], [0.95, 0.42]].forEach(([x, z]) => {
        const leg = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.78, 0.1), dark);
        leg.position.set(x, 0.39, z);
        g.add(leg);
    });

    const drawer = new THREE.Mesh(new THREE.BoxGeometry(0.75, 0.42, 0.85), wood);
    drawer.position.set(0.6, 0.5, 0);
    g.add(drawer);

    const handle = new THREE.Mesh(new THREE.BoxGeometry(0.28, 0.06, 0.06), mat(0xd8c48a, { metalness: 0.3, roughness: 0.4 }));
    handle.position.set(0.6, 0.5, 0.45);
    g.add(handle);

    // 机の上のノートとペン立て
    const book = new THREE.Mesh(new THREE.BoxGeometry(0.4, 0.05, 0.3), mat(0xfdfaf2));
    book.position.set(-0.4, 0.85, 0);
    book.rotation.y = 0.2;
    g.add(book);

    const cup = new THREE.Mesh(new THREE.CylinderGeometry(0.09, 0.08, 0.2, 12), mat(0x7fa8c9));
    cup.position.set(-0.85, 0.93, -0.25);
    g.add(cup);

    return g;
}

/** いす */
function buildChair(color) {
    const g = new THREE.Group();
    const wood = mat(color);

    const seat = new THREE.Mesh(new THREE.BoxGeometry(0.72, 0.1, 0.72), wood);
    seat.position.y = 0.46;
    g.add(seat);

    const back = new THREE.Mesh(new THREE.BoxGeometry(0.72, 0.72, 0.09), wood);
    back.position.set(0, 0.82, -0.32);
    g.add(back);

    [[-0.3, -0.3], [0.3, -0.3], [-0.3, 0.3], [0.3, 0.3]].forEach(([x, z]) => {
        const leg = new THREE.Mesh(new THREE.BoxGeometry(0.08, 0.46, 0.08), wood);
        leg.position.set(x, 0.23, z);
        g.add(leg);
    });

    return g;
}

/** 本だな：棚板と背表紙 */
function buildBookshelf(color) {
    const g = new THREE.Group();
    const wood = mat(color);
    const back = mat(new THREE.Color(color).multiplyScalar(0.75));

    const body = new THREE.Mesh(new THREE.BoxGeometry(1.9, 1.9, 0.12), back);
    body.position.set(0, 0.95, -0.22);
    g.add(body);

    [-0.93, 0.93].forEach((x) => {
        const side = new THREE.Mesh(new THREE.BoxGeometry(0.1, 1.9, 0.55), wood);
        side.position.set(x, 0.95, 0);
        g.add(side);
    });

    for (let i = 0; i < 4; i++) {
        const shelf = new THREE.Mesh(new THREE.BoxGeometry(1.9, 0.08, 0.55), wood);
        shelf.position.set(0, 0.1 + i * 0.58, 0);
        g.add(shelf);

        // 本を並べる
        let x = -0.8;
        while (x < 0.75) {
            const w = 0.07 + (i * 7 + x * 13) % 0.06;
            const h = 0.32 + ((i * 3 + x * 11) % 0.12);
            const book = new THREE.Mesh(
                new THREE.BoxGeometry(w, h, 0.38),
                mat(BOOK_COLORS[Math.abs(Math.round((x * 10 + i * 3))) % BOOK_COLORS.length]),
            );
            book.position.set(x + w / 2, 0.14 + i * 0.58 + h / 2, 0);
            g.add(book);
            x += w + 0.015;
        }
    }

    const top = new THREE.Mesh(new THREE.BoxGeometry(2.0, 0.1, 0.6), wood);
    top.position.y = 1.93;
    g.add(top);

    return g;
}

/** まるいテーブル */
function buildTable(color) {
    const g = new THREE.Group();
    const wood = mat(color);

    const top = new THREE.Mesh(new THREE.CylinderGeometry(0.78, 0.78, 0.1, 24), wood);
    top.position.y = 0.72;
    g.add(top);

    const pole = new THREE.Mesh(new THREE.CylinderGeometry(0.12, 0.16, 0.68, 12), wood);
    pole.position.y = 0.36;
    g.add(pole);

    const foot = new THREE.Mesh(new THREE.CylinderGeometry(0.45, 0.5, 0.08, 16), wood);
    foot.position.y = 0.04;
    g.add(foot);

    // 花びん
    const vase = new THREE.Mesh(new THREE.CylinderGeometry(0.08, 0.12, 0.26, 12), mat(0xd8e6f2));
    vase.position.y = 0.9;
    g.add(vase);

    const flowerMat = mat(0xe8756b);
    for (let i = 0; i < 4; i++) {
        const a = (i / 4) * Math.PI * 2;
        const bloom = new THREE.Mesh(new THREE.SphereGeometry(0.07, 8, 6), flowerMat);
        bloom.position.set(Math.cos(a) * 0.07, 1.12, Math.sin(a) * 0.07);
        g.add(bloom);
    }

    return g;
}

/** ソファ */
function buildSofa(color) {
    const g = new THREE.Group();
    const fabric = mat(color, { roughness: 0.95 });
    const cushion = mat(new THREE.Color(color).lerp(new THREE.Color(0xffffff), 0.18), { roughness: 0.95 });

    const base = new THREE.Mesh(new THREE.BoxGeometry(2.5, 0.4, 1.1), fabric);
    base.position.y = 0.32;
    g.add(base);

    const back = new THREE.Mesh(new THREE.BoxGeometry(2.5, 0.75, 0.28), fabric);
    back.position.set(0, 0.75, -0.41);
    g.add(back);

    [-1.11, 1.11].forEach((x) => {
        const arm = new THREE.Mesh(new THREE.BoxGeometry(0.28, 0.55, 1.1), fabric);
        arm.position.set(x, 0.6, 0);
        g.add(arm);
    });

    [-0.6, 0.6].forEach((x) => {
        const seat = new THREE.Mesh(new THREE.BoxGeometry(1.1, 0.2, 0.95), cushion);
        seat.position.set(x, 0.6, 0.05);
        g.add(seat);
    });

    [[-0.9, -0.45], [0.9, -0.45], [-0.9, 0.45], [0.9, 0.45]].forEach(([x, z]) => {
        const leg = new THREE.Mesh(new THREE.CylinderGeometry(0.06, 0.05, 0.14, 8), mat(0x6b4a2f));
        leg.position.set(x, 0.07, z);
        g.add(leg);
    });

    return g;
}

/** ラグ（床に敷く） */
function buildRug(color) {
    const g = new THREE.Group();

    const base = new THREE.Mesh(new THREE.CylinderGeometry(1.45, 1.45, 0.04, 32), mat(color, { roughness: 1 }));
    base.scale.z = 0.74;
    base.position.y = 0.02;
    g.add(base);

    const inner = new THREE.Mesh(
        new THREE.CylinderGeometry(1.15, 1.15, 0.05, 32),
        mat(new THREE.Color(color).lerp(new THREE.Color(0xfffdf7), 0.45), { roughness: 1 }),
    );
    inner.scale.z = 0.74;
    inner.position.y = 0.03;
    g.add(inner);

    return g;
}

/** かんようしょくぶつ */
function buildPlant(color) {
    const g = new THREE.Group();

    const pot = new THREE.Mesh(new THREE.CylinderGeometry(0.28, 0.22, 0.4, 14), mat(0xc07d56));
    pot.position.y = 0.2;
    g.add(pot);

    const soil = new THREE.Mesh(new THREE.CylinderGeometry(0.25, 0.25, 0.05, 14), mat(0x4a3526));
    soil.position.y = 0.4;
    g.add(soil);

    const leafMat = mat(color, { roughness: 0.7 });
    for (let i = 0; i < 7; i++) {
        const a = (i / 7) * Math.PI * 2;
        const lean = 0.35 + (i % 3) * 0.12;
        const stem = new THREE.Mesh(new THREE.CylinderGeometry(0.02, 0.03, 0.7, 6), mat(0x5c7a3a));
        stem.position.set(Math.cos(a) * 0.1, 0.7, Math.sin(a) * 0.1);
        stem.rotation.z = Math.cos(a) * lean * 0.5;
        stem.rotation.x = -Math.sin(a) * lean * 0.5;
        g.add(stem);

        const leaf = new THREE.Mesh(new THREE.SphereGeometry(0.26, 10, 8), leafMat);
        leaf.scale.set(1, 0.28, 0.55);
        leaf.position.set(Math.cos(a) * 0.34, 1.02, Math.sin(a) * 0.34);
        leaf.rotation.y = -a;
        leaf.rotation.z = -lean;
        g.add(leaf);
    }

    return g;
}

/** フロアランプ（あかりが灯る） */
function buildLamp(color) {
    const g = new THREE.Group();

    const base = new THREE.Mesh(new THREE.CylinderGeometry(0.26, 0.3, 0.07, 16), mat(0x5c6675, { metalness: 0.3, roughness: 0.5 }));
    base.position.y = 0.035;
    g.add(base);

    const pole = new THREE.Mesh(new THREE.CylinderGeometry(0.035, 0.035, 1.5, 8), mat(0x8d8b83, { metalness: 0.4, roughness: 0.4 }));
    pole.position.y = 0.78;
    g.add(pole);

    const shade = new THREE.Mesh(
        new THREE.CylinderGeometry(0.3, 0.42, 0.42, 16, 1, true),
        new THREE.MeshStandardMaterial({
            color, roughness: 0.8, side: THREE.DoubleSide,
            emissive: new THREE.Color(color), emissiveIntensity: 0.55,
        }),
    );
    shade.position.y = 1.65;
    g.add(shade);

    const bulb = new THREE.Mesh(
        new THREE.SphereGeometry(0.12, 12, 10),
        new THREE.MeshStandardMaterial({ color: 0xfff3d0, emissive: 0xffe6a8, emissiveIntensity: 2.2 }),
    );
    bulb.position.y = 1.6;
    g.add(bulb);

    const light = new THREE.PointLight(0xffd9a0, 6, 6, 2);
    light.position.y = 1.6;
    g.add(light);

    return g;
}

/** 置き時計（背の高い振り子時計） */
function buildClock(color) {
    const g = new THREE.Group();
    const wood = mat(color);

    const body = new THREE.Mesh(new THREE.BoxGeometry(0.62, 1.8, 0.3), wood);
    body.position.y = 0.9;
    g.add(body);

    const top = new THREE.Mesh(new THREE.BoxGeometry(0.72, 0.12, 0.38), wood);
    top.position.y = 1.86;
    g.add(top);

    const face = new THREE.Mesh(new THREE.CylinderGeometry(0.22, 0.22, 0.06, 20), mat(0xfdfaf2));
    face.rotation.x = Math.PI / 2;
    face.position.set(0, 1.45, 0.16);
    g.add(face);

    const hourHand = new THREE.Mesh(new THREE.BoxGeometry(0.03, 0.12, 0.02), mat(0x2e3a4f));
    hourHand.position.set(0, 1.49, 0.2);
    g.add(hourHand);

    const minuteHand = new THREE.Mesh(new THREE.BoxGeometry(0.02, 0.17, 0.02), mat(0x2e3a4f));
    minuteHand.position.set(0.05, 1.47, 0.2);
    minuteHand.rotation.z = 1.1;
    g.add(minuteHand);

    const pendulum = new THREE.Mesh(new THREE.CylinderGeometry(0.1, 0.1, 0.03, 14), mat(0xd8c48a, { metalness: 0.4, roughness: 0.35 }));
    pendulum.rotation.x = Math.PI / 2;
    pendulum.position.set(0, 0.6, 0.16);
    g.add(pendulum);

    return g;
}

/** ちきゅうぎ */
function buildGlobe(color) {
    const g = new THREE.Group();

    const foot = new THREE.Mesh(new THREE.CylinderGeometry(0.22, 0.26, 0.08, 16), mat(0x6b4a2f));
    foot.position.y = 0.04;
    g.add(foot);

    const pole = new THREE.Mesh(new THREE.CylinderGeometry(0.03, 0.03, 0.5, 8), mat(0x8d8b83, { metalness: 0.4 }));
    pole.position.y = 0.3;
    g.add(pole);

    const ball = new THREE.Mesh(new THREE.SphereGeometry(0.28, 20, 16), mat(color));
    ball.position.y = 0.78;
    ball.rotation.z = 0.4;
    g.add(ball);

    // 大陸をそれらしく貼る
    const landMat = mat(0x7fa65c);
    [[0.2, 0.1, 0.2], [-0.15, 0.35, 0.1], [0.1, -0.2, -0.2]].forEach(([x, y, z], i) => {
        const land = new THREE.Mesh(new THREE.SphereGeometry(0.29, 12, 10, 0, 1.1 + i * 0.3, 0.6, 0.9), landMat);
        land.position.y = 0.78;
        land.rotation.set(x * 3, y * 3, z * 3);
        g.add(land);
    });

    return g;
}

/** たんす */
function buildChest(color) {
    const g = new THREE.Group();
    const wood = mat(color);
    const drawerMat = mat(new THREE.Color(color).lerp(new THREE.Color(0xffffff), 0.15));
    const handleMat = mat(0xd8c48a, { metalness: 0.35, roughness: 0.4 });

    const body = new THREE.Mesh(new THREE.BoxGeometry(1.5, 1.0, 0.7), wood);
    body.position.y = 0.55;
    g.add(body);

    for (let i = 0; i < 3; i++) {
        const drawer = new THREE.Mesh(new THREE.BoxGeometry(1.32, 0.26, 0.06), drawerMat);
        drawer.position.set(0, 0.22 + i * 0.32, 0.36);
        g.add(drawer);

        const handle = new THREE.Mesh(new THREE.BoxGeometry(0.3, 0.05, 0.05), handleMat);
        handle.position.set(0, 0.22 + i * 0.32, 0.42);
        g.add(handle);
    }

    const top = new THREE.Mesh(new THREE.BoxGeometry(1.6, 0.08, 0.78), wood);
    top.position.y = 1.09;
    g.add(top);

    [[-0.65, -0.28], [0.65, -0.28], [-0.65, 0.28], [0.65, 0.28]].forEach(([x, z]) => {
        const leg = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.1, 0.1), mat(0x6b4a2f));
        leg.position.set(x, 0.05, z);
        g.add(leg);
    });

    return g;
}

const BUILDERS = {
    bed: buildBed,
    desk: buildDesk,
    chair: buildChair,
    bookshelf: buildBookshelf,
    table: buildTable,
    sofa: buildSofa,
    rug: buildRug,
    plant: buildPlant,
    lamp: buildLamp,
    clock: buildClock,
    globe: buildGlobe,
    chest: buildChest,
};

/**
 * 家具を 1 つ組み立てる。
 * @param {string} key  furniture カタログのキー
 * @param {object} def  { kind, color, w, d, name }
 */
export function buildFurniture(key, def) {
    const builder = BUILDERS[def.kind] ?? buildChest;
    const object = builder(new THREE.Color(def.color ?? '#a1815f'));

    object.traverse((child) => {
        if (!child.isMesh) return;
        child.castShadow = true;
        child.receiveShadow = true;
    });

    object.userData.furnitureKey = key;
    return object;
}

/** 模様替えで選択中の家具を光らせる／戻す */
export function setHighlight(object, on) {
    object.traverse((child) => {
        if (!child.isMesh || !child.material) return;

        if (on) {
            if (child.userData.baseEmissive === undefined) {
                child.userData.baseEmissive = child.material.emissiveIntensity ?? 0;
                child.material = child.material.clone();
            }
            child.material.emissive = new THREE.Color(0x4fa3d1);
            child.material.emissiveIntensity = 0.55;
        } else if (child.userData.baseEmissive !== undefined) {
            child.material.emissive = new THREE.Color(0x000000);
            child.material.emissiveIntensity = child.userData.baseEmissive;
        }
    });
}
