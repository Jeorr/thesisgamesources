import AnimatedObject from "../AnimatedObject";
import Constants from "../../../Constants";

export default class Unit extends AnimatedObject {
    constructor(id, unitType) {
        super(unitType);
        this.id = id;
        this.unitType = unitType;
        this.bars = {};
    }

    getId() {
        return this.id;
    }

    getUnitType() {
        return this.unitType;
    }

    showHpMpBars(){
        let unitData = this.getData();
        let unitBounds = this.getBounds();
        let hpBarData = {
            type: 'hpBar',
            x: unitBounds.x,
            y: unitBounds.y,
            barWidth: unitBounds.width,
            barHeight: Constants.HP_BAR_OBJECT_HEIGHT,
            barColor: Constants.HP_BAR_OBJECT_COLOR,
            value: unitData.stats.hp,
            valueMax: unitData.stats.maxHp,
            attachToObject: this,
            objectValuePath: 'stats.hp',
            objectValueMaxPath: 'stats.maxHp',
            displacementX: Constants.HP_BAR_OBJECT_DISPLACEMENT_X,
            displacementY: Constants.HP_BAR_OBJECT_DISPLACEMENT_Y
        };
        let mpBarData = {
            type: 'mpBar',
            x: unitBounds.x,
            y: unitBounds.y,
            barWidth: unitBounds.width,
            barHeight: Constants.MP_BAR_OBJECT_HEIGHT,
            barColor: Constants.MP_BAR_OBJECT_COLOR,
            value: unitData.stats.mp,
            valueMax: unitData.stats.maxMp,
            objectValuePath: 'stats.mp',
            objectValueMaxPath: 'stats.maxMp',
            attachToObject: this,
            displacementX: Constants.MP_BAR_OBJECT_DISPLACEMENT_X,
            displacementY: Constants.MP_BAR_OBJECT_DISPLACEMENT_Y
        };
        let hpBar = this.objectManager.addBarObjectOnScene(hpBarData);
        let mpBar = this.objectManager.addBarObjectOnScene(mpBarData);
        this.bars['hpBar'] = hpBar;
        this.bars['mpBar'] = mpBar;
    }

    hideHpMpBars() {
        for (let barType in this.bars) {
            let bar = this.bars[barType];
            delete this.bars[barType];
            App().getEngine().getObjectManager().removeObjectFromScene(bar);
        }
    }

    playAnimation(animation, repeat = false, force = false, fallbackToDefaultAfter = true) {
        this.objectManager.playObjectAnimation(this, animation, repeat, force, fallbackToDefaultAfter);
    }

    playAnimationChain(animationChain, force, fallbackToDefaultAfter = true) {
        this.objectManager.playObjectAnimationChain(this, animationChain, force, fallbackToDefaultAfter);
    }
}
