import { v4 as uuidv4 } from 'uuid';
import MathUtility from "../../MathUtility";

export default class BaseObject {
    constructor(objectType) {
        this.internalId = MathUtility.generateRandomId();
        this.objectType = objectType;
        this.tags = {};
        this.original = null;
        this.data = null;
        this.removeFunction = null;
        this.loopFunction = null;
        /** @type {ObjectManager} */
        this.objectManager = App().getEngine().getObjectManager();
    }

    getInternalId() {
        return this.internalId;
    }

    getObjectType() {
        return this.objectType;
    }

    getTags() {
        return this.tags;
    }

    addTag(tag) {
        this.tags[tag] = tag;
    }

    hasTag(tag) {
        return tag in this.tags;
    }

    removeTag(tag) {
        delete this.tags[tag];
    }

    getOriginal() {
        return this.original;
    }

    setOriginal(original) {
        this.original = original;
    }

    getData() {
        return this.data;
    }

    setData(data) {
        this.data = data;
    }

    setRemoveFunction(callback) {
        this.removeFunction = callback;
    }

    getRemoveFunction() {
        return this.removeFunction;
    }

    remove() {
        if (this.removeFunction) {
            this.removeFunction();
        }

        this.clear();
    }

    clear() {
        Object.keys(this).forEach(key => delete this[key]);
    }

    setLoopFunction(callback) {
        this.loopFunction = callback;
    }

    getLoopFunction() {
        return this.loopFunction;
    }
}
