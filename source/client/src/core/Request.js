import BaseUserEvent from "./events/userevents/BaseUserEvent";

export default class Request {
    constructor() {
        this.data = null;
    }

    buildFromUserEvent(userEvent) {
        if (!userEvent instanceof BaseUserEvent) {
            throw new Error('userEvent argument must be type of BaseUserEvent, ' + typeof userEvent + ' is given!');
        }

        this.data = {
            'event' : userEvent.constructor.name,
            'data' : userEvent.data
        };
    }

    process() {
        App().getConnection().sendData(this.data);
    }
}