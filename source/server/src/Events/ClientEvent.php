<?php

declare(strict_types=1);

namespace Server\Events;

class ClientEvent
{

    const PLAYER_ENTERS_THE_WORLD = 'PlayerEntersTheWorld';
    const PLAYER_ENTERS_TERRITORY = 'PlayerEntersTerritory';
    const PLAYER_ENTERS_LOCATION_DETAILS = 'PlayerEntersLocationDetails';
    const PLAYER_ENTERS_ARENA = 'PlayerEntersArena';
    const PLAYER_ENTERS_SHOP = 'PlayerEntersShop';
    const PLAYER_ENTERS_ELIXIR_LAB = 'PlayerEntersElixirLab';
    const PLAYER_ENTERS_BARRACKS = 'PlayerEntersBarracks';
    const PLAYER_OPENS_BARRACKS_INFO_WINDOW_STATS = 'PlayerOpensBarracksInfoWindowStats';
    const PLAYER_OPENS_BARRACKS_INFO_WINDOW_SKILLS = 'PlayerOpensBarracksInfoWindowSkills';
    const PLAYER_OPENS_BARRACKS_INFO_WINDOW_ELIXIRS = 'PlayerOpensBarracksInfoWindowElixirs';
    const PLAYER_OPENS_BARRACKS_INFO_WINDOW_APPLY_ELIXIR = 'PlayerOpensBarracksInfoWindowApplyElixir';
    const PLAYER_OPENS_BARRACKS_INFO_WINDOW_TALENTS = 'PlayerOpensBarracksInfoWindowTalents';
    const PLAYER_OPENS_INVENTORY = 'PlayerOpensInventory';
    const PLAYER_OPENS_CHATS_OVERVIEW = 'PlayerOpensChatsOverview';
    const PLAYER_OPENS_CHAT_DETAILS = 'PlayerOpensChatDetails';
    const PLAYER_OPENS_SETTINGS = 'PlayerOpensSettings';
    const PLAYER_ACCEPTS_DRAFT_MESSAGE = 'PlayerAcceptsDraftMessage';
    const PLAYER_OPENS_CHAPTER_OVERVIEW = 'PlayerOpensChapterOverview';
    const PLAYER_STARTS_CHAPTER_INTRO = 'PlayerStartsChapterIntro';
    const UPDATE_SHOP_DATA = 'UpdateShopData';
    const PLAYER_BUYS_IN_ITEM = 'PlayerBuysItemInShop';
    const PLAYER_CREATES_ITEM_IN_LAB = 'PlayerCreatesItemInLab';
    const PLAYER_APPLIES_ELIXIR = 'PlayerAppliesElixir';
    const PLAYER_CLEARS_ELIXIR = 'PlayerClearsElixir';
    const PLAYER_ACTIVATES_TALENT = 'PlayerActivatesTalent';
    const PLAYER_USES_ITEM = 'PlayerUsesItem';
    const PLAYER_CHANGES_LOCATION = 'PlayerChangesLocation';
    const PLAYER_STARTS_LOCATION_CHANGE = 'PlayerStartsLocationChange';
    const PLAYER_GETS_LOCATION_INVESTIGATION_RESULTS = 'PlayerGetsLocationInvestigationResults';
    const PLAYER_SELECTS_FIRST_HERO = 'PlayerSelectsFirstHero';
    const INIT_GAME_TRANSLATIONS = 'InitGameTranslations';
    const DISPLAY_OWN_PET_FULL_INFO = 'DisplayOwnPetFullInfo';
    const INIT_CLI = 'InitCli';
    const UPDATE_CLI = 'UpdateCli';
    const UPDATE_TOP_BAR = 'UpdateTopBar';
    const PLAYER_STARTS_BATTLE_SEARCH = 'PlayerStartsBattleSearch';
    const PLAYER_CANCELS_BATTLE_SEARCH = 'PlayerCancelsBattleSearch';
    const BATTLE_STARTED_ONE_VS_ONE = 'BattleStartedOneVsOne';
    const BATTLE_ENDED_ONE_VS_ONE = 'BattleEndedOneVsOne';
    const BATTLE_UNIT_STARTS_SKILL_ANIMATION = 'BattleUnitStartsSkillAnimation';
    const BATTLE_UNIT_ENDS_SKILL_CASTING = 'BattleUnitEndsSkillCasting';
    const BATTLE_UNIT_USES_SKILL = 'BattleUnitUsesSkill';
    const BATTLE_UPDATE_UNIT_DATA = 'BattleUpdateUnitData';
    const BATTLE_UNIT_BUFF_TICK = 'BattleUnitBuffTick';
    const SETTINGS_SAVED = 'SettingsSaved';
    const DISPLAY_FLASH_MESSAGE = 'DisplayFlashMessage';
    const ERROR_AUTHENTICATION = 'ErrorAuthentication';
    const ERROR_GENERAL = 'ErrorGeneral';
    const ERROR_BATTLE_NOT_FOUND = 'ErrorBattleNotFound';
    const ERROR_NPC_GONE_AWAY = 'ErrorNpcGoneAway';

}