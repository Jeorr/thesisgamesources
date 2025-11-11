import UserInitGameEvent from "../../../../events/userevents/UserInitGameEvent";
import UserStartsBattleWithEnemyNPCEvent from "../../../../events/userevents/UserStartsBattleWithEnemyNPCEvent";
import BaseScene from "./BaseScene";
import AssetsUtility from "../../../../AssetsUtility";
import CONSTANTS from "../../../../Constants";

export default class BootScene extends BaseScene {

    constructor() {
        super({key: 'BootScene'});
    }

    preload() {
        super.preload();

        this.load.image('loadingscreen', AssetsUtility.buildCommonUIElementPath('loadingscreen'));
        this.load.html('bootsceneframes', AssetsUtility.buildHtmlComponentPath('bootsceneframes'));
    }

    create() {
        super.create();

        App().getEngine().getUI().enableBootSceneLoadingFrames({
            progress: 0,
            complete: false
        });

        // Progress callback
        this.load.on('progress', (value) => {
            console.log('progress: ' + value);
            App().getEngine().getUI().updateBootSceneLoadingFrames({
                progress: value
            });
        });

        // Optional: complete callback
        this.load.on('complete', () => {
            App().getEngine().getUI().updateBootSceneLoadingFrames({
                progress: 100,
                complete: true
            });
        });

        this.createStaticMap('loadingscreen');

        this.preloadHtmlTemplates();
        this.preloadAllSpecialEffects();
        this.preloadAllMusic();
        this.preloadAllSounds();

        // Important: start new load phase manually
        this.load.start();

        // Initialize settings
        App().getEngine().getSettingsManager().initSettings({
            language: CONSTANTS.SETTINGS_DEFAULT_LANGUAGE,
            enableSounds: CONSTANTS.SETTINGS_DEFAULT_ENABLE_SOUNDS
        });
    }

    update ()
    {
        //console.log(1113344);
    }
}

