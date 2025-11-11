import PhaserAdapter from "./engine/adapter/phaser/PhaserAdapter";
import UI from "./engine/ui/UI";
import SceneManager from "./engine/scene/SceneManager";
import ObjectManager from "./engine/objects/ObjectManager";
import BattleManager from "./engine/battle/BattleManager";
import TextManager from "./engine/text/TextManager";
import MusicManager from "./engine/music/MusicManager";
import SoundManager from "./engine/sound/SoundManager";
import GameSettingsManager from "./engine/settings/GameSettingsManager";

export default class Engine {
    constructor() {
        this.adapter = new PhaserAdapter();
        this.ui = new UI(this.adapter);
        this.sceneManager = new SceneManager(this.adapter);
        this.objectManager = new ObjectManager(this.adapter);
        this.battleManager = new BattleManager(this.adapter);
        this.textManager = new TextManager(this.adapter);
        this.musicManager = new MusicManager(this.adapter);
        this.soundManager = new SoundManager(this.adapter);
        this.settingsManager = new GameSettingsManager(this.adapter);
    }

    init() {
        this.getSceneManager().loadInitialScene();
    }

    /**
     *
     * @returns {PhaserAdapter}
     */
    getAdapter() {
        return this.adapter;
    }

    /**
     *
     * @returns {UI}
     */
    getUI() {
        return this.ui;
    }

    /**
     *
     * @returns {SceneManager}
     */
    getSceneManager() {
        return this.sceneManager;
    }

    /**
     *
     * @returns {ObjectManager}
     */
    getObjectManager() {
        return this.objectManager;
    }

    /**
     *
     * @returns {BattleManager}
     */
    getBattleManager() {
        return this.battleManager;
    }

    /**
     *
     * @returns {TextManager}
     */
    getTextManager() {
        return this.textManager;
    }

    /**
     *
     * @returns {MusicManager}
     */
    getMusicManager() {
        return this.musicManager;
    }

    /**
     *
     * @returns {SoundManager}
     */
    getSoundManager() {
        return this.soundManager;
    }

    /**
     *
     * @returns {GameSettingsManager}
     */
    getSettingsManager() {
        return this.settingsManager;
    }
}