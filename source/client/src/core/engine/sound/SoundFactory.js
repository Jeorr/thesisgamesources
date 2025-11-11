
import UiElementClickSound from "./predefined/UiElementClickSound";

export default class SoundFactory {

    static preloadKeys = ['uielementclick'];

    static classMapSounds = {
        'uielementclick': UiElementClickSound,
    };

    constructor() {
    }

    /**
     * Create sound
     *
     * @param soundKey
     * @returns {*}
     */
    static createSound(soundKey) {
        let sound = null;
        if (soundKey && (soundKey in SoundFactory.classMapSounds)) {
            sound = new (SoundFactory.classMapSounds[soundKey])(soundKey);
        } else {
            throw new Error('Could not create sound: given soundKey is not configured - '  + soundKey)
        }

        sound.buildSound();

        return sound;
    }
}
