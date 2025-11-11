import CONSTANTS from "../../../Constants";
import OneShotSound from "../OneShotSound";

export default class UiElementClickSound extends OneShotSound {
    constructor(soundKey) {
        super(soundKey);
    }

    buildSound() {        
        this.audioObject = App().getEngine().getObjectManager().addGlobalAudioObject({
            key: this.soundKey
        });
        this.audioObject.setVolume(CONSTANTS.SETTINGS_DEFAULT_AMBIENT_SOUND_VOLUME);
        this.audioObject.setCompleteFunction(() => {
            this.remove();
        });
    }
}
