export default class ObjectManager {
    constructor(adapter) {
        this.adapter = adapter;
    }

    getObjectOrigin(object) {
        return this.adapter.getObjectOrigin(object);
    }

    setObjectOrigin(object, x, y) {
        return this.adapter.setObjectOrigin(object, x, y);
    }

    getObjectZindex(object) {
        return this.adapter.getObjectZindex(object);
    }

    setObjectZindex(object, zIndex) {
        return this.adapter.setObjectZindex(object, zIndex);
    }

    getObjectDisplayOrigin(object) {
        return this.adapter.getObjectDisplayOrigin(object);
    }

    setObjectDisplayOrigin(object, x, y) {
        this.adapter.setObjectDisplayOrigin(object, x, y);
    }

    getObjectWidth(object) {
        return this.adapter.getObjectWidth(object);
    }

    setObjectWidth(object, width) {
        this.adapter.setObjectWidth(object, width);
    }

    getObjectHeight(object) {
        return this.adapter.getObjectHeight(object);
    }

    setObjectHeight(object, height) {   
        this.adapter.setObjectHeight(object, height);
    }

    getUnitPosition(unit) {
        return this.adapter.getUnitPosition(unit);
    }

    getUnitBounds(unit) {
        return this.adapter.getUnitBounds(unit);
    }

    setUnitPosition(unit, x, y) {
        this.adapter.setUnitPosition(unit, x, y);
    }

    getUnitScale(unit) {
        return this.adapter.getUnitScale(unit);
    }

    setUnitScale(unit, scale) {
        this.adapter.setUnitScale(unit, scale);
    }

    setVisible(object, visible) {
        this.adapter.setVisible(object, visible);
    }

    setMuted(object, muted) {
        this.adapter.setMuted(object, muted);
    }

    getMuted(object) {
        return this.adapter.getMuted(object);
    }

    getVolume(object) {
        return this.adapter.getVolume(object);
    }

    setVolume(object, volume) {
        this.adapter.setVolume(object, volume);
    }

    getUnitCurrentAnimationKey(unit) {
        return this.adapter.getUnitCurrentAnimationKey(unit);
    }

    getCurrentUnitAnimationKey(unit) {
        return this.adapter.getCurrentUnitAnimationKey(unit);
    }

    getVideoDuration(videoObject) {
        return this.adapter.getVideoDuration(videoObject);
    }

    getAudioDuration(audioObject) {
        return this.adapter.getAudioDuration(audioObject);
    }

    isAudioPlaying(audioObject) {
        return this.adapter.isAudioPlaying(audioObject);
    }

    isAudioPaused(audioObject) {
        return this.adapter.isAudioPaused(audioObject);
    }

    playObjectAnimation(unit, animation, repeat, force, fallbackToDefaultAfter = true) {
        this.adapter.playObjectAnimation(unit, animation, repeat, force, fallbackToDefaultAfter);
    }

    playObjectAnimationChain(unit, animationChain, force, fallbackToDefaultAfter = true) {
        this.adapter.playObjectAnimationChain(unit, animationChain, force, fallbackToDefaultAfter);
    }

    playVideo(videoObject) {
        this.adapter.playVideo(videoObject);
    }

    playAudio(audioObject) {
        this.adapter.playAudio(audioObject);
    }

    pauseVideo(videoObject) {
        this.adapter.pauseVideo(videoObject);
    }

    pauseAudio(audioObject) {
        this.adapter.pauseAudio(audioObject);
    }

    resumeVideo(videoObject) {
        this.adapter.resumeVideo(videoObject);
    }

    resumeAudio(audioObject) {
        this.adapter.resumeAudio(audioObject);
    }

    stopVideo(videoObject) {
        this.adapter.stopVideo(videoObject);
    }

    stopAudio(audioObject) {
        this.adapter.stopAudio(audioObject);
    }
    
    flipUnitX(unit, flip) {
        this.adapter.flipUnitX(unit, flip);
    }

    objectFitToScreen(object, type = 'cover') {
        this.adapter.objectFitToScreen(object, type);
    }

    objectClickEvent(object, func) {
        this.adapter.objectClickEvent(object, func);
    }

    objectTriggerClickEvent(object) {
        this.adapter.objectTriggerClickEvent(object);
    }

    /**
     *
     * @param unitData
     * @param scene
     * @returns {Unit}
     */
    addUnitOnScene(unitData, scene = null) {
        let unitId = unitData.id || null;

        if (null === scene) {
            scene = App().getEngine().getSceneManager().getCurrentScene();
        }

        if (!unitId) {
            throw new Error('Unit ID is missing in the given unit data!');
        }

        if (scene.getUnitById(unitId)) {
            throw new Error('Unit with the given ID already exists on the scene!');
        }

        let unit = this.adapter.addUnitOnScene(unitData, scene);
        unit.setData(unitData);
        unit.setZindex(100);
        scene.addUnit(unit);

        return unit;
    }

    /**
     * @param objectData
     * @param scene
     * @returns {*}
     */
    addAnimatedObjectOnScene(objectData, scene = null) {
        if (null === scene) {
            scene = App().getEngine().getSceneManager().getCurrentScene();
        }

        let animatedObject = this.adapter.addAnimatedObjectOnScene(objectData, scene);
        animatedObject.setData(objectData);
        animatedObject.setZindex(100);
        scene.addObject(animatedObject);

        return animatedObject;
    }

    addVideoObjectOnScene(objectData, scene = null) {
        if (null === scene) {
            scene = App().getEngine().getSceneManager().getCurrentScene();
        }

        let videoObject = this.adapter.addVideoObjectOnScene(objectData, scene);
        videoObject.setData(objectData);
        scene.addObject(videoObject);

        return videoObject;
    }

    addAudioObjectOnScene(objectData, scene = null) {
        if (null === scene) {
            scene = App().getEngine().getSceneManager().getCurrentScene();
        }

        let audioObject = this.adapter.addAudioObjectOnScene(objectData, scene);
        audioObject.setData(objectData);
        scene.addObject(audioObject);

        return audioObject;
    }

    addGlobalAudioObject(objectData) {
        let audioObject = this.adapter.addAudioObjectOnScene(objectData);
        audioObject.setData(objectData);

        return audioObject;
    }

    /**
     * @param objectData
     * @param scene
     * @returns {*}
     */
    addBarObjectOnScene(objectData, scene = null) {
        if (null === scene) {
            scene = App().getEngine().getSceneManager().getCurrentScene();
        }

        let barObject = this.adapter.addBarObjectOnScene(objectData, scene);
        barObject.setData(objectData);
        scene.addObject(barObject);

        return barObject;
    }

    removeObjectFromScene(object, scene = null) {
        if (null === scene) {
            scene = App().getEngine().getSceneManager().getCurrentScene();
        }

        scene.removeObjectByInternalId(object.getInternalId());
        object.remove();
    }

    addLocationsPathObjectOnScene(pathData, scene = null) {
        if (null === scene) {
            scene = App().getEngine().getSceneManager().getCurrentScene();
        }

        let locationsPath = this.adapter.addLocationsPathObjectOnScene(pathData, scene);
        locationsPath.setData(pathData);
        scene.addObject(locationsPath);

        return locationsPath;
    }
}
