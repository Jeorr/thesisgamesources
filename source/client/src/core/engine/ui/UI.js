import PhaserAdapter from "../adapter/phaser/PhaserAdapter";
import DomElement from "../html/DomElement";

export default class UI {
    constructor(adapter) {
        /**
         * @type {PhaserAdapter}
         */
        this.adapter = adapter;
        this.elements = {};
    }

    createHtmlComponent(key) {
        // HTML components are unique by key, so clear the existing one if it exists
        for (let elementInternalId in this.elements) {
            let element = this.elements[elementInternalId];

            if (element.getKey() === key) {
                element.remove();
                delete this.elements[elementInternalId];
            }
        }

        let component = this.adapter.createHtmlComponent(key);
        this.elements[component.getInternalId()] = component;

        this.enableTooltipsForHtmlElement(component);
        
        return component;
    }

    getHtmlComponentByKey(key) {
        for (let elementInternalId in this.elements) {
            let element = this.elements[elementInternalId];
            
            if (element.getKey() === key) {
                return element;
            }
        }
        
        return null;
    }

    clearHtmlElementsOnSceneChange() {
        for (let elementInternalId in this.elements) {
            let element = this.elements[elementInternalId];
            
            if (!element.getPreserveOnSceneChange()) {
                element.remove();
                delete this.elements[elementInternalId];
            }
        }
    }
    
    destroyHtmlElement(element) {
        let elementInternalId = element.getInternalId();

        if (this.elements[elementInternalId]) {
            element.remove();
            delete this.elements[elementInternalId];
        }
    }

    enableTooltipsForHtmlElement(element) {
        let self = this;
        
        if (!(element instanceof DomElement)) {
            return;
        }

        let node = element.getNode();

        if (!node) {
            return;
        }

        // enable tooltips
        node.onclick = function (event) {
            console.log(event.target);
            // find first parent with data-tooltip attribute
            let tooltip = event.target.closest('[data-tooltip]');
            if (tooltip) {
                console.log(tooltip);
                self.showTooltip('', tooltip.dataset.tooltip, tooltip);
            } else {
                let currentTooltip = App().getEngine().getUI().getHtmlComponentByKey('tooltip');
                if (currentTooltip) {
                    App().getEngine().getUI().destroyHtmlElement(currentTooltip);
                }
            }
        }
    }

    hideHtmlElement(element) {
        this.adapter.hideHtmlElement(element);
    }

    showHtmlElement(element) {
        this.adapter.showHtmlElement(element);
    }

    hideAllHtmlElements() {
        for (let elementInternalId in this.elements) {
            let element = this.elements[elementInternalId];
            
            this.adapter.hideHtmlElement(element);
        }
    }

    showAllHtmlElements() {
        for (let elementInternalId in this.elements) {
            let element = this.elements[elementInternalId];
            
            this.adapter.showHtmlElement(element);
        }
    }

    enableLoadingScreen() {
        this.adapter.enableLoadingScreen();
    }

    disableLoadingScreen() {
        this.adapter.disableLoadingScreen();
    }

    enableTopBar(data) {
        this.adapter.enableTopBar(data);
    }

    updateTopBar(data) {
        this.adapter.updateTopBar(data);
    }

    showErrorMessage(title, message, interruptGame = true) {
        this.adapter.showErrorMessage(title, message, interruptGame);
    }

    showFlashMessage(title, message) {
        this.adapter.showFlashMessage(title, message);
    }

    showTooltip(title, message, node) {
        this.adapter.showTooltip(title, message, node);
    }

    showUnitFullInfo(data) {
        this.adapter.showUnitFullInfo(data);
    }

    showLocationInfo(data) {
        this.adapter.showLocationInfo(data);
    }

    showLocationNpcInfo(data) {
        this.adapter.showLocationNpcInfo(data);
    }

    showLocationStructureInfo(data) {
        this.adapter.showLocationStructureInfo(data);
    }

    enableMapFrames(data) {
        this.adapter.enableMapFrames(data);
    }

    enableQuickAccessBottomBar(data) {
        this.adapter.enableQuickAccessBottomBar(data);
    }

    addActionToQuickAccessBottomBar(actionData) {
        return this.adapter.addActionToQuickAccessBottomBar(actionData);
    }

    makeQuickAccessBottomBarActionActive(type) {
        this.adapter.makeQuickAccessBottomBarActionActive(type);
    }

    enableBattleFrames(ownData, enemyData) {
        this.adapter.enableBattleFrames(ownData, enemyData);
    }

    enableBattleResults(data) {
        this.adapter.enableBattleResults(data);
    }

    enableZoneFrames(data) {
        this.adapter.enableZoneFrames(data);
    }
    enableArenaFrames(data) {
        this.adapter.enableArenaFrames(data);
    }

    updateArenaFrames(data) {
        this.adapter.updateArenaFrames(data);
    }

    enableShopFrames(data) {
        this.adapter.enableShopFrames(data);
    }

    updateShopFrames(data) {
        this.adapter.updateShopFrames(data);
    }

    enableLaboratoryFrames(data) {
        this.adapter.enableLaboratoryFrames(data);
    }

    updateLaboratoryFrames(data) {
        this.adapter.updateLaboratoryFrames(data);
    }

    enableBarracksFrames(data) {
        this.adapter.enableBarracksFrames(data);
    }

    updateBarracksFrames(data, activePetData) {
        this.adapter.updateBarracksFrames(data);
    }

    showBarracksInfoWindow(category) {
        this.adapter.showBarracksInfoWindow(category);
    }

    updateBarrackTalentInfoWindow(data)  {
        this.adapter.updateBarrackTalentInfoWindow(data);
    }

    enableInventoryFrames(data) {
        this.adapter.enableInventoryFrames(data);
    }

    updateInventoryFrames(data) {
        this.adapter.updateInventoryFrames(data);
    }

    enableChapterOverviewFrames(data) {
        this.adapter.enableChapterOverviewFrames(data);
    }

    updateChapterOverviewFrames(data) {
        this.adapter.updateChapterOverviewFrames(data);
    }

    hideShopModal(data) {
        this.adapter.hideShopModal(data);
    }

    hideLaboratoryModal(data) {
        this.adapter.hideLaboratoryModal(data);
    }

    hideBarracksModal(data) {
        this.adapter.hideBarracksModal(data);
    }

    hideInventoryModal(data) {
        this.adapter.hideInventoryModal(data);
    }

    showLocationPointInvestigationResults(data) {
        this.adapter.showLocationPointInvestigationResults(data);
    }

    showLocationPointInvestigationIcon(data) {
        this.adapter.showLocationPointInvestigationIcon(data);
    }

    showCli(data) {
        this.adapter.showCli(data);
    }

    updateCli(data) {
        this.adapter.updateCli(data);
    }

    showFirstHeroSelect(data) {
        this.adapter.showFirstHeroSelect(data);
    }

    hideFirstHeroSelect(data) {
        this.adapter.hideFirstHeroSelect(data);
    }

    showChatFrames(data) {
        this.adapter.showChatFrames(data);
    }

    hideChatFrames(data) {
        this.adapter.hideChatFrames(data);
    }

    updateChatMessage(data)  {
        this.adapter.updateChatMessage(data);
    }

    showSettingsFrames(data) {
        this.adapter.showSettingsFrames(data);
    }

    hideSettingsFrames(data) {
        this.adapter.hideSettingsFrames(data);
    }

    enableChapterIntroFrames(data) {
        this.adapter.enableChapterIntroFrames(data);
    }

    updateChapterIntroFrames(data) {
        this.adapter.updateChapterIntroFrames(data);
    }

    enableBootSceneLoadingFrames(data) {
        this.adapter.enableBootSceneLoadingFrames(data);
    }

    updateBootSceneLoadingFrames(data) {
        this.adapter.updateBootSceneLoadingFrames(data);
    }
}
