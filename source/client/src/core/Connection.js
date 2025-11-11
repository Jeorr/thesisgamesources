import Response from "./Response";
import CONSTANTS from "./Constants";

export default class Connection {
    constructor() {
        this.socket = null;
        this.lastResponse = null;
        this.connectionEstablished = false;
    }

    init() {
        let self = this;
        self.socket = new WebSocket(CONSTANTS.API_URL);

        self.socket.onopen = function (e) {
            App().debug('Connection established: ', this);
            self.connectionEstablished = true;
        };

        self.socket.onmessage = function (event) {
            self.lastResponse = new Response();
            self.lastResponse.buildFromMessageEvent(event);
            self.lastResponse.process();
        };

        self.socket.onclose = function (event) {
            self.connectionEstablished = false;
            if (event.wasClean) {
                App().debug('Connection closed cleanly');
            } else {
                App().debug('Connection closed with error');
            }
        };

        self.socket.onerror = function (error) {
            self.connectionEstablished = false;
            App().debug('Connection error: ', error.message);
        };
    }

    sendData(data) {
        if (this.connectionEstablished) {
            App().debug('Sending data to server: ', data);
            this.socket.send(JSON.stringify(data));
        } else {
            App().debug('Could not send data to server: connection is not established', data);
            throw new Error('Could not send data to server: connection is not established');
        }
    }
}