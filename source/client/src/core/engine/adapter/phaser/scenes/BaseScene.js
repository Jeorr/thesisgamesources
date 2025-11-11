import Scene from "../../../scene/Scene";
import AssetsUtility from "../../../../AssetsUtility";
import SpecialEffectFactory from "../../../objects/specialeffects/SpecialEffectFactory";
import CONSTANTS from "../../../../Constants";
import MusicListFactory from "../../../music/MusicListFactory";
import SoundFactory from "../../../sound/SoundFactory";

export default class BaseScene extends Phaser.Scene {
    COMMON_HTML_COMPONENTS = [
        'loader', 
        'topbar', 
        'cli', 
        'error', 
        'flashmessage', 
        'tooltip',
        'unitfullinfo',
        'firstheroselect',
        'chatframes',
        'settingsframes',
        'chapterintroframes',
        'bootsceneframes',
    ];

    constructor(obj) {
        super(obj);

        this.loopFunctions = {};
    }

    preload() {
        this.load.setBaseURL(CONSTANTS.BASE_URL);
        this.load.css('main', 'client/assets/css/style.min.css');
    }

    create() {
        let scene = new Scene();
        scene.setOriginal(this)

        App().getEngine().getSceneManager().setCurrentScene(scene);
        App().getEngine().getUI().clearHtmlElementsOnSceneChange();
        App().getEngine().getUI().showAllHtmlElements();

        this.cameras.main.setZoom(1);

        this.input.on('pointerdown', function () {
            // clear all tooltips
            let tooltip = App().getEngine().getUI().getHtmlComponentByKey('tooltip');
            if (tooltip) {
                App().getEngine().getUI().destroyHtmlElement(tooltip);
            }
        });
    }

    update() {
        for (let functionKey in this.loopFunctions) {
            let callback = this.loopFunctions[functionKey];
            callback();
        }
    }

    addLoopFunction(key, callback) {
        this.loopFunctions[key] = callback;
    }

    getLoopFunctions() {
        return this.loopFunctions;
    }

    removeLoopFunction(key) {
        delete this.loopFunctions[key];
    }

    adjustCoordinatesToScaledMap(originalX, originalY) {
        const screenWidth = this.cameras.main.width;
        const screenHeight = this.cameras.main.height;

        const mapOriginalWidth = CONSTANTS.GAME_SCENE_ORIGINAL_WIDTH;
        const mapOriginalHeight = CONSTANTS.GAME_SCENE_ORIGINAL_HEIGHT;

        const scaleX = screenWidth / mapOriginalWidth;
        const scaleY = screenHeight / mapOriginalHeight;
        const scale = Math.max(scaleX, scaleY);

        const displayWidth = mapOriginalWidth * scale;
        const displayHeight = mapOriginalHeight * scale;

        const offsetX = (screenWidth - displayWidth) / 2;
        const offsetY = (screenHeight - displayHeight) / 2;

        // Scale the coordinates and add the offset
        const adjustedX = (originalX * scale) + offsetX;
        const adjustedY = (originalY * scale) + offsetY;

        return { x: adjustedX, y: adjustedY };
    }

    adjustValueToScaledMap(originalValue) {
        const screenWidth = this.cameras.main.width;
        const screenHeight = this.cameras.main.height;

        const mapOriginalWidth = CONSTANTS.GAME_SCENE_ORIGINAL_WIDTH;
        const mapOriginalHeight = CONSTANTS.GAME_SCENE_ORIGINAL_HEIGHT;

        const scaleX = screenWidth / mapOriginalWidth;
        const scaleY = screenHeight / mapOriginalHeight;
        const mapScale = Math.max(scaleX, scaleY);

        return originalValue * mapScale;
    }

    createStaticMap(textureKey) {
        const screenWidth = this.cameras.main.width;
        const screenHeight = this.cameras.main.height;

        const mapOriginalWidth = CONSTANTS.GAME_SCENE_ORIGINAL_WIDTH;
        const mapOriginalHeight = CONSTANTS.GAME_SCENE_ORIGINAL_HEIGHT;

        const scaleX = screenWidth / mapOriginalWidth;
        const scaleY = screenHeight / mapOriginalHeight;
        const scale = Math.max(scaleX, scaleY);

        const displayWidth = mapOriginalWidth * scale;
        const displayHeight = mapOriginalHeight * scale;

        const offsetX = (screenWidth - displayWidth) / 2;
        const offsetY = (screenHeight - displayHeight) / 2;
        //console.log('createStaticMap', textureKey, mapOriginalWidth, mapOriginalHeight, screenWidth, screenHeight, scale, scaleX, scaleY, displayWidth, displayHeight, offsetX, offsetY);
        return this.add.image(0, 0, textureKey)
            .setOrigin(0, 0)
            .setDisplaySize(displayWidth, displayHeight)
            .setPosition(offsetX, offsetY)
            .setDataEnabled()
            .disableInteractive();
    }

    createDraggableMap(textureKey) {
        const map = this.add.image(0, 0, textureKey)
            .setOrigin(0, 0)
            .setScale(1)
            .setDataEnabled()
            .setInteractive();

        this.enableMapDrag(map.displayWidth, map.displayHeight);

        return map;
    }

    enableMapDrag(mapWidth, mapHeight) {
        let cam = this.cameras.main;

        cam.setBounds(0, 0, mapWidth, mapHeight);
        cam.setZoom(1);

        this.input.on("pointermove", function (p) {
            if (!p.isDown) return;

            cam.scrollX -= (p.x - p.prevPosition.x) / cam.zoom;
            cam.scrollY -= (p.y - p.prevPosition.y) / cam.zoom;
        });
    }

    enableGameObjectClick() {
        this.input.on('gameobjectup', function (pointer, gameObject) {
            // html elements do not propagate events to the game objects
            if (pointer.downElement && pointer.downElement.nodeName !== 'CANVAS') {
                return;
            }

            gameObject.emit('clicked', gameObject, pointer);
        }, this);
    }

    preloadUnitAseprites(sprite, animations = []) {
        // load default
        this.load.aseprite(
            sprite,
            AssetsUtility.buildUnitAsepritePngPath(sprite),
            AssetsUtility.buildUnitAsepriteJsonPath(sprite)
        );

        // load animations
        for (let i = 0; i < animations.length; i++) {
            let animation = animations[i];
            this.load.aseprite(
                sprite + '-' + animation,
                AssetsUtility.buildUnitAsepritePngPath(sprite, animation),
                AssetsUtility.buildUnitAsepriteJsonPath(sprite, animation)
            );
        }
    }

    preloadAllSpecialEffects() {
        let allSpecialEffectKeys = SpecialEffectFactory.preloadKeys;

        allSpecialEffectKeys.forEach((specialEffectKey) => {
            this.load.aseprite(
                specialEffectKey,
                AssetsUtility.buildSpecialEffectPngPath(specialEffectKey),
                AssetsUtility.buildSpecialEffectJsonPath(specialEffectKey)
            );
        });
    }

    // @todo consider replacing COMMON_HTML_COMPONENTS with a predefined objects in /html/predefined/
    preloadHtmlTemplates() {
        this.COMMON_HTML_COMPONENTS.forEach((component) => {
            this.load.html(component, AssetsUtility.buildHtmlComponentPath(component));
        });
    }

    preloadIntroVideo(key) {
        console.log('Preloading intro video: ' + key);
        this.load.video(
            key,
            AssetsUtility.buildIntroVideoMp4Path(key),
            true
        );
    }

    preloadIntroAudio(key) {
        console.log('Preloading intro audio: ' + key);
        this.load.audio(key, AssetsUtility.buildIntroAudioPath(key));
    }

    preloadAllMusic() {
        console.log('Preloading all music');
        let allMusicKeys = MusicListFactory.preloadKeys;

        console.log('allMusicKeys', allMusicKeys);
        allMusicKeys.forEach((musicKey) => {
            this.preloadMusic(musicKey);
        });
    }

    preloadMusic(key) {
        console.log('Preloading music: ' + key);
        this.load.audio(key, AssetsUtility.buildMusicPath(key));
    }


    preloadAllSounds() {
        let allSoundKeys = SoundFactory.preloadKeys;
        allSoundKeys.forEach((soundKey) => {
            this.preloadSound(soundKey);
        });
    }

    preloadSound(key) {
        console.log('Preloading sound: ' + key);
        this.load.audio(key, AssetsUtility.buildSoundPath(key));
    }

    drawDebugBorder(target, color = 0xff0000) {
        const bounds = target.getBounds();

        const graphics = this.add.graphics();
        graphics.lineStyle(2, color, 1);
        graphics.strokeRect(bounds.x, bounds.y, bounds.width, bounds.height);

        return graphics;
    }
}

