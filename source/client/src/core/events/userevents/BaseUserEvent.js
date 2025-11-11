import Request from "../../Request";

export default class BaseUserEvent {
    constructor(data) {
        this.data = data;
    }

    trigger() {
        // Extend in child classes
    }

    buildAndSendRequest() {
        try {
            let request = new Request();
            request.buildFromUserEvent(this);
            request.process();
        } catch (error) {
            App().getEngine().getUI().showErrorMessage('', 'Unexpected error!');
        }
    }
}