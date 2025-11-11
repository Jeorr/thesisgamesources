import BaseInitiatingEvent from "./BaseInitiatingEvent";

export default class BattleStartedOneVsOne extends BaseInitiatingEvent {
    constructor(data) {
        super(data);
    }

    /**
     * REQUIRED PARAMS:
     *  - this.data.battleData
     * OPTIONAL PARAMS:
     *  - this.data.userData
     */
    trigger() {
        super.trigger();

        let battleData = this.data.battleData || [];
        App().getEngine().getSceneManager().startOneVsOneBattleScene(battleData);
    }
}