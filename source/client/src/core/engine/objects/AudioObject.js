import MediaObject from "./MediaObject";
import CONSTANTS from "../../Constants";

export default class AudioObject extends MediaObject {
    constructor(objectType) {
        super(objectType);
    }

    getDuration() {
        return this.objectManager.getAudioDuration(this);
    }

    isPlaying() {
        return this.objectManager.isAudioPlaying(this);
    }

    setVolume(volume) {
        this.objectManager.setVolume(this, volume);
    }

    play() {
        this.objectManager.playAudio(this);
    }

    stop() {
        this.objectManager.stopAudio(this);
    }

    pause() {
        this.objectManager.pauseAudio(this);
    }
}
