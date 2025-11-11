export default class BaseGameEvent {
    constructor(data) {
        this.data = data;
    }

    trigger() {
        App().debug(this.data);
        // Extend in child classes
    }
}