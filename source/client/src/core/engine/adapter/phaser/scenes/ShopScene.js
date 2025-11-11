import BaseScene from "./BaseScene";
import AssetsUtility from "../../../../AssetsUtility";
import CONSTANTS from "../../../../Constants";
import UserClicksBuyItemBtnEvent from "../../../../events/userevents/UserClicksBuyItemBtnEvent";
import UserRequestsShopDataUpdateEvent from "../../../../events/userevents/UserRequestsShopDataUpdateEvent";
import SpecialEffectFactory from "../../../objects/specialeffects/SpecialEffectFactory";

export default class ShopScene extends BaseScene {
    SHOP_SELLER_UNIT_SPRITE = 'shopseller';
    SHOP_SELLER_UNIT_POSITION = [560, 550];
    SHOP_SELLER_UNIT_SCALE = 0.9;
    SHOP_SELLER_SHADOW_OFFSET = [0, 0];
    SHOP_CORNER_PLANT_SPRITE = 'shopcornerplant';
    SHOP_CORNER_PLANT_POSITION = [0, 800];
    SHOP_CORNER_PLANT_SCALE = 1.1;
    SHOP_CORNER_GLOW_POSITION = [955, 185];
    SHOP_CORNER_GLOW_SCALE = 0.4;

    constructor() {
        super({key: 'ShopScene'});

        this.mapObj = null;
        this.timer = null;
        this.structureCode = null;
    }
    preload() {
        super.preload();

        this.load.image('shopzone', AssetsUtility.buildZonePath('shop'));
        this.load.html('zoneframes', AssetsUtility.buildHtmlComponentPath('zoneframes'));
        this.load.html('shopframes', AssetsUtility.buildHtmlComponentPath('shopframes'));

        let sellerAseprite = this.SHOP_SELLER_UNIT_SPRITE;
        this.load.aseprite(
            sellerAseprite,
            AssetsUtility.buildStructurePngPath(sellerAseprite),
            AssetsUtility.buildStructureJsonPath(sellerAseprite)
        );
        let shopCornerPlantAseprite = this.SHOP_CORNER_PLANT_SPRITE;
        this.load.aseprite(
            shopCornerPlantAseprite,
            AssetsUtility.buildObjectPngPath(shopCornerPlantAseprite),
            AssetsUtility.buildObjectJsonPath(shopCornerPlantAseprite)
        );
    }

    create(data) {
        App().getEngine().getUI().disableLoadingScreen();
        super.create();

        let map = this.createStaticMap('shopzone');
        this.mapObj = map;
        this.timer = 0;
        this.structureCode = data.code;

        let shopsellerAdjustedCoords = this.adjustCoordinatesToScaledMap(this.SHOP_SELLER_UNIT_POSITION[0], this.SHOP_SELLER_UNIT_POSITION[1]);
        let shopsellerData = {
            'sprite': this.SHOP_SELLER_UNIT_SPRITE,
            'x': shopsellerAdjustedCoords.x,
            'y': shopsellerAdjustedCoords.y,
            'scale': this.adjustValueToScaledMap(this.SHOP_SELLER_UNIT_SCALE),
        };
        let shopseller = App().getEngine().getObjectManager().addAnimatedObjectOnScene(shopsellerData);
        shopseller.setScale(shopsellerData.scale || 1);
        shopseller.playAnimation('default', true, true);

        let shopcornerplantAdjustedCoords = this.adjustCoordinatesToScaledMap(this.SHOP_CORNER_PLANT_POSITION[0], this.SHOP_CORNER_PLANT_POSITION[1]);
        let shopcornerplantData = {
            'sprite': this.SHOP_CORNER_PLANT_SPRITE,
            'x': shopcornerplantAdjustedCoords.x,
            'y': shopcornerplantAdjustedCoords.y,
            'scale': this.adjustValueToScaledMap(this.SHOP_CORNER_PLANT_SCALE),
        };
        let shopcornerplant = App().getEngine().getObjectManager().addAnimatedObjectOnScene(shopcornerplantData);
        shopcornerplant.setScale(shopcornerplantData.scale || 1);
        shopcornerplant.playAnimation('default', true, true);
        shopcornerplant.setOrigin(0, 1);

        SpecialEffectFactory.createSpecialEffectOnUnit('shadowgeneral', shopseller, null, {
            offsetX: this.adjustValueToScaledMap(this.SHOP_SELLER_SHADOW_OFFSET[0]),
            offsetY: this.adjustValueToScaledMap(this.SHOP_SELLER_SHADOW_OFFSET[1]),
        });
        
        let simpleglowAdjustedCoords = this.adjustCoordinatesToScaledMap(this.SHOP_CORNER_GLOW_POSITION[0], this.SHOP_CORNER_GLOW_POSITION[1]);
        SpecialEffectFactory.createSpecialEffect('simpleglow', {
            'x': simpleglowAdjustedCoords.x,
            'y': simpleglowAdjustedCoords.y,
            'scale': this.adjustValueToScaledMap(this.SHOP_CORNER_GLOW_SCALE),
        });

        App().getEngine().getUI().enableZoneFrames();
        App().getEngine().getUI().enableShopFrames(data)
    }

    update(time, delta) {
        super.update();

        this.timer += delta;

        if (this.timer >= 1000) {
            this.timer = 0;
            this.refreshShopItems();
        }
    }

    refreshShopItems() {
        let userRequestsShopDataUpdateEvent = new UserRequestsShopDataUpdateEvent({
            'structureCode': this.structureCode,
        });
        userRequestsShopDataUpdateEvent.trigger();
    }
}


