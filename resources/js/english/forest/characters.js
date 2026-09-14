/**
 * 英語の森 – プレイヤーキャラクター（8種類）
 *
 * 「かわいい動物の住人が小さな島を歩く」というコンセプトだけを参考にした
 * オリジナルデザイン。8匹とも同じ二足歩行の骨格（胴体・頭・腕×2・脚×2）
 * を共有しつつ、耳・鼻づら・尻尾・目の形と配色だけを種族ごとに変えることで
 * シルエットをはっきり描き分けている。
 *
 * config/english.php の `characters.*` の body/belly/accent がそのまま
 * 配色として渡ってくる。
 */
import * as THREE from 'three';

// 毛並みはざらついた質感（roughness 高め）にすると、光の当たり方で丸みが出る
const mat = (color, flat = false) => new THREE.MeshStandardMaterial({
    color, flatShading: flat, roughness: 0.82, metalness: 0,
});

/**
 * 全キャラクター共通の骨格（胴体・頭・腕・脚・影）を組み立てる。
 * player.js の歩行アニメーションは、ここで返す body/head/arms/legs/blob を
 * そのまま動かす前提になっている。
 */
function createRig({ body: bodyColor, belly: bellyColor, accent: accentColor }) {
    const group = new THREE.Group();

    const body = new THREE.Mesh(new THREE.CapsuleGeometry(0.44, 0.4, 6, 14), mat(bodyColor));
    body.position.y = 0.78;
    body.castShadow = true;
    group.add(body);

    // お腹まわりの毛色パッチ
    const belly = new THREE.Mesh(new THREE.SphereGeometry(0.34, 12, 10), mat(bellyColor));
    belly.scale.set(0.82, 1.05, 0.5);
    belly.position.set(0, 0.68, 0.29);
    group.add(belly);

    const head = new THREE.Group();
    head.position.y = 1.6;
    group.add(head);

    const skull = new THREE.Mesh(new THREE.SphereGeometry(0.5, 16, 14), mat(bodyColor));
    skull.scale.set(1, 0.94, 0.92);
    skull.castShadow = true;
    head.add(skull);

    const armGeo = new THREE.CapsuleGeometry(0.13, 0.34, 4, 8);
    const arms = [-1, 1].map((side) => {
        const pivot = new THREE.Group();
        pivot.position.set(side * 0.5, 1.1, 0);
        const arm = new THREE.Mesh(armGeo, mat(bodyColor));
        arm.position.y = -0.28;
        arm.castShadow = true;
        const paw = new THREE.Mesh(new THREE.SphereGeometry(0.15, 10, 8), mat(bellyColor));
        paw.position.y = -0.55;
        pivot.add(arm, paw);
        group.add(pivot);
        return pivot;
    });

    const legGeo = new THREE.CapsuleGeometry(0.17, 0.3, 4, 8);
    const legs = [-1, 1].map((side) => {
        const pivot = new THREE.Group();
        pivot.position.set(side * 0.21, 0.5, 0);
        const leg = new THREE.Mesh(legGeo, mat(bodyColor));
        leg.position.y = -0.24;
        leg.castShadow = true;
        const foot = new THREE.Mesh(new THREE.SphereGeometry(0.2, 10, 8), mat(bellyColor));
        foot.scale.set(1, 0.75, 1.3);
        foot.position.set(0, -0.46, 0.05);
        pivot.add(leg, foot);
        group.add(pivot);
        return pivot;
    });

    const blob = new THREE.Mesh(
        new THREE.CircleGeometry(0.55, 18),
        new THREE.MeshBasicMaterial({ color: 0x2e3a4f, transparent: true, opacity: 0.18, depthWrite: false }),
    );
    blob.rotation.x = -Math.PI / 2;
    blob.position.y = 0.03;
    group.add(blob);

    return { group, body, belly, head, skull, arms, legs, blob, colors: { bodyColor, bellyColor, accentColor } };
}

/** 目（黒目）を左右対称に生やす */
function addEyes(head, { y = 0.02, spacing = 0.19, z = 0.44, rx = 0.075, ry = 0.09, color = 0x2c2018 } = {}) {
    const eyeMat = mat(color);
    [-spacing, spacing].forEach((x) => {
        const eye = new THREE.Mesh(new THREE.SphereGeometry(rx, 10, 8), eyeMat);
        eye.position.set(x, y, z);
        eye.scale.set(1, ry / rx, 0.6);
        head.add(eye);
    });
}

/** 鼻先（鼻づらの先端の小さな鼻） */
function addNoseDot(target, { x = 0, y = 0, z = 0, size = 0.05, color = 0x2c2018 } = {}) {
    const nose = new THREE.Mesh(new THREE.SphereGeometry(size, 8, 6), mat(color));
    nose.position.set(x, y, z);
    target.add(nose);
    return nose;
}

// ─────────────────────────────────────────────────────────────────────────
// 種族ごとの見た目
// ─────────────────────────────────────────────────────────────────────────

/** きつね：三角の耳、伸びた鼻づら、ふさふさの尻尾 */
function buildFox(colors) {
    const rig = createRig(colors);
    const { head, group } = rig;
    const { bodyColor, bellyColor, accentColor } = rig.colors;

    [-1, 1].forEach((side) => {
        const ear = new THREE.Mesh(new THREE.ConeGeometry(0.16, 0.34, 4), mat(bodyColor));
        ear.position.set(side * 0.26, 0.42, -0.02);
        ear.rotation.z = side * -0.18;
        ear.rotation.y = Math.PI / 4;
        head.add(ear);
        const inner = new THREE.Mesh(new THREE.ConeGeometry(0.09, 0.2, 4), mat(accentColor));
        inner.position.set(side * 0.26, 0.36, 0.05);
        inner.rotation.copy(ear.rotation);
        head.add(inner);
    });

    const snout = new THREE.Mesh(new THREE.ConeGeometry(0.22, 0.5, 10), mat(bellyColor));
    snout.rotation.x = Math.PI / 2;
    snout.position.set(0, -0.1, 0.55);
    head.add(snout);
    addNoseDot(head, { y: -0.1, z: 0.82, size: 0.06, color: accentColor });
    addEyes(head, { y: 0.05, spacing: 0.2, z: 0.42 });

    // 尻尾：首の後ろから背中に沿ってふさふさと垂れる
    const tail = new THREE.Group();
    tail.position.set(0, 0.65, -0.65);
    const tailBase = new THREE.Mesh(new THREE.ConeGeometry(0.24, 0.85, 8), mat(bodyColor));
    tailBase.position.y = 0.42;
    tail.add(tailBase);
    const tailTip = new THREE.Mesh(new THREE.SphereGeometry(0.16, 8, 7), mat(bellyColor));
    tailTip.position.y = 0.06;
    tail.add(tailTip);
    group.add(tail);

    return rig;
}

/** ねこ：三角の耳、丸い顔、くるんとした尻尾 */
function buildCat(colors) {
    const rig = createRig(colors);
    const { head, group } = rig;
    const { bodyColor, accentColor } = rig.colors;

    [-1, 1].forEach((side) => {
        const ear = new THREE.Mesh(new THREE.ConeGeometry(0.17, 0.26, 4), mat(bodyColor));
        ear.position.set(side * 0.28, 0.4, -0.02);
        ear.rotation.y = Math.PI / 4;
        head.add(ear);
        const inner = new THREE.Mesh(new THREE.ConeGeometry(0.09, 0.15, 4), mat(accentColor));
        inner.position.set(side * 0.28, 0.35, 0.04);
        inner.rotation.copy(ear.rotation);
        head.add(inner);
    });

    addEyes(head, { y: 0.02, spacing: 0.19, z: 0.45 });
    addNoseDot(head, { y: -0.12, z: 0.5, size: 0.045, color: accentColor });

    // ひげ
    const whiskerMat = mat(accentColor);
    [-1, 1].forEach((side) => {
        for (let i = 0; i < 2; i++) {
            const whisker = new THREE.Mesh(new THREE.CylinderGeometry(0.006, 0.006, 0.3, 4), whiskerMat);
            whisker.rotation.z = Math.PI / 2;
            whisker.rotation.y = side * (0.25 + i * 0.12);
            whisker.position.set(side * 0.22, -0.1 - i * 0.05, 0.42);
            head.add(whisker);
        }
    });

    // 尻尾：首の後ろから垂れて、先端がくるんとカーブする
    const tail = new THREE.Group();
    tail.position.set(0, 0.65, -0.6);
    const seg1 = new THREE.Mesh(new THREE.CapsuleGeometry(0.09, 0.5, 4, 8), mat(bodyColor));
    seg1.position.y = 0.35;
    tail.add(seg1);
    const seg2 = new THREE.Mesh(new THREE.CapsuleGeometry(0.075, 0.28, 4, 8), mat(bodyColor));
    seg2.rotation.z = 1.0;
    seg2.position.set(0.09, -0.02, 0);
    tail.add(seg2);
    group.add(tail);

    return rig;
}

/** くま：丸い耳、大きな鼻づら、がっしり体型 */
function buildBear(colors) {
    const rig = createRig(colors);
    const { head } = rig;
    const { bodyColor, bellyColor, accentColor } = rig.colors;

    rig.head.scale.set(1.08, 1.05, 1.05);

    [-1, 1].forEach((side) => {
        const ear = new THREE.Mesh(new THREE.SphereGeometry(0.15, 12, 10), mat(bodyColor));
        ear.position.set(side * 0.36, 0.36, -0.02);
        head.add(ear);
        const inner = new THREE.Mesh(new THREE.SphereGeometry(0.08, 10, 8), mat(accentColor));
        inner.position.set(side * 0.36, 0.36, 0.07);
        head.add(inner);
    });

    const snout = new THREE.Mesh(new THREE.SphereGeometry(0.22, 12, 10), mat(bellyColor));
    snout.scale.set(1, 0.8, 0.85);
    snout.position.set(0, -0.14, 0.42);
    head.add(snout);
    addNoseDot(head, { y: -0.1, z: 0.62, size: 0.07, color: accentColor });
    addEyes(head, { y: 0.06, spacing: 0.21, z: 0.42, rx: 0.065 });

    return rig;
}

/** うさぎ：長い耳、ふわふわの尻尾 */
function buildRabbit(colors) {
    const rig = createRig(colors);
    const { head, group } = rig;
    const { bodyColor, bellyColor } = rig.colors;

    [-1, 1].forEach((side) => {
        const ear = new THREE.Group();
        ear.position.set(side * 0.2, 0.42, 0);
        ear.rotation.z = side * -0.12;
        const outer = new THREE.Mesh(new THREE.CapsuleGeometry(0.1, 0.5, 4, 8), mat(bodyColor));
        outer.position.y = 0.35;
        ear.add(outer);
        const inner = new THREE.Mesh(new THREE.CapsuleGeometry(0.055, 0.36, 4, 8), mat(bellyColor));
        inner.position.set(0, 0.37, 0.06);
        ear.add(inner);
        head.add(ear);
    });

    addEyes(head, { y: 0.03, spacing: 0.19, z: 0.44 });
    addNoseDot(head, { y: -0.14, z: 0.5, size: 0.045, color: '#d98a9c' });

    const tail = new THREE.Mesh(new THREE.SphereGeometry(0.16, 10, 8), mat(bellyColor));
    tail.position.set(0, 0.82, -0.42);
    group.add(tail);

    return rig;
}

/** たぬき：目のまわりのマスク模様、しま模様の尻尾 */
function buildRaccoon(colors) {
    const rig = createRig(colors);
    const { head, group } = rig;
    const { bodyColor, bellyColor, accentColor } = rig.colors;

    [-1, 1].forEach((side) => {
        const ear = new THREE.Mesh(new THREE.SphereGeometry(0.13, 10, 8), mat(accentColor));
        ear.position.set(side * 0.3, 0.38, -0.02);
        head.add(ear);
    });

    const mask = new THREE.Mesh(new THREE.TorusGeometry(0.28, 0.11, 8, 16, Math.PI), mat(accentColor));
    mask.rotation.z = Math.PI;
    mask.position.set(0, 0.06, 0.4);
    head.add(mask);

    addEyes(head, { y: 0.05, spacing: 0.2, z: 0.46, color: '#fdfbf5' });
    addEyes(head, { y: 0.05, spacing: 0.2, z: 0.5, rx: 0.035, color: '#2c2018' });
    addNoseDot(head, { y: -0.14, z: 0.5, size: 0.05, color: '#2c2018' });

    // 尻尾：首の後ろから垂れる、しま模様のふさふさした尻尾
    const tail = new THREE.Group();
    tail.position.set(0, 0.65, -0.65);
    for (let i = 0; i < 4; i++) {
        const ring = new THREE.Mesh(
            new THREE.CylinderGeometry(0.17 - i * 0.015, 0.19 - i * 0.015, 0.22, 8),
            mat(i % 2 === 0 ? accentColor : bodyColor),
        );
        ring.position.y = i * 0.2; // y が大きいほど付け根（頭側）、小さいほど垂れた先端側
        tail.add(ring);
    }
    const tailTip = new THREE.Mesh(new THREE.SphereGeometry(0.12, 8, 7), mat(accentColor));
    tailTip.position.y = -0.1;
    tail.add(tailTip);
    group.add(tail);

    return rig;
}

/** パンダ：黒い耳と目のまわりのパッチ */
function buildPanda(colors) {
    const rig = createRig(colors);
    const { head } = rig;
    const { accentColor } = rig.colors;

    [-1, 1].forEach((side) => {
        const ear = new THREE.Mesh(new THREE.SphereGeometry(0.15, 12, 10), mat(accentColor));
        ear.position.set(side * 0.34, 0.38, -0.02);
        head.add(ear);
    });

    [-1, 1].forEach((side) => {
        const patch = new THREE.Mesh(new THREE.SphereGeometry(0.15, 10, 8), mat(accentColor));
        patch.scale.set(0.9, 1.1, 0.55);
        patch.position.set(side * 0.2, 0.02, 0.4);
        patch.rotation.z = side * -0.15;
        head.add(patch);
    });

    addEyes(head, { y: 0.02, spacing: 0.2, z: 0.48, color: '#fdfbf5' });
    addEyes(head, { y: 0.0, spacing: 0.2, z: 0.52, rx: 0.045, color: '#1c1712' });
    addNoseDot(head, { y: -0.14, z: 0.52, size: 0.05, color: accentColor });

    return rig;
}

/** ふくろう：大きな目、羽の耳、くちばし */
function buildOwl(colors) {
    const rig = createRig(colors);
    const { head } = rig;
    const { bodyColor, bellyColor, accentColor } = rig.colors;

    [-1, 1].forEach((side) => {
        const tuft = new THREE.Mesh(new THREE.ConeGeometry(0.09, 0.24, 4), mat(bodyColor));
        tuft.position.set(side * 0.22, 0.46, -0.05);
        tuft.rotation.z = side * 0.3;
        head.add(tuft);
    });

    [-1, 1].forEach((side) => {
        const disk = new THREE.Mesh(new THREE.CircleGeometry(0.19, 16), mat(bellyColor));
        disk.position.set(side * 0.2, 0.02, 0.44);
        head.add(disk);
    });
    addEyes(head, { y: 0.02, spacing: 0.2, z: 0.5, rx: 0.09, ry: 0.09 });

    const beak = new THREE.Mesh(new THREE.ConeGeometry(0.07, 0.18, 6), mat(accentColor));
    beak.rotation.x = Math.PI / 2;
    beak.position.set(0, -0.14, 0.5);
    head.add(beak);

    return rig;
}

/** かえる：頭の上に飛び出た目、大きな口 */
function buildFrog(colors) {
    const rig = createRig(colors);
    const { head } = rig;
    const { bodyColor, bellyColor, accentColor } = rig.colors;

    rig.head.scale.set(1.05, 0.9, 1.05);

    [-1, 1].forEach((side) => {
        const stalk = new THREE.Mesh(new THREE.CylinderGeometry(0.08, 0.1, 0.14, 8), mat(bodyColor));
        stalk.position.set(side * 0.2, 0.4, 0.12);
        head.add(stalk);
        const eye = new THREE.Mesh(new THREE.SphereGeometry(0.11, 10, 8), mat('#fdfbf5'));
        eye.position.set(side * 0.2, 0.5, 0.15);
        head.add(eye);
        const pupil = new THREE.Mesh(new THREE.SphereGeometry(0.05, 8, 6), mat('#2c2018'));
        pupil.position.set(side * 0.2, 0.5, 0.24);
        head.add(pupil);
    });

    const mouth = new THREE.Mesh(new THREE.TorusGeometry(0.22, 0.03, 6, 12, Math.PI), mat(accentColor));
    mouth.rotation.z = Math.PI;
    mouth.position.set(0, -0.08, 0.44);
    head.add(mouth);

    const cheek = new THREE.Mesh(new THREE.SphereGeometry(0.26, 12, 10), mat(bellyColor));
    cheek.scale.set(1, 0.7, 0.6);
    cheek.position.set(0, -0.18, 0.32);
    head.add(cheek);

    return rig;
}

const BUILDERS = {
    fox: buildFox,
    cat: buildCat,
    bear: buildBear,
    rabbit: buildRabbit,
    raccoon: buildRaccoon,
    panda: buildPanda,
    owl: buildOwl,
    frog: buildFrog,
};

/**
 * キャラクター情報（config/english.php の characters.* 相当）から
 * 3D モデルの骨格を組み立てる。未知の key は狐にフォールバックする。
 */
export function buildCharacter(character = {}) {
    const colors = {
        body: character.body || '#e08a4f',
        belly: character.belly || '#fbe9d7',
        accent: character.accent || '#6b4226',
    };
    const builder = BUILDERS[character.key] || buildFox;
    return builder(colors);
}
