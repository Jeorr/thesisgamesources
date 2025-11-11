import BaseUserEvent from "./BaseUserEvent";

export default class UserCancelBattleSearchEvent extends BaseUserEvent {
    constructor(data) {
        super(data);
    }

    trigger() {
        super.trigger();

        this.buildAndSendRequest();
    }
}