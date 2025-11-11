import phaser from '../node_modules/phaser/dist/phaser'
import App from './core/MainApp.js';

(function(){
    var global = window || global;

    /**
     * @returns {MainApp}
     */
    global.App = function() {
        return App;
    }

    global.App().init();
})();
