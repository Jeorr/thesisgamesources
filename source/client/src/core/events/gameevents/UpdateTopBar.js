import BaseGameEvent from "./BaseGameEvent";

export default class UpdateTopBar extends BaseGameEvent {
    constructor(data) {
        super(data);
    }

    trigger() {
        super.trigger();

        let userData = this.data.userData || [];

        if (userData) {
            App().getEngine().getUI().updateTopBar(userData);
        }
    }
}