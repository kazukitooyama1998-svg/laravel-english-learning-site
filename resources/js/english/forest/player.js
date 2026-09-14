/**
 * 英語の森 – プレイヤーキャラクター
 *
 * ログイン中のユーザーが選択した動物キャラクター（characters.js）を歩かせる。
 * 見た目の組み立ては characters.js に任せ、ここでは移動・歩行アニメーション
 * だけを担当する。
 */
import * as THREE from 'three';
import { createLabel } from './labels.js';
import { buildCharacter } from './characters.js';

const WALK_SPEED = 6.2;
const RUN_SPEED = 10.5;
const ACCEL = 26;
const FRICTION = 14;
const TURN_SPEED = 12;

export class Player {
    /**
     * @param {THREE.Scene} scene
     * @param {{name: string, level: number, character: object}} profile
     */
    constructor(scene, profile) {
        this.profile = profile;
        this.velocity = new THREE.Vector3();
        this.facing = 0;         // 現在の向き（ラジアン）
        this.speed = 0;          // 水平方向の速さ（アニメーション用）
        this.walkPhase = 0;

        const character = profile.character || {};
        const rig = buildCharacter(character);

        // 体のパーツ同士でも影を落とし合わせる（あごの下や腕の内側に陰ができる）
        rig.group.traverse((child) => {
            if (child.isMesh && child.material?.type !== 'MeshBasicMaterial') {
                child.castShadow = true;
                child.receiveShadow = true;
            }
        });

        this.group = rig.group;
        this.body = rig.body;
        this.head = rig.head;
        this.arms = rig.arms;
        this.legs = rig.legs;
        this.blob = rig.blob;

        // 名前プレート（キャラクターの持ち主のアクセントカラーで縁取り）
        this.nameTag = createLabel(profile.name, {
            sub: `Lv.${profile.level ?? 1}`,
            accent: character.accent || character.body || '#3d5a80',
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
        this.head.position.y = 1.6 + bob;
        this.head.rotation.z = moving ? Math.sin(this.walkPhase) * 0.05 : 0;

        // 影は地面に貼りついたまま
        this.blob.position.y = 0.03;
    }
}
