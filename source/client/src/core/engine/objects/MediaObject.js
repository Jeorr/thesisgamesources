import SimpleObject from "./SimpleObject";
import CONSTANTS from "../../Constants";

export default class MediaObject extends SimpleObject {
    constructor(objectType) {
        super(objectType);

        this.playSpeed = 1;
        this.metadataLoadedFunction = null;
        this.completeFunction = null;
    }

    getPlaySpeed() {
        return this.playSpeed;
    }

    setPlaySpeed(playSpeed) {
        this.playSpeed = playSpeed;
    }

    getVolume() {
        return this.objectManager.getVolume(this);
    }

    setVolume(volume) {
        this.objectManager.setVolume(this, volume);
    }
     
    setMetadataLoadedFunction(callback) {
        this.metadataLoadedFunction = callback;
    }

    getMetadataLoadedFunction() {
        return this.metadataLoadedFunction
    }

    setCompleteFunction(callback) {
        this.completeFunction = callback;
    }

    getCompleteFunction() {
        return this.completeFunction;
    }
}
