import BaseUserEvent from "./BaseUserEvent";

export default class UserClicksApplyElixirBtnEvent extends BaseUserEvent {
    constructor(data) {
        super(data);
    }

    trigger() {
        super.trigger();

        this.buildAndSendRequest();
    }
}