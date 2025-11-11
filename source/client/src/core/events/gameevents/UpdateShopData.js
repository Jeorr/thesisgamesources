import BaseGameEvent from "./BaseGameEvent";

export default class UpdateShopData extends BaseGameEvent {
    constructor(data) {
        super(data);
    }

    trigger() {
        super.trigger();

        App().getEngine().getUI().updateShopFrames(this.data);
    }
}