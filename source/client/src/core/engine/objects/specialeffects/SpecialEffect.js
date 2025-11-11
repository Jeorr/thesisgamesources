import BaseObject from "../BaseObject";
import Unit from "../units/Unit";

export default class SpecialEffect extends BaseObject {
    constructor(objectType) {
        super(objectType);

        this.attachToUnit = null;
        this.attachedSkeletonPointKey = null;
    }

    /**
     * @returns {Unit}
     */
    getAttachToUnit() {
        return this.attachToUnit;
    }

    setAttachToUnit(unit) {
        this.attachToUnit = unit;
    }

    getAttachedSkeletonPointKey() {
        return this.attachedSkeletonPointKey;
    }

    setAttachedSkeletonPointKey(skeletonPointKey) {
        this.attachedSkeletonPointKey = skeletonPointKey;
    }

    getAdjustedSkeletonPointBasedOnOrientation() {
        let attachToUnit = this.getAttachToUnit();
        if (!attachToUnit) {
            return null;
        }

        let orientation = attachToUnit.getOrientation();
        let skeletonPointKey = this.getAttachedSkeletonPointKey();
        let skeletonPoint = attachToUnit.getSkeletonPoint(skeletonPointKey);

        if (!skeletonPoint) {
            return null;
        }

        let adjustedSkeletonPoint = {
            x: skeletonPoint.x,
            y: skeletonPoint.y,
        };

        if (skeletonPointKey === 'front' || skeletonPointKey === 'back') {
            if (orientation === 'left') {
                adjustedSkeletonPoint.x = -skeletonPoint.x;
            } else {
                adjustedSkeletonPoint.x = skeletonPoint.x;
            }
        }
        
        return adjustedSkeletonPoint;
    }
}
