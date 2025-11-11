
export default class OneShotSound {
    constructor(soundKey) {
        this.soundKey = soundKey;
        this.audioObject = null;
    }


    play() {
        if (this.audioObject) {
            this.audioObject.play();
        }
    }

    stop() {
        if (this.audioObject) {
            this.audioObject.stop();
        }
    }

    remove() {
        this.stop();
        
        if (this.audioObject) {
            this.audioObject.remove();
        }

        this.clear();
    }

    clear() {
        Object.keys(this).forEach(key => delete this[key]);
    }
}
