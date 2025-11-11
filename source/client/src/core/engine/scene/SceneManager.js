import Scene from "./Scene";
import UpdateCliDataEvent from "../../events/userevents/UpdateCliDataEvent";

export default class SceneManager {
    constructor(adapter) {
        this.adapter = adapter;
        this.currentScene = null;
    }

    /**
     *
     * @returns {Scene}
     */
    getCurrentScene() {
        return this.currentScene;
    }

    setCurrentScene(scene) {
        this.currentScene = scene;
    }

    loadInitialScene() {
        this.adapter.loadInitialScene();
    }

    startOneVsOneBattleScene(data) {
        this.adapter.startOneVsOneBattleScene(data);
    }

    startLocationsMapScene(data) {
        this.adapter.startLocationsMapScene(data);
    }

    startLocationDetailsScene(data) {
        this.adapter.startLocationDetailsScene(data);
    }

    startArenaScene(data) {
        this.adapter.startArenaScene(data);
    }

    startShopScene(data) {
        this.adapter.startShopScene(data);
    }

    startLaboratoryScene(data) {
        this.adapter.startLaboratoryScene(data);
    }

    startBarracksScene(data) {
        this.adapter.startBarracksScene(data);
    }

    startHeroSelectScene(data) {
        this.adapter.startHeroSelectScene(data);
    }

    startInventoryScene(data) {
        this.adapter.startInventoryScene(data);
    }

    startChapterOverviewScene(data) {
        this.adapter.startChapterOverviewScene(data);
    }

    startIntroScene(data) {
        this.adapter.startIntroScene(data);
    }
}
