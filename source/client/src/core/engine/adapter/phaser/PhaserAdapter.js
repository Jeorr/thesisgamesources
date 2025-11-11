export default class PhaserAdapter {
    constructor() {
        // traits
        Object.assign(this, InventoryFramesTrait);
        Object.assign(this, ShopFramesTrait);
        Object.assign(this, ChapterOverviewFramesTrait);
        Object.assign(this, ArenaFramesTrait);
        Object.assign(this, ChatFramesTrait);
        Object.assign(this, SettingsFramesTrait);
        Object.assign(this, ChapterIntroFramesTrait);
        Object.assign(this, GlobalFramesTrait);
        Object.assign(this, BootSceneFramesTrait);
        Object.assign(this, BarracksFramesTrait);
        Object.assign(this, BattleFramesTrait);

        // init
        this.game = null;
        this.sceneWidth = CONSTANTS.CANVAS_WIDTH;
        this.sceneHeight = CONSTANTS.CANVAS_HEIGHT;
        this.DOM = {
            reserved: {}
        };
    }

    /**
     *
     * @returns {Phaser.Scene}
     */
    getOriginalScene() {
        return App().getEngine().getSceneManager().getCurrentScene().getOriginal();
    }

    getSceneWidth() {
        return this.getOriginalScene().sys.game.scale.gameSize.width;
    }

    getSceneHeight() {
        return this.getOriginalScene().sys.game.scale.gameSize.height;
    }

    getRealSceneWidth() {
        return this.getOriginalScene().sys.game.canvas.clientWidth;
    }

    getRealSceneHeight() {
        return this.getOriginalScene().sys.game.canvas.clientHeight;
    }

    getDisplayScaleX() {
        return this.getOriginalScene().sys.game.scale.displayScale.x || 1;
    }

    getDisplayScaleY() {
        return this.getOriginalScene().sys.game.scale.displayScale.y || 1;
    }

    loadInitialScene() {
        let config = {
            type: Phaser.AUTO,
            width: CONSTANTS.CANVAS_WIDTH,
            height: CONSTANTS.CANVAS_HEIGHT,
            scale: {
                // Or set parent divId here
                parent: 'gamecontainer',
                mode: Phaser.Scale.FIT,
                autoCenter: Phaser.Scale.CENTER_BOTH,

            },
            backgroundColor: '#fff',
            pixelArt: true,
            dom: {
                createContainer: true
            },
            scene: [
                BootScene,
                OneVsOneBattleScene,
                LocationsMapScene,
                LocationDetailsScene,
                ArenaScene,
                ShopScene,
                LaboratoryScene,
                BarracksScene,
                ErrorScene,
                HeroSelectScene,
                InventoryScene,
                ChapterOverviewScene,
                IntroScene,
            ],
        };

        this.game = new Phaser.Game(config);
    }
}
