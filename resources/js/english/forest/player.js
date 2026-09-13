/**
 * 英語の森 – プレイヤーキャラクター
 *
 * ログイン中のユーザー本人を表す、丸っこいローポリのキャラクター。
 * 歩くと手足が振れ、体が少し弾む。名前は頭の上に表示する。
 */
import * as THREE from 'three';
import { createLabel } from './labels.js';

const WALK_SPEED = 6.2;
const RUN_SPEED = 10.5;
const ACCEL = 26;
const FRICTION = 14;
const TURN_SPEED = 12;

const mat = (color, flat = false) => new THREE.MeshLambertMaterial({ color, flatShading: flat });

export class Player {
    /**
     * @param {THREE.Scene} scene
     * @param {{name: string, color: string, level: number}} profile
     */
    constructor(scene, profile) {
        this.profile = profile;
        this.group = new THREE.Group();
        this.velocity = new THREE.Vector3();
        this.facing = 0;         // 現在の向き（ラジアン）
        this.speed = 0;          // 水平方向の速さ（アニメーション用）
        this.walkPhase = 0;

        const skin = 0xf7dcc0;
        const cloth = new THREE.Color(profile.color || '#3d5a80');
        const clothDark = cloth.clone().multiplyScalar(0.78);

        // 胴体
        this.body = new THREE.Mesh(new THREE.CapsuleGeometry(0.42, 0.42, 6, 14), mat(cloth));
        this.body.position.y = 0.78;
        this.body.castShadow = true;
        this.group.add(this.body);

        // 頭
        this.head = new THREE.Group();
        this.head.position.y = 1.62;
        this.group.add(this.head);

        const skull = new THREE.Mesh(new THREE.SphereGeometry(0.52, 16, 14), mat(skin));
        skull.scale.set(1, 0.96, 0.95);
        skull.castShadow = true;
        this.head.add(skull);

        // 髪（頭のうしろ半分をかぶせる）
        const hair = new THREE.Mesh(new THREE.SphereGeometry(0.545, 16, 14, 0, Math.PI * 2, 0, Math.PI * 0.62), mat(0x4a3a2e));
        hair.position.y = 0.01;
        this.head.add(hair);

        // 目・ほほ・口
        const eyeMat = mat(0x32302e);
        [-0.19, 0.19].forEach((x) => {
            const eye = new THREE.Mesh(new THREE.SphereGeometry(0.072, 10, 8), eyeMat);
            eye.position.set(x, 0.04, 0.46);
            eye.scale.set(0.85, 1.15, 0.6);
            this.head.add(eye);
        });
        const blushMat = new THREE.MeshBasicMaterial({ color: 0xf0a79a, transparent: true, opacity: 0.75 });
        [-0.3, 0.3].forEach((x) => {
            const blush = new THREE.Mesh(new THREE.CircleGeometry(0.1, 12), blushMat);
            blush.position.set(x, -0.08, 0.42);
            blush.rotation.y = x > 0 ? -0.5 : 0.5;
            this.head.add(blush);
        });
        const mouth = new THREE.Mesh(new THREE.TorusGeometry(0.07, 0.022, 6, 12, Math.PI), eyeMat);
        mouth.position.set(0, -0.14, 0.47);
        mouth.rotation.z = Math.PI;
        this.head.add(mouth);

        // 腕
        const armGeo = new THREE.CapsuleGeometry(0.13, 0.34, 4, 8);
        this.arms = [-1, 1].map((side) => {
            const pivot = new THREE.Group();
            pivot.position.set(side * 0.5, 1.12, 0);
            const arm = new THREE.Mesh(armGeo, mat(cloth));
            arm.position.y = -0.28;
            arm.castShadow = true;
            const hand = new THREE.Mesh(new THREE.SphereGeometry(0.15, 10, 8), mat(skin));
            hand.position.y = -0.55;
            pivot.add(arm, hand);
            this.group.add(pivot);
            return pivot;
        });

        // 脚
        const legGeo = new THREE.CapsuleGeometry(0.16, 0.3, 4, 8);
        this.legs = [-1, 1].map((side) => {
            const pivot = new THREE.Group();
            pivot.position.set(side * 0.2, 0.52, 0);
            const leg = new THREE.Mesh(legGeo, mat(clothDark));
            leg.position.y = -0.24;
            leg.castShadow = true;
            const shoe = new THREE.Mesh(new THREE.SphereGeometry(0.19, 10, 8), mat(0xf4efe2));
            shoe.scale.set(1, 0.8, 1.3);
            shoe.position.set(0, -0.46, 0.05);
            pivot.add(leg, shoe);
            this.group.add(pivot);
            return pivot;
        });

        // 接地感を出す簡易影
        this.blob = new THREE.Mesh(
            new THREE.CircleGeometry(0.55, 18),
            new THREE.MeshBasicMaterial({ color: 0x2e3a4f, transparent: true, opacity: 0.18, depthWrite: false }),
        );
        this.blob.rotation.x = -Math.PI / 2;
        this.blob.position.y = 0.03;
        this.group.add(this.blob);

        // 名前プレート
        this.nameTag = createLabel(profile.name, {
            sub: `Lv.${profile.level ?? 1}`,
            accent: profile.color || '#3d5a80',
            height: 0.62,
        });
        this.nameTag.position.y = 2.75;
        this.group.add(this.nameTag);

        scene.add(this.group);
    }

    get position() { return this.group.position; }

    /**
     * 移動と姿勢の更新。
     * @param {number} dt      経過秒
     * @param {THREE.Vector3} moveDir  ワールド座標での進みたい方向（長さ 0〜1）
     * @param {boolean} running  ダッシュ中か
     * @param {object} world   { groundHeightAt, resolveCollisions }
     */
    update(dt, moveDir, running, world) {
        const maxSpeed = running ? RUN_SPEED : WALK_SPEED;
        const wants = moveDir.lengthSq() > 0.0001;

        if (wants) {
            this.velocity.x += moveDir.x * ACCEL * dt;
            this.velocity.z += moveDir.z * ACCEL * dt;
            const sp = Math.hypot(this.velocity.x, this.velocity.z);
            if (sp > maxSpeed) {
                this.velocity.x *= maxSpeed / sp;
                this.velocity.z *= maxSpeed / sp;
            }
        } else {
            const damp = Math.max(0, 1 - FRICTION * dt);
            this.velocity.x *= damp;
            this.velocity.z *= damp;
        }

        const next = {
            x: this.group.position.x + this.velocity.x * dt,
            z: this.group.position.z + this.velocity.z * dt,
        };
        const resolved = world.resolveCollisions(next.x, next.z);
        this.group.position.x = resolved.x;
        this.group.position.z = resolved.z;

        // 地面の高さへ追従（浜辺と芝生の段差をなめらかに登る）
        const groundY = world.groundHeightAt(resolved.x, resolved.z);
        this.group.position.y += (groundY - this.group.position.y) * Math.min(1, dt * 12);

        // 進行方向へ体を向ける（最短回転）
        this.speed = Math.hypot(this.velocity.x, this.velocity.z);
        if (this.speed > 0.15) {
            const target = Math.atan2(this.velocity.x, this.velocity.z);
            let diff = target - this.facing;
            diff = Math.atan2(Math.sin(diff), Math.cos(diff));
            this.facing += diff * Math.min(1, TURN_SPEED * dt);
        }
        this.group.rotation.y = this.facing;

        // 歩行アニメーション
        const moving = this.speed > 0.2;
        this.walkPhase += dt * this.speed * 2.2;
        const swing = moving ? Math.sin(this.walkPhase) * Math.min(0.9, this.speed * 0.12) : 0;
        this.arms[0].rotation.x = swing;
        this.arms[1].rotation.x = -swing;
        this.legs[0].rotation.x = -swing;
        this.legs[1].rotation.x = swing;

        const bob = moving ? Math.abs(Math.sin(this.walkPhase)) * 0.07 : 0;
        this.body.position.y = 0.78 + bob;
        this.head.position.y = 1.62 + bob;
        this.head.rotation.z = moving ? Math.sin(this.walkPhase) * 0.05 : 0;

        // 影は地面に貼りついたまま
        this.blob.position.y = 0.03;
    }
}
