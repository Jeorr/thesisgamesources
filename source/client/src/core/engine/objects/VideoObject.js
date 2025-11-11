import MediaObject from "./MediaObject";
import CONSTANTS from "../../Constants";

export default class VideoObject extends MediaObject {
    constructor(objectType) {
        super(objectType);
    }

    setMuted(muted) {
        this.objectManager.setMuted(this, muted);
    }

    getMuted() {
        return this.objectManager.getMuted(this);
    }

    getDuration() {
        return this.objectManager.getVideoDuration(this);
    }

    play() {
        this.objectManager.playVideo(this);
    }
    
    stop() {
        this.objectManager.stopVideo(this);
    }

    pause() {
        this.objectManager.pauseVideo(this);
    }
}
