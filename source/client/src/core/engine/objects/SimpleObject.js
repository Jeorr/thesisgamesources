import BaseObject from "./BaseObject";
import Constants from "../../Constants";

export default class SimpleObject extends BaseObject {
    constructor(objectType) {
        super(objectType);

        this.orientation = 'right';
        this.highlighted = false;
    }

    getOrigin() {
        return this.objectManager.getObjectOrigin(this);
    }

    setOrigin(x, y) {
        this.objectManager.setObjectOrigin(this, x, y);
    }

    getDisplayOrigin() {
        return this.objectManager.getObjectDisplayOrigin(this);
    }

    setDisplayOrigin(x, y) {
        this.objectManager.setObjectDisplayOrigin(this, x, y);
    }

    setWidth(width) {
        this.objectManager.setObjectWidth(this, width);
    }

    getWidth() {
        return this.objectManager.getObjectWidth(this);
    }

    setHeight(height) {
        this.objectManager.setObjectHeight(this, height);
    }

    getHeight() {
        return this.objectManager.getObjectHeight(this);
    }

    getPosition() {
        return this.objectManager.getUnitPosition(this);
    }

    setPosition(x, y) {
        this.objectManager.setUnitPosition(this, x, y);
    }

    getZindex() {
        return this.objectManager.getObjectZindex(this);
    }

    setZindex(zIndex) {
        this.objectManager.setObjectZindex(this, zIndex);
    }

    getBounds() {
        return this.objectManager.getUnitBounds(this);
    }

    getScale() {
        return this.objectManager.getUnitScale(this);
    }

    setScale(scale) {
        this.objectManager.setUnitScale(this, scale);
    }

    setOrientation(orientation) {
        if (orientation !== 'right' && orientation !== 'left') {
            throw new Error('Invalid orientation value!');
        }

        if (this.orientation === orientation) {
            return;
        } else if (this.orientation === 'left') {
            this.objectManager.flipUnitX(this, false);
        } else {
            this.objectManager.flipUnitX(this, true);
        }

        this.orientation = orientation;
    }

    getOrientation() {
        return this.orientation;
    }

    setHighlighted(enable) {
        if (enable && !this.highlighted) {
            this.setScale(this.getScale() + 0.05);
            this.highlighted = true;
        } else if (!enable && this.highlighted) {
            this.setScale(this.getScale() - 0.05);
            this.highlighted = false;
        }
    }

    setVisible(visible) {
        this.objectManager.setVisible(this, visible);
    }

    onClick(func) {
        this.objectManager.objectClickEvent(this, func);
    }

    click() {
        this.objectManager.objectTriggerClickEvent(this);
    }
}
