import BaseUserEvent from "./BaseUserEvent";

export default class UpdateCliDataEvent extends BaseUserEvent {
    constructor(data) {
        super(data);
    }

    trigger() {
        super.trigger();

        this.buildAndSendRequest();
    }
}