import Engine from './Engine';
import Connection from './Connection';
import Debugger from "./Debugger";

class MainApp {
    constructor() {
        this.initialized = false;
        this.engine = null;
        this.connection = null;
        this.debugger = null;
    }

    /**
     *
     * @throws Error
     */
    init() {
        if (this.initialized){
            throw new Error('App is already initialized!');
        }

        this.initialized = true;
        this.engine = new Engine();
        this.connection = new Connection();
        this.debugger = new Debugger();

        this.engine.init();
        this.connection.init();
    }

    /**
     *
     * @returns {Connection}
     */
    getConnection() {
        return this.connection;
    }

    /**
     *
     * @param data
     * @returns {*}
     */
    debug(...data) {
        return this.debugger.debug(...data);
    }

    getEngine() {
        return this.engine;
    }
}

const App = new MainApp();
export default App;