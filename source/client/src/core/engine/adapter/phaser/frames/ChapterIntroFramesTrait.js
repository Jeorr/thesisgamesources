import TranslationsUtility from "../../../../TranslationsUtility";
import AssetsUtility from "../../../../AssetsUtility";
import CONSTANTS from "../../../../Constants";
import { onHold } from "../../../../Misc";

export default {
    enableChapterIntroFrames(data) {
        const self = this;
        let chapterintroframes = App().getEngine().getUI().getHtmlComponentByKey('chapterintroframes');

        if (chapterintroframes) {
            App().getEngine().getUI().destroyHtmlElement('chapterintroframes');
        }

        chapterintroframes = App().getEngine().getUI().createHtmlComponent('chapterintroframes');

        this.updateChapterIntroFrames(data);
    },

    updateChapterIntroFrames(data) {
        const self = this;
        let chapterintroframes = App().getEngine().getUI().getHtmlComponentByKey('chapterintroframes');

        if (chapterintroframes) {
            let introBackground = chapterintroframes.getNode().querySelector('.js-chapterintro-background');
            let introTextContainer = chapterintroframes.getNode().querySelector('.js-chapterintro-text-container');
            let introText = introTextContainer.querySelector('.js-chapterintro-text');
            let skipIntro = chapterintroframes.getNode().querySelector('.js-chapterintro-skip-intro');
            let skipIntroText = skipIntro.querySelector('.button-text');
            
            skipIntroText.innerText = TranslationsUtility.getTranslation('BUTTONS.SKIP');

            if (data.blackmask === true) {
                introBackground.classList.add('visible');
            } else if (data.blackmask === false) {
                introBackground.classList.remove('visible');
            }


            if ('text' in data) {
                introText.innerText = data.text;
            }

            onHold(skipIntro, 1000, () => {
                skipIntro.classList.add('holding');
            }, () => {
                console.log('skipIntro');
                skipIntro.disabled = true;
                let currentScene = App().getEngine().getSceneManager().getCurrentScene();
                let intro = currentScene.getOriginal().intro;
                intro.introEnd(true);
            }, () => {
                skipIntro.classList.remove('holding');
            });
        }
    },
}


