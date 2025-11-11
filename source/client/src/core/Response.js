import EventsFactory from "./events/EventsFactory";

export default class Response {
    constructor() {
        this.data = null;
    }

    buildFromMessageEvent(messageEvent) {
        let data = null;

        App().debug(messageEvent);

        try {
            this.data = JSON.parse(messageEvent.data);
        } catch(e) {
            App().debug('Could not parse response data from server: ' + e);
        }
    }

    process() {
        let event;

        try {
            event = EventsFactory.createGameEventFromResponse(this);
            event.trigger();
        } catch (e){
            console.log(e)
        }
    }

    getParam(key) {
        if (!(key in this.data)) {
            return null;
        }

        return this.data[key];
    }

    getParams() {
        return this.data;
    }
}