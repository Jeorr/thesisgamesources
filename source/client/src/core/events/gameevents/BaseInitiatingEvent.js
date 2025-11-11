/**
 * Initiating Event is an event that may (or may not) be triggered first on game start. So if specific parameters are given -
 * it does some initial stuff like enabling top bar etc
 */
export default class BaseInitiatingEvent {
    constructor(data) {
        this.data = data;
    }

    /**
     * OPTIONAL PARAMS:
     *  - this.data.userData
     */
    trigger() {
        App().debug(this.data);
        
        let userData = this.data.userData || [];

        if (userData && Object.keys(userData).length > 0) {
            console.log('324324234', userData);
            App().getEngine().getUI().enableTopBar(userData);
        }

        App().getEngine().getUI().disableLoadingScreen();
    }
}