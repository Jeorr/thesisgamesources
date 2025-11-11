import SoundFactory from "./SoundFactory";

export default class SoundManager {
    constructor(adapter) {
        this.adapter = adapter;
    }

    playSound(soundKey) {
        let sound = SoundFactory.createSound(soundKey);
        sound.play();
    }
}
