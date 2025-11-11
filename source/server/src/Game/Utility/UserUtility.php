<?php

declare(strict_types=1);

namespace Server\Game\Utility;

use Psr\Log\LogLevel;
use React\EventLoop\Loop;
use Server\Game\Battle\BaseBattle;
use Server\Game\Battle\BattleBuff;
use Server\Game\Battle\BattleSkill;
use Server\Game\Battle\BattleUnit;
use Server\Game\Consts\UserAttribute;
use Server\Game\Models\Elixir;
use Server\Game\Models\Item;
use Server\Game\Models\Pet;
use Server\Game\Models\User;

/**
 *
 */
class UserUtility
{

    const ALLOWED_SETTINGS = [
        'language' => [
            'name' => 'USER_SETTINGS.LANGUAGE',
            'type' => 'select',
            'values' => [
                'en' => 'English',
                'de' => 'Deutsch',
                'es' => 'Español',
                'fr' => 'Français',
                'it' => 'Italiano',
                'pt' => 'Português',
                'ua' => 'Українська',
                'zh' => '中文',
                'ja' => '日本語',
                'ko' => '한국어',
                'ar' => 'العربية',
                'hi' => 'हिन्दी',
                'tr' => 'Türkçe',
                'pl' => 'Polski',
                'nl' => 'Nederlands',
                'vi' => 'Tiếng Việt',
                'th' => 'ไทย',
                'id' => 'Indonesia',
                'cs' => 'Čeština',
                'sv' => 'Svenska'
            ],
            'default' => 'en',
        ],
        'sounds' => [
            'name' => 'USER_SETTINGS.SOUNDS',
            'type' => 'checkbox',
            'default' => '1',
        ],
        'chapterIntroViewed' => [
            'name' => 'USER_SETTINGS.CHAPTER_INTRO_VIEWED',
            'type' => 'hidden',
            'default' => '0',
        ]
    ];
    
    /**
     * @param \Server\Game\Models\User $user
     *
     * @return bool
     */
    public static function isUserBusy(User $user): bool
    {
        if ($user->getCurrentBattleQueue() !== null) {
            return true;
        }

        if ($user->getCurrentBattle() !== null) {
            return true;
        }

        if ($user->getAttribute(UserAttribute::IS_MOVING_TO_OTHER_LOCATION)) {
            return true;
        }

        return false;
    }

    public static function userCanSendSystemMessages(User $user)
    {
        return true;
    }

    /**
     * Returns User data that can safely be passed back to the client
     *
     * @param \Server\Game\Models\User $user
     *
     * @return array
     */
    public static function prepareUserDataForResponse(User $user): array
    {
        $userData = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            \Server\Game\Consts\User::USER_CURRENCY_1 => $user->getCurrency1(),
            \Server\Game\Consts\User::USER_CURRENCY_2 => $user->getCurrency2(),
            'avatar' => $user->getAvatar(),
        ];

        if ($user->getSelectedPets()) {
            foreach ($user->getSelectedPets() as $pet) {
                $userData['selectedPets'][$pet->getUnitCode()] = [
                    'code' => $pet->getUnitCode(),
                    'sprite' => $pet->getSprite(),
                    'name' => TranslationsUtility::getTranslation($pet->getName(), TranslationsUtility::TYPE_UNITS, (string)$user->getLang()),
                ];
            }
        }

        return $userData;
    }

    /**
     * Returns User status data that can safely be passed back to the client
     *
     * @param \Server\Game\Models\User $user
     *
     * @return array
     */
    public static function prepareUserStatusForResponse(User $user): array
    {
        $userStatus = [
            'isBusy' => $user->isBusy(),
            'isInBattleQueue' => !empty($user->getCurrentBattleQueue()),
            'isInBattle' => !empty($user->getCurrentBattle()),
        ];

        return $userStatus;
    }

    /**
     * @param \Server\Game\Models\User $user
     *
     * @return void
     */
    public static function prefillPropertiesWithDefaultValues(User $user)
    {
        // default location code
        if (empty($user->getLocationCode())) {
            $user->setLocationCode(\Server\Game\Consts\User::DEFAULT_LOCATION_CODE);
        }

        // default settings
        $userSettings = $user->getSettings();

        foreach (self::ALLOWED_SETTINGS as $settingName => $settingData) {
            $defaultValue = $settingData['default'];
            $newValue = $userSettings[$settingName] ?? $defaultValue;
            $userSettings[$settingName] = $newValue;
        }

        $user->setSettings($userSettings);

        // default avatar
        if (empty($user->getAvatar())) {
            $user->setAvatar(\Server\Game\Consts\User::DEFAULT_AVATAR);
        }
    }


    /**
     * @param \Server\Game\Models\User $user
     * @param string $npcCode
     *
     * @return void
     */
    public static function addSearchedNpsCodeForUser(User $user, string $npcCode)
    {
        $id = $user->addSearchedNpcCode($npcCode);

        // @todo use const for interval
        Loop::addTimer(60, function () use ($user, $id, $npcCode) {
            $currentId = $user->getSearchedNpcCodeId($npcCode);

            // id is not changed  means no other concurrent timers for the same code
            if ($currentId === $id) {
                $user->removeSearchedNpcCode($npcCode);
            }
        });
    }

    /**
     * @param User $user
     * @return array
     * @throws \Exception
     */
    public static function prepareInventoryItemsDataForResponse(User $user): array
    {
        $itemsData = [];

        /** @var Item $item */
        foreach ($user->getItems() as $userItem) {
            $item = App()->getWorld()->getItems()[$userItem->getItemCode()] ?? null;

            if (empty($item)) {
                throw new \Exception('Invalid related item code for user item ' . $userItem->getItemCode());
            }

            $itemsData[] = [
                'code' => $item->getCode(),
                'icon' => $item->getIcon(),
                'rarity' => $item->getRarity(),
                'type' => $item->getType(),
                'usable' => !empty($item->getUseFunction()),
                'amount' => $userItem->getAmount(),
                'description' => TranslationsUtility::getTranslation($item->getDescription(), TranslationsUtility::TYPE_ITEMS, (string)$user->getLang()),
                'name' => TranslationsUtility::getTranslation($item->getName(), TranslationsUtility::TYPE_ITEMS, (string)$user->getLang()),
            ];
        }

        return $itemsData;
    }
}