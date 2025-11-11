import BaseGameEvent from "./BaseGameEvent";

export default class PlayerOpensChatsOverview extends BaseGameEvent {
    constructor(data) {
        super(data);
    }

    trigger() {
        super.trigger();

        App().getEngine().getUI().showChatFrames(this.data);
    }
}