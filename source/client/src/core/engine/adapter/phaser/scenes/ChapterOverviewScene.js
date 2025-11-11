import BaseScene from "./BaseScene";
import AssetsUtility from "../../../../AssetsUtility";
import CONSTANTS from "../../../../Constants";
import UserClicksBuyItemBtnEvent from "../../../../events/userevents/UserClicksBuyItemBtnEvent";
import UserRequestsShopDataUpdateEvent from "../../../../events/userevents/UserRequestsShopDataUpdateEvent";
import SpecialEffectFactory from "../../../objects/specialeffects/SpecialEffectFactory";

export default class ChapterOverviewScene extends BaseScene {
    constructor() {
        super({key: 'ChapterOverviewScene'});

        this.mapObj = null;
    }
    preload() {
        super.preload();

        this.load.image('chapteroverviewzone', AssetsUtility.buildZonePath('chapteroverview'));
        this.load.html('zoneframes', AssetsUtility.buildHtmlComponentPath('zoneframes'));
        this.load.html('chapteroverviewframes', AssetsUtility.buildHtmlComponentPath('chapteroverviewframes'));

        let cornerPlantAseprite = CONSTANTS.CHAPTEROVERVIEW_CORNER_PLANT_SPRITE;
        this.load.aseprite(
            cornerPlantAseprite,
            AssetsUtility.buildObjectPngPath(cornerPlantAseprite),
            AssetsUtility.buildObjectJsonPath(cornerPlantAseprite)
        );
    }

    create(data) {
        App().getEngine().getUI().disableLoadingScreen();
        super.create();

        this.mapObj = this.createStaticMap('chapteroverviewzone');

        let overviewData = data.overview;

        let cornerplantAdjustedCoords = this.adjustCoordinatesToScaledMap(CONSTANTS.CHAPTEROVERVIEW_CORNER_PLANT_POSITION[0], CONSTANTS.CHAPTEROVERVIEW_CORNER_PLANT_POSITION[1]);
        let cornerplantData = {
            'sprite': CONSTANTS.CHAPTEROVERVIEW_CORNER_PLANT_SPRITE,
            'x': cornerplantAdjustedCoords.x,
            'y': cornerplantAdjustedCoords.y,
            'scale': this.adjustValueToScaledMap(CONSTANTS.CHAPTEROVERVIEW_CORNER_PLANT_SCALE),
        };
        let cornerplant = App().getEngine().getObjectManager().addAnimatedObjectOnScene(cornerplantData);
        cornerplant.setScale(cornerplantData.scale || 1);
        cornerplant.playAnimation('default', true, true);
        cornerplant.setOrigin(0, 1);

        App().getEngine().getUI().enableZoneFrames();
        App().getEngine().getUI().enableChapterOverviewFrames(overviewData);
        App().getEngine().getMusicManager().playMusicList('maincitymusiclist');
    }

    update(time, delta) {
        super.update();
    }
}


