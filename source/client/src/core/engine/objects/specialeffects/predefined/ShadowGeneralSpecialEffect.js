import SpecialEffect from "../SpecialEffect";
import CONSTANTS from "../../../../Constants";

export default class ShadowGeneralSpecialEffect extends SpecialEffect {
    constructor(objectType) {
        super(objectType);

        this.node = null;
        this.defaultOriginX = 0.5;
        this.defaultOriginY = 0.5;
        this.offsetX = 0;
        this.offsetY = 0;
        this.scaleModifier = 0.6;
    }

    buildEffect(additionalData) {
        this.node = this.objectManager.addAnimatedObjectOnScene({
            sprite: 'shadowgeneral',
            x: additionalData.x || 0,
            y: additionalData.y || 0,
        });

        if (additionalData.offsetX) {
            this.offsetX = additionalData.offsetX;
        }
        if (additionalData.offsetY) {
            this.offsetY = additionalData.offsetY;
        }
        
        this.node.setScale(additionalData.scale || 1);
        this.node.playAnimation(this.node.getDefaultAnimationKey(), true, true);
        this.node.setOrigin(this.defaultOriginX, this.defaultOriginY);

        this.node.setLoopFunction(() => {
            let attachToUnit = this.getAttachToUnit();
            if (attachToUnit) {
                let position = attachToUnit.getPosition();
                let adjustedSkeletonPoint = this.getAdjustedSkeletonPointBasedOnOrientation();

                if (adjustedSkeletonPoint) {
                    position = {
                        x: position.x + adjustedSkeletonPoint.x + this.offsetX,
                        y: position.y + adjustedSkeletonPoint.y + this.offsetY,
                    };
                }

                this.node.setPosition(position.x, position.y);
                this.node.setScale(attachToUnit.getScale() * this.scaleModifier);
                this.node.setZindex(attachToUnit.getZindex() - 2);
            }
        });

        // implement delete logic
    }

    remove() {
        if (this.removeFunction) {
            this.removeFunction();
        }

        this.node.remove();
        this.clear();
    }
}
