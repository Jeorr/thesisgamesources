import BaseUserEvent from "./BaseUserEvent";

export default class UserClicksActivateTalentBtnEvent extends BaseUserEvent {
    constructor(data) {
        super(data);
    }

    trigger() {
        super.trigger();

        this.buildAndSendRequest();
    }
}