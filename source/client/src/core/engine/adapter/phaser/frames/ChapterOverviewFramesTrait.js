import TranslationsUtility from "../../../../TranslationsUtility";
import AssetsUtility from "../../../../AssetsUtility";
import CONSTANTS from "../../../../Constants";
import UserClicksUseItemBtnEvent from "../../../../events/userevents/UserClicksUseItemBtnEvent";
import UserClicksShowIntroBtnEvent from "../../../../events/userevents/UserClicksShowIntroBtnEvent";

export default {
    enableChapterOverviewFrames(data) {
        const self = this;
        let chapteroverviewframes = App().getEngine().getUI().getHtmlComponentByKey('chapteroverviewframes');

        if (chapteroverviewframes) {
            App().getEngine().getUI().destroyHtmlElement('chapteroverviewframes');
        }

        chapteroverviewframes = App().getEngine().getUI().createHtmlComponent('chapteroverviewframes');

        this.updateChapterOverviewFrames(data);
    },

    updateChapterOverviewFrames(data) {
        const self = this;
        let chapteroverviewframes = App().getEngine().getUI().getHtmlComponentByKey('chapteroverviewframes');

        if (chapteroverviewframes) {
            let mainInfoBlock = chapteroverviewframes.getNode().getElementsByClassName('js-chapteroverview-main-info')[0];
            let goalsBlock = chapteroverviewframes.getNode().getElementsByClassName('js-chapteroverview-goals')[0];
            let mainInfoHeading = mainInfoBlock.querySelector('.js-chapteroverview-main-info-heading');
            let mainInfoDescription = mainInfoBlock.querySelector('.js-chapteroverview-main-info-description');
            let goalsHeading = goalsBlock.querySelector('.js-chapteroverview-goals-heading');
            let goalItemDummy = goalsBlock.querySelector('.js-chapteroverview-goals-list-item-dummy');
            let goalsList = goalsBlock.querySelector('.js-chapteroverview-goals-list');
            let runIntro = chapteroverviewframes.getNode().getElementsByClassName('js-chapteroverview-run-intro')[0];

            mainInfoHeading.innerText = data.name || '';
            mainInfoDescription.innerText = data.description || '';
            goalsHeading.innerText = TranslationsUtility.getTranslation('CHAPTER_GOALS');
            goalsList.innerHTML = '';

            if (data.goals) {
                for (let goalData of data.goals) {
                    let goalName = goalData.name;
                    let goalValueCurrent = goalData.current;
                    let goalValueNeeded= goalData.needed;
                    let newGoalItem = goalItemDummy.cloneNode(true);
                    let separator = '';

                    newGoalItem.querySelector('.goal-name').innerText = goalName;

                    if (goalValueCurrent !== '') {
                        newGoalItem.querySelector('.value-cur').innerText = goalValueCurrent;
                        separator = '/';
                    }
                    if (goalValueNeeded !== '') {
                        newGoalItem.querySelector('.value-needed').innerText = separator + goalValueNeeded;
                    }

                    newGoalItem.classList.remove('chapteroverview-goals-list-item-dummy', 'js-chapteroverview-goals-list-item-dummy');
                    goalsList.appendChild(newGoalItem);
                }
            }

            runIntro.onclick = () => {
                const userClicksShowIntroBtnEvent = new UserClicksShowIntroBtnEvent({});
                userClicksShowIntroBtnEvent.trigger();
            };
        }
    },
}


