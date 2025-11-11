export default class Debugger {
    constructor() {
        this.debugEnabled = true;
    }

    debug(...data) {
        if (!this.debugEnabled) {
            return;
        }

        console.log(...data)
    }
}