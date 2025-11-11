import BaseUserEvent from "./BaseUserEvent";

export default class UserClicksAcceptDraftMessageEvent extends BaseUserEvent {
    constructor(data) {
        super(data);
    }

    trigger() {
        super.trigger();

        this.buildAndSendRequest();
    }
}