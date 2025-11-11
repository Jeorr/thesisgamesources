import BaseGameEvent from "./BaseGameEvent";

export default class BattleEndedOneVsOne extends BaseGameEvent {
    constructor(data) {
        super(data);
    }

    trigger() {
        super.trigger();
        let enemies = App().getEngine().getBattleManager().getCurrentBattle().getEnemies();
        let friends = App().getEngine().getBattleManager().getCurrentBattle().getFriends();

        for (let unitId in enemies) {
            enemies[unitId].hideHpMpBars();
        }
        for (let unitId in friends) {
            friends[unitId].hideHpMpBars();
        }

        App().getEngine().getUI().enableBattleResults(this.data);
    }
}