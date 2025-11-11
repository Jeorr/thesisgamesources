import SimpleObject from "./SimpleObject";
import CONSTANTS from "../../Constants";

export default class AnimatedObject extends SimpleObject {
    constructor(objectType) {
        super(objectType);

        this.defaultAnimationKey = CONSTANTS.OBJECT_DEFAULT_ANIMATION_KEY;
        this.activeAnimationKey = CONSTANTS.OBJECT_ACTIVE_ANIMATION_KEY;
        this.animations = {};
        this.skeletonPoints = {};
        this.animationStartFunction = null;
        this.animationEndFunction = null;
    }

    setAnimationStartFunction(callback) {
        this.animationStartFunction = callback;
    }

    getAnimationStartFunction() {
        return this.animationStartFunction;
    }

    setAnimationEndFunction(callback) {
        this.animationEndFunction = callback;
    }

    getAnimationEndFunction() {
        return this.animationEndFunction
    }

    restoreOriginalPositionWithAnimation(animation){
        /*let animationKey = animation.key || '';
        let animationDuration = parseFloat(animation.duration || 0);
        let origPosition = this.getOriginalPosition();

        if (animationDuration <= 0) {
            this.setPosition(origPosition.x || 0, origPosition.y || 0);
            return;
        }

        let animationConfig = {
            key: animationKey,
            duration: animationDuration,
            position: [origPosition.x || 0, origPosition.y || 0]
        };
        console.log(animationConfig);
        this.playAnimationChain([animationConfig], true);*/
    }

    getDefaultAnimationKey() {
        return this.defaultAnimationKey;
    }

    getActiveAnimationKey() {
        return this.activeAnimationKey;
    }

    getCurrentAnimationKey() {
        return this.objectManager.getUnitCurrentAnimationKey(this);
    }

    addAnimation(key, animation) {
        this.animations[key] = animation;
    }

    getAnimation(key) {
        return this.animations[key];
    }

    getAnimations() {
        return this.animations;
    }

    playAnimation(animation, repeat = false, force = false, fallbackToDefaultAfter = false) {
        this.objectManager.playObjectAnimation(this, animation, repeat, force, fallbackToDefaultAfter);
    }

    playAnimationChain(animationChain, force, fallbackToDefaultAfter = false) {
        this.objectManager.playObjectAnimationChain(this, animationChain, force, fallbackToDefaultAfter);
    }

    setHighlighted(enable) {
        if (enable && !this.highlighted) {
            this.playAnimation(this.getActiveAnimationKey(), true, true);
            this.setScale(this.getScale() + 0.05);
            this.highlighted = true;
        } else if (!enable && this.highlighted) {
            this.playAnimation(this.getDefaultAnimationKey(), true, true);
            this.setScale(this.getScale() - 0.05);
            this.highlighted = false;
        }
    }

    getSkeletonPoints() {
        return this.skeletonPoints;
    }

    addSkeletonPoint(point, x, y) {
        this.skeletonPoints[point] = { x: x, y: y };
    }

    getSkeletonPoint(point) {
        return this.skeletonPoints[point];
    }
}
