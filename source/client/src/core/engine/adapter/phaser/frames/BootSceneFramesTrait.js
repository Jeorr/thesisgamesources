import UserInitGameEvent from "../../../../events/userevents/UserInitGameEvent";

export default {
    enableBootSceneLoadingFrames(data) {
        const self = this;
        let bootsceneframes = App().getEngine().getUI().getHtmlComponentByKey('bootsceneframes');

        if (bootsceneframes) {
            App().getEngine().getUI().destroyHtmlElement('bootsceneframes');
        }

        bootsceneframes = App().getEngine().getUI().createHtmlComponent('bootsceneframes');

        let startGameButton = bootsceneframes.getNode().querySelector('.js-action-start-game');
        
        startGameButton.onclick = () => {
            let userInitGameEvent = new UserInitGameEvent({});
            userInitGameEvent.trigger();
        };

        this.updateBootSceneLoadingFrames(data);
    },

    updateBootSceneLoadingFrames(data) {
        const self = this;
        let bootsceneframes = App().getEngine().getUI().getHtmlComponentByKey('bootsceneframes');
        let loadingWrapper = bootsceneframes.getNode().querySelector('.js-loading-wrapper');
        let loadingBarInner = loadingWrapper.querySelector('.js-loadingbar-inner');
        let loadingBarFrame = loadingWrapper.querySelector('.js-loadingbar-frame');
        let startGameButton = bootsceneframes.getNode().querySelector('.js-action-start-game');
        let progress = data.progress || 0;
        let complete = data.complete || false;

        if (complete) {
            startGameButton.classList.remove('disabled');
            startGameButton.disabled = false;
        }

        loadingWrapper.dataset.progress = progress;
        loadingBarInner.style.width = progress + '%';
    },
}
