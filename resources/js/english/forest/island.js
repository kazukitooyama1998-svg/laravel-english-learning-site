/**
 * 英語の森 – 島の地形
 *
 * 「浜辺（砂）」「芝生の台地」「海」の 3 層でどうぶつの森風の小島を作る。
 * 輪郭は sin 波を重ねた半径関数で決まるので、真円ではない自然な形になる。
 * 3D モデルは全て three.js のプリミティブから生成する（外部アセットなし）。
 */
import * as THREE from 'three';

export const ISLAND = {
    grassRadius: 30,   // 芝生の基準半径
    sandWidth:   6.5,  // 浜辺の幅
    grassTop:    1.5,  // 芝生の高さ（ワールド Y）
    sandTop:     0.2,  // 砂浜の高さ
    slope:       2.2,  // 芝生のふち（斜面）の幅
    waterY:     -0.35, // 海面
};

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

/** 半径関数から THREE.Shape を作る（押し出し後にワールド XZ 平面へ寝かせる前提）。 */
function shapeFromRadius(radiusFn, segments = 160, scale = 1) {
    const shape = new THREE.Shape();
    for (let i = 0; i <= segments; i++) {
        const t = (i / segments) * Math.PI * 2;
        const r = radiusFn(t) * scale;
        // 回転 -90°(X) で (x, y) → (x, 0, -y) になるため、z 座標は符号を反転させておく
        const px = Math.cos(t) * r;
        const py = -Math.sin(t) * r;
        if (i === 0) shape.moveTo(px, py);
        else shape.lineTo(px, py);
    }
    shape.closePath();
    return shape;
}

/** 押し出したジオメトリを XZ 平面に寝かせ、上面が topY になるよう配置する。 */
function layerMesh(shape, { depth, bevelSize, bevelThickness, color, topY }) {
    const geo = new THREE.ExtrudeGeometry(shape, {
        depth,
        bevelEnabled: bevelSize > 0,
        bevelSize,
        bevelThickness,
        bevelSegments: 2,
        curveSegments: 4,
    });
    geo.rotateX(-Math.PI / 2);
    geo.computeVertexNormals();

    const mesh = new THREE.Mesh(geo, new THREE.MeshLambertMaterial({ color, flatShading: false }));
    mesh.position.y = topY - depth - bevelThickness;
    mesh.receiveShadow = true;
    return mesh;
}

export class Island {
    constructor(scene) {
        this.scene = scene;
        this.group = new THREE.Group();
        scene.add(this.group);

        this._buildSand();
        this._buildGrass();
        this._buildWater();
        this._buildFoam();
        this._buildPond();
    }

    _buildSand() {
        const mesh = layerMesh(shapeFromRadius(sandRadiusAt), {
            depth: 1.2,
            bevelSize: 1.6,
            bevelThickness: 0.5,
            color: 0xecdcb4,
            topY: ISLAND.sandTop,
        });
        this.group.add(mesh);
    }

    _buildGrass() {
        const mesh = layerMesh(shapeFromRadius(grassRadiusAt), {
            depth: 0.9,
            bevelSize: ISLAND.slope,
            bevelThickness: 0.95,
            color: 0x7fa65c,
            topY: ISLAND.grassTop,
        });
        this.group.add(mesh);

        // ふちの土（崖）をわずかに覗かせる茶色いリング
        const dirt = layerMesh(shapeFromRadius(grassRadiusAt, 160, 1.005), {
            depth: 0.5,
            bevelSize: 0.4,
            bevelThickness: 0.3,
            color: 0xb79a6d,
            topY: ISLAND.grassTop - 0.55,
        });
        this.group.add(dirt);
    }

    _buildWater() {
        const geo = new THREE.PlaneGeometry(600, 600, 48, 48);
        geo.rotateX(-Math.PI / 2);
        this.waterGeo = geo;
        this.waterBase = Float32Array.from(geo.attributes.position.array);

        const mat = new THREE.MeshLambertMaterial({ color: 0x3f9ec4 });
        this.water = new THREE.Mesh(geo, mat);
        this.water.position.y = ISLAND.waterY;
        this.group.add(this.water);

        // 島のまわりだけ明るい浅瀬（エメラルドの縁取り）
        const shallow = new THREE.Mesh(
            new THREE.ShapeGeometry(shapeFromRadius(sandRadiusAt, 120, 1.5)),
            new THREE.MeshBasicMaterial({ color: 0x74cfd8, transparent: true, opacity: 0.85 }),
        );
        shallow.geometry.rotateX(-Math.PI / 2);
        shallow.position.y = ISLAND.waterY + 0.12;
        this.group.add(shallow);
    }

    _buildFoam() {
        // 波打ち際の白い泡：砂浜の輪郭を少し外側へ広げたリング
        const outer = shapeFromRadius(sandRadiusAt, 120, 1.055);
        const hole = new THREE.Path();
        const segments = 120;
        for (let i = 0; i <= segments; i++) {
            const t = (i / segments) * Math.PI * 2;
            const r = sandRadiusAt(t) * 0.995;
            const px = Math.cos(t) * r;
            const py = -Math.sin(t) * r;
            if (i === 0) hole.moveTo(px, py);
            else hole.lineTo(px, py);
        }
        outer.holes.push(hole);

        const geo = new THREE.ShapeGeometry(outer);
        geo.rotateX(-Math.PI / 2);
        this.foam = new THREE.Mesh(geo, new THREE.MeshBasicMaterial({
            color: 0xffffff, transparent: true, opacity: 0.55, depthWrite: false,
        }));
        this.foam.position.y = ISLAND.waterY + 0.2;
        this.group.add(this.foam);
    }

    _buildPond() {
        // 島の東側の小さな池（歩行時は障害物として扱う）
        this.pond = { x: 14, z: -6, r: 4.6 };

        // 池のふちの砂地（芝生のすぐ上に敷く）
        const rim = new THREE.Mesh(
            new THREE.CylinderGeometry(this.pond.r + 1.1, this.pond.r + 1.1, 0.16, 32),
            new THREE.MeshLambertMaterial({ color: 0xe3d3a8 }),
        );
        rim.position.set(this.pond.x, ISLAND.grassTop, this.pond.z);
        rim.receiveShadow = true;
        this.group.add(rim);

        // 水面
        const surface = new THREE.Mesh(
            new THREE.CircleGeometry(this.pond.r, 32),
            new THREE.MeshLambertMaterial({ color: 0x56b6cf, transparent: true, opacity: 0.9 }),
        );
        surface.geometry.rotateX(-Math.PI / 2);
        surface.position.set(this.pond.x, ISLAND.grassTop + 0.1, this.pond.z);
        this.pondSurface = surface;
        this.group.add(surface);

        // 水辺の石
        const stoneMat = new THREE.MeshLambertMaterial({ color: 0x9aa0a6, flatShading: true });
        for (let i = 0; i < 5; i++) {
            const a = (i / 5) * Math.PI * 2 + 0.6;
            const stone = new THREE.Mesh(new THREE.IcosahedronGeometry(0.45 + (i % 3) * 0.12, 0), stoneMat);
            stone.position.set(
                this.pond.x + Math.cos(a) * (this.pond.r + 0.7),
                ISLAND.grassTop + 0.2,
                this.pond.z + Math.sin(a) * (this.pond.r + 0.7),
            );
            stone.rotation.set(i, a, i * 0.5);
            stone.castShadow = true;
            this.group.add(stone);
        }
    }

    /** 指定座標の地面の高さ。浜辺 → 斜面 → 芝生の順になめらかにつながる。 */
    groundHeightAt(x, z) {
        const theta = Math.atan2(z, x);
        const r = Math.hypot(x, z);
        const rg = grassRadiusAt(theta);

        if (r <= rg - ISLAND.slope) return ISLAND.grassTop;
        if (r >= rg) return ISLAND.sandTop;

        const t = (r - (rg - ISLAND.slope)) / ISLAND.slope;
        const smooth = t * t * (3 - 2 * t); // smoothstep
        return ISLAND.grassTop + (ISLAND.sandTop - ISLAND.grassTop) * smooth;
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

    update(time) {
        // 海面のゆるやかなうねり（負荷を抑えるため 2 フレームに 1 回だけ更新する）
        this._frame = (this._frame || 0) + 1;
        if (this._frame % 2 === 0) this._updateWaves(time);

        // 波打ち際の泡はゆっくり寄せて返す
        const pulse = 1 + Math.sin(time * 0.8) * 0.012;
        this.foam.scale.set(pulse, 1, pulse);
        this.foam.material.opacity = 0.45 + Math.sin(time * 0.8) * 0.15;

        this.pondSurface.position.y = ISLAND.grassTop + 0.1 + Math.sin(time * 1.4) * 0.03;
    }

    _updateWaves(time) {
        const pos = this.waterGeo.attributes.position;
        const base = this.waterBase;
        for (let i = 0; i < pos.count; i++) {
            const x = base[i * 3];
            const z = base[i * 3 + 2];
            pos.array[i * 3 + 1] =
                Math.sin(x * 0.06 + time * 0.9) * 0.35 +
                Math.sin(z * 0.08 - time * 0.7) * 0.3;
        }
        pos.needsUpdate = true;
        this.waterGeo.computeVertexNormals();
    }
}
