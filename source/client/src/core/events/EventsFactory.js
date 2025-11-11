import PlayerEntersTheWorld from "./gameevents/PlayerEntersTheWorld";
import PlayerEntersTerritory from "./gameevents/PlayerEntersTerritory";
import ErrorAuthentication from "./gameevents/ErrorAuthentication";
import DisplayOwnPetFullInfo from "./gameevents/DisplayOwnPetFullInfo";
import ErrorGeneral from "./gameevents/ErrorGeneral";
import BattleStartedOneVsOne from "./gameevents/BattleStartedOneVsOne";
import ErrorBattleNotFound from "./gameevents/ErrorBattleNotFound";
import BattleUnitUsesSkill from "./gameevents/BattleUnitUsesSkill";
import BattleUnitStartsSkillAnimation from "./gameevents/BattleUnitStartsSkillAnimation";
import BattleEndedOneVsOne from "./gameevents/BattleEndedOneVsOne";
import InitGameTranslations from "./gameevents/InitGameTranslations";
import BattleUpdateUnitData from "./gameevents/BattleUpdateUnitData";
import BattleUnitBuffTick from "./gameevents/BattleUnitBuffTick";
import PlayerChangesLocation from "./gameevents/PlayerChangesLocation";
import PlayerStartsLocationChange from "./gameevents/PlayerStartsLocationChange";
import PlayerEntersLocationDetails from "./gameevents/PlayerEntersLocationDetails";
import PlayerGetsLocationInvestigationResults from "./gameevents/PlayerGetsLocationInvestigationResults";
import InitCli from "./gameevents/InitCli";
import UpdateCli from "./gameevents/UpdateCli";
import UpdateTopBar from "./gameevents/UpdateTopBar";
import PlayerEntersArena from "./gameevents/PlayerEntersArena";
import PlayerStartsBattleSearch from "./gameevents/PlayerStartsBattleSearch";
import PlayerCancelsBattleSearch from "./gameevents/PlayerCancelsBattleSearch";
import PlayerEntersShop from "./gameevents/PlayerEntersShop";
import UpdateShopData from "./gameevents/UpdateShopData";
import BattleUnitEndsSkillCasting from "./gameevents/BattleUnitEndsSkillCasting";
import PlayerBuysItemInShop from "./gameevents/PlayerBuysItemInShop";
import DisplayFlashMessage from "./gameevents/DisplayFlashMessage";
import PlayerSelectsFirstHero from "./gameevents/PlayerSelectsFirstHero";
import PlayerEntersElixirLab from "./gameevents/PlayerEntersElixirLab";
import PlayerCreatesItemInLab from "./gameevents/PlayerCreatesItemInLab";
import PlayerEntersBarracks from "./gameevents/PlayerEntersBarracks";
import PlayerOpensBarracksInfoWindowStats from "./gameevents/PlayerOpensBarracksInfoWindowStats";
import PlayerOpensBarracksInfoWindowSkills from "./gameevents/PlayerOpensBarracksInfoWindowSkills";
import PlayerOpensBarracksInfoWindowElixirs from "./gameevents/PlayerOpensBarracksInfoWindowElixirs";
import PlayerOpensBarracksInfoWindowApplyElixir from "./gameevents/PlayerOpensBarracksInfoWindowApplyElixir";
import PlayerAppliesElixir from "./gameevents/PlayerAppliesElixir";
import PlayerClearsElixir from "./gameevents/PlayerClearsElixir";
import PlayerOpensBarracksInfoWindowTalents from "./gameevents/PlayerOpensBarracksInfoWindowTalents";
import PlayerActivatesTalent from "./gameevents/PlayerActivatesTalent";
import PlayerOpensInventory from "./gameevents/PlayerOpensInventory";
import PlayerUsesItem from "./gameevents/PlayerUsesItem";
import PlayerOpensChapterOverview from "./gameevents/PlayerOpensChapterOverview";
import PlayerOpensChatsOverview from "./gameevents/PlayerOpensChatsOverview";
import PlayerOpensChatDetails from "./gameevents/PlayerOpensChatDetails";
import PlayerAcceptsDraftMessage from "./gameevents/PlayerAcceptsDraftMessage";
import PlayerOpensSettings from "./gameevents/PlayerOpensSettings";
import SettingsSaved from "./gameevents/SettingsSaved";
import PlayerStartsChapterIntro from "./gameevents/PlayerStartsChapterIntro";

export default class EventsFactory {
    static classMapGameEvents = {
        'PlayerEntersTheWorld': PlayerEntersTheWorld,
        'PlayerEntersTerritory': PlayerEntersTerritory,
        'PlayerChangesLocation': PlayerChangesLocation,
        'PlayerStartsLocationChange': PlayerStartsLocationChange,
        'PlayerGetsLocationInvestigationResults': PlayerGetsLocationInvestigationResults,
        'InitGameTranslations': InitGameTranslations,
        'InitCli': InitCli,
        'UpdateCli': UpdateCli,
        'UpdateTopBar': UpdateTopBar,
        'DisplayFlashMessage': DisplayFlashMessage,
        'ErrorAuthentication': ErrorAuthentication,
        'ErrorGeneral': ErrorGeneral,
        'ErrorBattleNotFound': ErrorBattleNotFound,
        'DisplayOwnPetFullInfo': DisplayOwnPetFullInfo,
        'BattleStartedOneVsOne': BattleStartedOneVsOne,
        'BattleUnitUsesSkill': BattleUnitUsesSkill,
        'BattleUnitEndsSkillCasting': BattleUnitEndsSkillCasting,
        'BattleUpdateUnitData': BattleUpdateUnitData,
        'BattleUnitBuffTick': BattleUnitBuffTick,
        'BattleUnitStartsSkillAnimation': BattleUnitStartsSkillAnimation,
        'BattleEndedOneVsOne': BattleEndedOneVsOne,
        'PlayerEntersLocationDetails': PlayerEntersLocationDetails,
        'PlayerEntersArena': PlayerEntersArena,
        'PlayerEntersShop': PlayerEntersShop,
        'PlayerEntersElixirLab': PlayerEntersElixirLab,
        'PlayerEntersBarracks': PlayerEntersBarracks,
        'PlayerStartsBattleSearch': PlayerStartsBattleSearch,
        'PlayerCancelsBattleSearch': PlayerCancelsBattleSearch,
        'UpdateShopData': UpdateShopData,
        'PlayerBuysItemInShop': PlayerBuysItemInShop,
        'PlayerCreatesItemInLab': PlayerCreatesItemInLab,
        'PlayerSelectsFirstHero': PlayerSelectsFirstHero,
        'PlayerOpensBarracksInfoWindowStats': PlayerOpensBarracksInfoWindowStats,
        'PlayerOpensBarracksInfoWindowSkills': PlayerOpensBarracksInfoWindowSkills,
        'PlayerOpensBarracksInfoWindowElixirs': PlayerOpensBarracksInfoWindowElixirs,
        'PlayerOpensBarracksInfoWindowApplyElixir': PlayerOpensBarracksInfoWindowApplyElixir,
        'PlayerOpensBarracksInfoWindowTalents': PlayerOpensBarracksInfoWindowTalents,
        'PlayerAppliesElixir': PlayerAppliesElixir,
        'PlayerClearsElixir': PlayerClearsElixir,
        'PlayerActivatesTalent': PlayerActivatesTalent,
        'PlayerOpensInventory': PlayerOpensInventory,
        'PlayerUsesItem': PlayerUsesItem,
        'PlayerOpensChapterOverview': PlayerOpensChapterOverview,
        'PlayerOpensChatsOverview': PlayerOpensChatsOverview,
        'PlayerOpensChatDetails': PlayerOpensChatDetails,
        'PlayerAcceptsDraftMessage': PlayerAcceptsDraftMessage,
        'PlayerOpensSettings': PlayerOpensSettings,
        'SettingsSaved': SettingsSaved,
        'PlayerStartsChapterIntro': PlayerStartsChapterIntro,
    }

    constructor() {
    }

    static createGameEventFromResponse(response) {
        let eventType = response.getParam('eventType');
        let eventData = response.getParam('data');
        let event;

        if (eventType && (eventType in EventsFactory.classMapGameEvents)) {
            event = new (EventsFactory.classMapGameEvents[eventType])(eventData);
            App().debug('Successfully created event of type: '  + eventType);
        } else {
            throw new Error('Could not create game event from response: given eventType is not configured - '  + eventType)
        }

        return event;
    }
}